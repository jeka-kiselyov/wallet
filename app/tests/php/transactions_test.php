<?php
 
 class transactions_test extends PHPUnit_Framework_0F
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

    /**
     * Should not save scheduled transactions
     */
    public function test_exeption_saving_scheduled()
    {
        $transaction = new transaction;
        $transaction->type = 'profit';
        $transaction->subtype = 'scheduled';
        $transaction->wallet_id = $this->wallet->id;
        $transaction->user_id = $this->test_user_id;
        $transaction->datetime = time();

        $this->setExpectedException('entityvalidation_exception');  /// Should thrown exception
        $transaction->save();
    }

    /**
     * Should not save profit transaction with negative amount
     */
    public function test_exeption_saving_positive()
    {
        // Should not save profit transaction with negative value
        $transaction = new transaction;
        $transaction->type = 'profit';
        $transaction->subtype = 'confirmed';
        $transaction->amount = -10;
        $transaction->wallet_id = $this->wallet->id;
        $transaction->user_id = $this->test_user_id;
        $transaction->datetime = time();

        $this->setExpectedException('entityvalidation_exception');  /// Should thrown exception
        $transaction->save();
    }

    /**
     * Should not save expense transaction with positive amount
     */
    public function test_exeption_saving_negative()
    {
        // Should not save profit transaction with negative value
        $transaction = new transaction;
        $transaction->type = 'expense';
        $transaction->subtype = 'confirmed';
        $transaction->amount = 999;
        $transaction->wallet_id = $this->wallet->id;
        $transaction->user_id = $this->test_user_id;
        $transaction->datetime = time();

        $this->setExpectedException('entityvalidation_exception');  /// Should thrown exception
        $transaction->save();
    }

    public function test_transaction_removing()
    {
        $transaction = $this->wallet->addProfit(100, 'Testing');

        $this->assertEquals(100, $this->wallet->total);

        $wallet_id = $this->wallet->id;
        $transaction->delete();

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(0, $this->wallet->total);

        $transaction1 = $this->wallet->addProfit(100, 'Testing');
        $transaction2 = $this->wallet->addExpense(10, 'Testing');

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(90, $this->wallet->total);

        $transaction2->delete();

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(100, $this->wallet->total);

        $transaction1->delete();
        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(0, $this->wallet->total);

        $transaction2 = $this->wallet->addExpense(10, 'Testing');
        $transaction1 = $this->wallet->addProfit(100, 'Testing');

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(90, $this->wallet->total);

        $transaction2->delete();

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(100, $this->wallet->total);

        $transaction1->delete();
        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(0, $this->wallet->total);

        /// now check setup. 
        $transaction1 = $this->wallet->addProfit(100, 'Testing');
        $transaction2 = $this->wallet->setTotalTo(150);

        $this->assertEquals(150, $this->wallet->total);
        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(150, $this->wallet->total);

        $setup_transaction_id = $transaction2->id;
        $check_setup_transaction = $this->transactions->get_by_id($setup_transaction_id);
        $this->assertEquals(50, $check_setup_transaction->amount); /// diif from 150 to 100 (1st transaction amount)

        $transaction1->delete(); /// should not affect wallet's total, as there's setup transaction after it
        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(150, $this->wallet->total);

        $check_setup_transaction = $this->transactions->get_by_id($setup_transaction_id);
        $this->assertEquals(150, $check_setup_transaction->amount); /// Should be 150 now, as 1st transaction is removed

        $check_setup_transaction->delete();
        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(0, $this->wallet->total);
        
        //// now check two setup transactions
           
        $transaction1 = $this->wallet->addProfit(100, 'Testing');
        $transaction2 = $this->wallet->addExpense(50, 'Testing expense');
        $transaction_setup_1 = $this->wallet->setTotalTo(200);

        $transaction3 = $this->wallet->addExpense(50, 'Testing expense');
        $transaction4 = $this->wallet->addExpense(50, 'Testing expense');

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(100, $this->wallet->total);

        $transaction_setup_2 = $this->wallet->setTotalTo(300);

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(300, $this->wallet->total);

        $transaction5 = $this->wallet->addExpense(50, 'Testing expense');

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(250, $this->wallet->total);

        $transaction2->delete();
        $transaction_setup_1 = $this->transactions->get_by_id($transaction_setup_1->id);
        $this->assertEquals(100, $transaction_setup_1->amount); // changed

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(250, $this->wallet->total);     // not changed

        $transaction_setup_2 = $this->transactions->get_by_id($transaction_setup_2->id);
        $this->assertEquals(200, $transaction_setup_2->amount);  /// not changed

        $transaction_setup_1->delete();  

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(250, $this->wallet->total);     // not changed

        $transaction_setup_2 = $this->transactions->get_by_id($transaction_setup_2->id);
        $this->assertEquals(300, $transaction_setup_2->amount);  /// changed

        $transaction_setup_2->delete();

        $this->wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals(-50, $this->wallet->total);     // there's no setup now. Just 100 - 50 - 50 - 50

    }

	public function tearDown()
	{
        $this->wallet->delete();
	}

 }
