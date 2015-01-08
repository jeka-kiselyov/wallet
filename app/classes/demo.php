<?php

class demo extends singleton_base 
{
  public function fill_demo_account($user_id)
  {
    /// 1st step. Create two wallets
    $user = $this->users->get_by_id($user_id);
    $currency = 'USD';

    $wallet_1_name = 'Sample Cash Wallet';
    $wallet_2_name = 'Sample Bank Account Wallet';
    $wallet_1 = $user->createWallet($wallet_1_name, $currency);
    $wallet_2 = $user->createWallet($wallet_2_name, $currency);

    //// 2nd step - add initial profit to wallets
    $initial_1_description = 'Profit';
    $initial_2_description = 'My freelance work';
    $initial_1_amount = 4000 + rand(0,50)*100;
    $initial_2_amount = 3000 + rand(0,50)*100; 

    $transaction = $wallet_1->addTransaction($initial_1_amount, $initial_1_description);
    $this->move_transaction_to_the_past($transaction, 70);
    $transaction = $wallet_2->addTransaction($initial_2_amount, $initial_2_description);
    $this->move_transaction_to_the_past($transaction, 50);

    $descriptions1 = array();
    $descriptions1[] = 'Vodka';
    $descriptions1[] = 'Beer';
    $descriptions1[] = 'Candies';
    $descriptions1[] = 'Date with Sammy';
    $descriptions1[] = 'Sausages';
    $descriptions1[] = 'Food';
    $descriptions1[] = 'Foods';
    $descriptions1[] = 'Eat out';
    $descriptions1[] = 'Mall';
    $descriptions1[] = 'Cinema';
    $descriptions1[] = 'Gas';
    $descriptions1[] = 'Wi-Fi access';
    $descriptions1[] = 'Wi-Fi';
    $descriptions1[] = 'Wine';

    $descriptions2 = array();
    $descriptions2[] = 'Hosting';
    $descriptions2[] = 'Custom Software';
    $descriptions2[] = 'Amazon S3';
    $descriptions2[] = 'Amazon AWS';
    $descriptions2[] = 'Wordpress template';
    $descriptions2[] = 'Translations';
    $descriptions2[] = 'Data Gathering';
    $descriptions2[] = 'CSS work';
    $descriptions2[] = 'Adwords';
    $descriptions2[] = 'PPC Campaign';
    $descriptions2[] = 'PPM Campaign';
    $descriptions2[] = 'Content writing';
    $descriptions2[] = 'iStock';
    $descriptions2[] = 'Shutterstock';
    $descriptions2[] = 'Gettyimages';
    $descriptions2[] = 'Photobank';

    for ($i = 69; $i > 0; $i--)
    {
      shuffle($descriptions1);
      $amount = rand(0, (int)($initial_1_amount / 30)) + (rand(0,100)*0.01);
      $transaction = $wallet_1->addExpense($amount, $descriptions1[0]);
      $this->move_transaction_to_the_past($transaction, $i - rand(0,80)*0.01);

      if ($wallet_1->total < 300)
      {
        $transaction = $wallet_1->addTransaction(rand(100,1000), $initial_1_description);
        $this->move_transaction_to_the_past($transaction, $i - 0.81 + rand(0,15)*0.01);
      }

    }

    for ($i = 49; $i > 0; $i--)
    {
      shuffle($descriptions2);
      $amount = rand(10, 11+(int)($initial_2_amount / 20));
      if (rand(0,5) === 1)
        $amount = $amount - 0.01;

      $transaction = $wallet_2->addExpense($amount, $descriptions2[0]);
      $this->move_transaction_to_the_past($transaction, $i - rand(0,80)*0.01);

      if ($wallet_2->total < 1000)
      {
        $transaction = $wallet_2->addTransaction(rand(1,10)*100, $initial_2_description);
        $this->move_transaction_to_the_past($transaction, $i - 0.81 + rand(0,15)*0.01);
      }
    }
  }

  private function move_transaction_to_the_past($transaction, $days_offset)
  {
    $transaction->datetime = time() - (int)($days_offset * 24 * 60 * 60);
    $transaction->save();

    return $transaction;
  }
}




