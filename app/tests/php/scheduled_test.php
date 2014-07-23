<?php
 
 class scheduled_test extends PHPUnit_Framework_0F
 {
    private $test_user_id = false;

    public function setUp()
    {
        $this->test_user_id = rand(1,9999999);
        $this->wallet = new wallet;
        $this->wallet->user_id = $this->test_user_id;
        $this->wallet->name = 'Profits';
        $this->wallet->save();
    }

    public function test_move_to_next()
    {
        $transaction = $this->wallet->addProfit(10, 'Test transaction');
        $transaction->makeReccurented(0,0,1,0,0);  /// every first day of the month

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*62); // get transactions for 2 month in the future

        // foreach ($transactions as $t) 
        //     echo date("r", $t->datetime)." ".$t['subtype']."\n";

        // echo "\n";

        $this->assertEquals($transactions[0]->type, 'profit'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->subtype, 'confirmed'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->amount, 10);

        $this->assertEquals($transactions[1]->subtype, 'scheduled');  /// 2nd one is scheduled

        $this->assertEquals($this->wallet->total, 10); // Check total

        $scheduled = $transactions[1];
        $new_transaction = $scheduled->moveToNext();

        $this->assertEquals($this->wallets->get_by_id($this->wallet->id)->total, 20); // Should be 20 now, as scheduled transaction is added. NOTE! Need to reload wallet.

        $this->assertEquals($new_transaction->type, 'profit'); /// 1st one is the one we have created
        $this->assertEquals($new_transaction->subtype, 'confirmed'); /// 1st one is the one we have created
        $this->assertEquals($new_transaction->amount, 10);

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*92); // get transactions for 3 months in the future


        $this->assertEquals($transactions[0]->type, 'profit'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->subtype, 'confirmed'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->amount, 10);

        $this->assertEquals($transactions[1]->type, 'profit'); /// 2nd one should be ready now too
        $this->assertEquals($transactions[1]->subtype, 'confirmed'); 
        $this->assertEquals($transactions[1]->amount, 10);

        $this->assertEquals($transactions[2]->type, 'profit'); /// 3rd is scheduled
        $this->assertEquals($transactions[2]->subtype, 'scheduled'); 
        $this->assertEquals($transactions[2]->amount, 10);

        $scheduled = $transactions[2];
        $new_transaction = $scheduled->moveToNext();

        $this->assertEquals($this->wallets->get_by_id($this->wallet->id)->total, 30); // Should be 30 now, as scheduled transaction is added. NOTE! Need to reload wallet.

        $this->assertEquals($new_transaction->type, 'profit'); /// 1st one is the one we have created
        $this->assertEquals($new_transaction->subtype, 'confirmed'); /// 1st one is the one we have created
        $this->assertEquals($new_transaction->amount, 10);

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*122); // get transactions for 4 months in the future

        // foreach ($transactions as $t) 
        //     echo date("r", $t->datetime)." ".$t['subtype']."\n";

        $this->assertEquals($transactions[0]->subtype, 'confirmed');
        $this->assertEquals($transactions[1]->subtype, 'confirmed');
        $this->assertEquals($transactions[2]->subtype, 'confirmed');
        $this->assertEquals($transactions[3]->subtype, 'scheduled');

    }

    public function test_move_to_next_double()
    {
        $transaction = $this->wallet->addProfit(2, 'Test transaction1');
        $transaction->makeReccurented(0,0,2,0,0);  /// every second day of the month

        $transaction = $this->wallet->addProfit(4, 'Test transaction2');
        $transaction->makeReccurented(0,0,2,0,0);  /// every second day of the month

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*62); // get transactions for 2 month in the future

        // foreach ($transactions as $t) 
        //     echo date("r", $t->datetime)." ".$t['subtype']."\n";

        $this->assertEquals($transactions[0]->subtype, 'confirmed');
        $this->assertEquals($transactions[1]->subtype, 'confirmed');

        $this->assertEquals($transactions[2]->subtype, 'scheduled');
        $this->assertEquals($transactions[3]->subtype, 'scheduled');

        $transactions[2]->moveToNext();
        $transactions[3]->moveToNext();

        $this->assertEquals($this->wallets->get_by_id($this->wallet->id)->total, 12); // Should be 12 as there re two new transactions. NOTE! Need to reload wallet.

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*4*31); // get transactions for 4 month in the future

        $this->assertEquals($transactions[0]->subtype, 'confirmed');
        $this->assertEquals($transactions[1]->subtype, 'confirmed');

        $this->assertEquals($transactions[2]->subtype, 'confirmed');
        $this->assertEquals($transactions[3]->subtype, 'confirmed');

        $this->assertEquals($transactions[4]->subtype, 'scheduled');
        $this->assertEquals($transactions[5]->subtype, 'scheduled');

        $transactions[5]->moveToNext();

        $total = $this->wallets->get_by_id($this->wallet->id)->total;
        $this->assertTrue( ($total == 14 || $total == 16) ); // Should be 14 or 16, depending on what transaction has been activated. NOTE! Need to reload wallet.
    }


    public function test_scheduled()
    {
        $transaction = $this->wallet->addProfit(10, 'Test transaction');
        $transaction->makeReccurented(0,0,0,0,1);  /// every Monday

        $transactions = $this->wallet->getTransactions(time()-3, time()+24*60*60*30); // 30 days in future

        // foreach ($transactions as $t)
        //     echo $t->amount." ".$t->subtype." ".date("r", $t->datetime)."\n";
        // echo "\n";

        $this->assertEquals($transactions[0]->type, 'profit'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->subtype, 'confirmed'); /// 1st one is the one we have created
        $this->assertEquals($transactions[0]->amount, 10);

        $this->assertEquals($transactions[1]->type, 'profit'); /// 2nd one is scheduled
        $this->assertEquals($transactions[1]->subtype, 'scheduled');
        $this->assertEquals($transactions[1]->amount, 10);
        $this->assertEquals(date("N", $transactions[1]->datetime), 1); // Monday
        $this->assertEquals($transactions[1]->description, 'Test transaction');
        $this->assertEquals($transactions[1]->user_id, $this->test_user_id);
        $this->assertEquals($transactions[1]->wallet_id, $this->wallet->id);

        $this->assertEquals($transactions[2]->type, 'profit'); /// 3rd one is scheduled
        $this->assertEquals($transactions[2]->subtype, 'scheduled');
        $this->assertEquals($transactions[2]->amount, 10);
        $this->assertEquals(date("N", $transactions[2]->datetime), 1); // Monday

        $this->assertNotEquals($transactions[2]->datetime, $transactions[1]->datetime); // Different
        $this->assertNotEquals($transactions[2]->datetime, $transactions[0]->datetime); // Different
        $this->assertNotEquals($transactions[0]->datetime, $transactions[1]->datetime); // Different
    }

    public function tearDown()
    {
        $this->wallet->delete();
    }
 }
