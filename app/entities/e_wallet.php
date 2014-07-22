<?php

	class wallet extends entity_base
	{
		public function getTransactions($from = false, $to = false)
		{
			return $this->transactions->getForWalletId($this->id, $from, $to);
		}

		/**
		 * Set total amount of money in wallet to value. Add setup transaction with difference.
		 * @param [type]  $total    [description]
		 * @param boolean $datetime [description]
		 */
		public function setTotalTo($total, $datetime = false)
		{
			if ($total == $this->total || $total < 0)
				return false;

			$diff = $total - $this->total;

			$type = 'profit';
			if ($diff < 0)
				$type = 'expense';

			return $this->addTransaction($diff, '', $type, 'setup', $datetime);
		}


		/**
		 * Add profit transaction
		 * @param [type]  $amount      [description]
		 * @param [type]  $description [description]
		 * @param boolean $datetime    [description]
		 */
		public function addProfit($amount, $description, $datetime = false)
		{
			return $this->addTransaction($amount, $description, 'profit', 'confirmed', $datetime);
		}

		/**
		 * Add expense transaction
		 * @param [type]  $amount      [description]
		 * @param [type]  $description [description]
		 * @param boolean $datetime    [description]
		 */
		public function addExpense($amount, $description, $datetime = false)
		{
			return $this->addTransaction(-$amount, $description, 'expense', 'confirmed', $datetime);
		}

		public function addTransaction($amount, $description, $type, $subtype = false, $datetime = false, $user_id = false)
		{
	 		if (!$this->id)
	 			return false;
			if (!$datetime)
				$datetime = time();
			if (!$user_id)
				$user_id = $this->user_id;

			$transaction = new transaction;
			$transaction->amount = $amount;
			$transaction->description = $description;
			$transaction->type = $type;
			$transaction->subtype = $subtype;
			$transaction->datetime = $datetime;
			$transaction->user_id = $user_id;
			$transaction->wallet_id = $this->id;

			if ($transaction->save())
			{
				//recalculate total
				if ($transaction->subtype == 'setup' || $transaction->subtype == 'confirmed')
				{
					$this->total = $this->total + $transaction->amount;
					$this->save();
				}
			}

			return $transaction;
		}

		protected function validation()
		{
			return array(
					array('field'=>'name', 'checker' => checker_rules::MIN_LENGTH(1), 'error_message' => 'Wallet length is too short'),
					array('field'=>'user_id', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'user_id is not set')
			);
		}

		public function save()
		{
			if (!$this->type)
				$this->type = 'default';

			if (!$this->name)
				$this->name = 'Undefined';

			if (!$this->id && !$this->total)
				$this->total = 0;

			return parent::save();
		}
	
		public function delete()
		{
			/// remove transactions
			$this->db->delete('transactions', "wallet_id='".(int)$this->id."' ");		
			$this->db->delete('transactions_reccurences', "wallet_id='".(int)$this->id."' ");			

			return parent::delete();
		}
	
	}



