<?php
 
 class accesses_test extends PHPUnit_Framework_0F
 {

    public function setUp()
    {
    }

    public function test_accesses()
    {
        $user1 = new user;
        $user1->email = 'random1'.rand(0, time()).time()."@gmail.com";
        $user1->login = $user1->email;
        $user1->save();
        $this->assertInstanceOf('user', $user1);

        $wallet1 = new wallet;
        $wallet1->user_id = $user1->id;
        $wallet1->name = 'Profits';
        $wallet1->save();
        $this->assertInstanceOf('wallet', $wallet1);

        $user2 = new user;
        $user2->email = 'random1'.rand(0, time()).time()."@gmail.com";
        $user2->login = $user2->email;
        $user2->save();
        $this->assertInstanceOf('user', $user2);

        $wallet2 = new wallet;
        $wallet2->user_id = $user2->id;
        $wallet2->name = 'Profits';
        $wallet2->save();
        $this->assertInstanceOf('wallet', $wallet2);

        /// should have access to his own wallets
        $this->assertTrue($user1->hasAccessToWallet($wallet1->id)); // get from user entity
        $this->assertTrue($wallet1->hasAccess($user1->id)); // get from user entity
        $this->assertTrue($user2->hasAccessToWallet($wallet2->id)); // get from user entity
        $this->assertTrue($wallet2->hasAccess($user2->id)); // get from user entity

        //// should not have access to others wallets
        $this->assertFalse($user2->hasAccessToWallet($wallet1->id)); // get from user entity
        $this->assertFalse($wallet2->hasAccess($user1->id)); // get from user entity
        $this->assertFalse($user1->hasAccessToWallet($wallet2->id)); // get from user entity
        $this->assertFalse($wallet1->hasAccess($user2->id)); // get from user entity

        //// list of accesses for wallet should be default empty
        $accesses = $wallet1->getAccesses();
        $this->assertEquals(0, count($accesses));

        ///// try to give access for user2 to wallet1
        $success = $wallet1->giveAccess($user2->email);
        $this->assertTrue($success);
        $accesses = $wallet1->getAccesses();
        $this->assertEquals(1, count($accesses));
        /////////Should have access now
        $this->assertTrue($user2->hasAccessToWallet($wallet1->id)); // get from user entity
        $this->assertTrue($wallet1->hasAccess($user2->id)); // get from user entity
        // but not vice versa
        $this->assertFalse($user1->hasAccessToWallet($wallet2->id)); // get from user entity
        $this->assertFalse($wallet2->hasAccess($user1->id)); // get from user entity

        /////// try to get from model
        $with_access = $this->wallets->find_shared_with_user_id($user2->id);
        $this->assertEquals(count($with_access), 1);

        $this->assertInstanceOf('wallet', $with_access->at(0));
        $this->assertEquals($with_access->at(0)->id, $wallet1->id);

        $user1->delete();
        $user2->delete();
        $wallet1->delete();
        $wallet2->delete();
    }

    public function tearDown()
    {
    }
 }
