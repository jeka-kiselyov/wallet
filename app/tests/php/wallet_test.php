<?php
 
 class wallet_test extends PHPUnit_Framework_0F
 {
 	private $test_user_id = false;

 	public function setUp()
    {
        $this->test_user_id = rand(1,9999999);
    }

    protected function create_test_wallet()
    {
        $wallet = new wallet;
        $wallet->user_id = $this->test_user_id;
        $wallet->name = 'Profits';
        $wallet->save();

        return $wallet;
    }

    public function test_wallet_creation()
    {
        $wallet = new wallet;

        $this->assertInstanceOf('wallet', $wallet);

        $wallet->user_id = $this->test_user_id;
        $wallet->name = 'Undefined';
        $wallet->save();

        $wallet_id = $wallet->id;

        $wallet = $this->wallets->get_by_id($wallet_id);

        $this->assertInstanceOf('wallet', $wallet);

        $this->assertEquals($wallet->id, $wallet_id);
        $this->assertEquals($wallet->name, 'Undefined');
        $this->assertEquals($wallet->user_id, $this->test_user_id);
        $this->assertEquals($wallet->total, 0);
        $this->assertEquals($wallet->type, 'default');

        $wallet->delete();
    }

    public function test_wallet_removal()
    {
        $wallet = $this->create_test_wallet();

        $wallet->addTransaction(50, 'Initializing', 'profit', 'setup');
        $wallet->addTransaction(50, 'Initializing', 'profit', 'confirmed');
        $wallet->addTransaction(50, 'Initializing', 'profit', 'planned');

        $wallet->addTransaction(-50, 'Initializing', 'expense', 'setup');
        $wallet->addTransaction(-50, 'Initializing', 'expense', 'confirmed');
        $wallet->addTransaction(-50, 'Initializing', 'expense', 'planned');

        $wallet_id = $wallet->id;

        /// 6 transactions assigned
        $this->assertEquals(count($wallet->getTransactions()), 6);
        $transactions = $this->transactions->find_by_wallet_id($wallet_id);
        $this->assertEquals(count($transactions), 6);

        $wallet = $this->wallets->get_by_id($wallet_id);
        $wallet->delete();

        $wallet = $this->wallets->get_by_id($wallet_id);
        $this->assertEquals($wallet, false);
        // related transactions should be removed too
        $transactions = $this->transactions->find_by_wallet_id($wallet_id);
        $this->assertEquals(count($transactions), 0);
    }

    public function test_total()
    {
        $wallet = $this->create_test_wallet();

        $wallet->addTransaction(50, 'Initializing', 'profit', 'setup');
        $this->assertEquals($wallet->total, 50);

        // floating
        $wallet->addTransaction(50.99, 'name', 'profit', 'confirmed');
        $this->assertEquals($wallet->total, 100.99);

        // Default is confirmed
        $wallet->addTransaction(50, 'name', 'profit'); 
        $this->assertEquals($wallet->total, 150.99);

        // Planned should not touch total
        $wallet->addTransaction(50, 'name', 'profit', 'planned'); 
        $this->assertEquals($wallet->total, 150.99);

        // Try expense. Default is confirmed
        $wallet->addTransaction(-0.99, 'name', 'expense'); 
        $this->assertEquals($wallet->total, 150);

        // Planned should not touch total
        $wallet->addTransaction(-150, 'name', 'expense', 'planned'); 
        $this->assertEquals($wallet->total, 150);

        $wallet_id = $wallet->id;

        $wallet->delete();
        $transactions = $this->transactions->find_by_wallet_id($wallet_id);
        $this->assertEquals(count($transactions), 0);
    }

    public function test_shortcodes()
    {
        $wallet = $this->create_test_wallet();
        $wallet->addProfit(150.99, 'Money from my mom');
        $wallet->addExpense(50, 'For vodka in bar');
        $this->assertEquals(100.99, $wallet->total);

        $wallet->setTotalTo(432.09);
        $this->assertEquals(432.09, $wallet->total);

        $transactions = $wallet->getTransactions();
        // foreach ($transactions as $t)
        //     echo $t->amount."\n";

        // echo "\n";
        $last = $transactions[count($transactions)-1];

        $this->assertEquals(432.09-100.99, $last->amount);

        $wallet->delete();
    }

	public function tearDown()
	{
	}

 }
