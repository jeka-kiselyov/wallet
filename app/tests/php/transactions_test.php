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

	public function tearDown()
	{
        $this->wallet->delete();
	}

 }
