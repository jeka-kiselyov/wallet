<?php
 
 class transactions_test extends PHPUnit_Framework_0F
 {
 	private $test_user_id = false;

 	public function setUp()
    {
        $this->test_user_id = rand(1,9999999);
    }

    public function test_date_normalization()
    {
        $time = time();

        for ($i = 0; $i < 32; $i++)
        {
            $time = $time+24*60*60;
            $normalized = $this->time_helper->ceilDate($time);  //// midnight of the next day

            $this->assertEquals(date("G",$normalized), 0);
            $this->assertEquals(intval(date("i",$normalized)), 0);

            $this->assertTrue( date("j",$normalized)-1 == date("j", $time) || date("j",$normalized) == 1 );

            $this->assertTrue( ($normalized-$time) <= 24*60*60 );
        }

        for ($i = 0; $i < 32; $i++)
        {
            $time = $time+24*60*60;
            $normalized = $this->time_helper->floorDate($time);  //// today's midnight

            $this->assertEquals(date("G",$normalized), 0);
            $this->assertEquals(intval(date("i",$normalized)), 0);

            $this->assertTrue( date("j",$normalized) == date("j", $time) );

            $this->assertTrue( ($time-$normalized) <= 24*60*60 );
        }
    }

    public function test_date_next()
    {
        $start_date = strtotime("2 July 2014");
        $this->assertEquals(date("Y-n-j", $start_date),"2014-7-2");
        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 4, 0, 0); /// every month on 4th
        $this->assertEquals(date("Y-n-j", $next),"2014-7-4");
        $nextnext = $this->time_helper->findNextOccurenceDate($next, 0, 0, 4, 0, 0); /// every month on 4th 
        $this->assertEquals(date("Y-n-j", $nextnext),"2014-8-4");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 0, 1, 0); /// every day on the first week
        $this->assertEquals(date("Y-n-j", $next),"2014-7-3");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 0, 2, 0); /// every day on the second week
        $this->assertEquals(date("Y-n-j", $next),"2014-7-7");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 0, 5, 0); /// every day on the last week
        $this->assertEquals(date("Y-n-j", $next),"2014-7-28");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 0, 0, 2); /// every tuesday
        $this->assertEquals(date("Y-n-j", $next),"2014-7-8");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 0, 0, 0, 7); /// every sunday
        $this->assertEquals(date("Y-n-j", $next),"2014-7-6");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 0, 8, 16, 0, 0); /// every August 16th
        $this->assertEquals(date("Y-n-j", $next),"2014-8-16");

        $next = $this->time_helper->findNextOccurenceDate($start_date, 2014, 9, 3, 0, 0); /// Sep 3, 2014
        $this->assertEquals(date("Y-n-j", $next),"2014-9-3");
    }


    public function test_scheduled()
    {
        $wallet = new wallet;
        $wallet->user_id = $this->test_user_id;
        $wallet->name = 'Profits';
        $wallet->save();

        $transaction = $wallet->addProfit(10, 'Test transaction');
        $transaction->makeReccurented(0,0,0,0,1);  /// every Monday

        $transactions = $wallet->getTransactions(time()-3, time()+24*60*60*30); // 30 days in future

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
        $this->assertEquals($transactions[1]->wallet_id, $wallet->id);

        $this->assertEquals($transactions[2]->type, 'profit'); /// 3rd one is scheduled
        $this->assertEquals($transactions[2]->subtype, 'scheduled');
        $this->assertEquals($transactions[2]->amount, 10);
        $this->assertEquals(date("N", $transactions[2]->datetime), 1); // Monday

        $this->assertNotEquals($transactions[2]->datetime, $transactions[1]->datetime); // Different
        $this->assertNotEquals($transactions[2]->datetime, $transactions[0]->datetime); // Different
        $this->assertNotEquals($transactions[0]->datetime, $transactions[1]->datetime); // Different

        $wallet->delete();
    }

	public function tearDown()
	{
	}

 }
