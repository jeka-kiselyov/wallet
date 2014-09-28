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

		public function addTransaction($amount, $description, $type = false, $subtype = false, $datetime = false)
		{
	 		if (!$this->id)
	 			return false;
			if (!$datetime)
				$datetime = time();

			$user_id = $this->user_id;

			if (!$type)
			{
				if ($amount >= 0)
					$type = 'profit';
				else
					$type = 'expense';
			}

			if (!$subtype)
				$subtype = 'confirmed';

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
					$this->_original_fields['total'] = $this->total;
					$this->save();
				}
			}

			return $transaction;
		}

		/**
		 * Update wallet total, adding 'setup' transaction
		 * @param  [type] $total          current total
		 * @param  [type] $original_total previous total
		 * @return [type]                 [description]
		 */
		private function fixTotalTo($total, $original_total)
		{
			$diff = $total - $original_total;
			$type = 'profit';
			if ($diff < 0)
				$type = 'expense';

			$transaction = new transaction;
			$transaction->amount = $diff;
			$transaction->description = '';
			$transaction->type = $type;
			$transaction->subtype = 'setup';
			$transaction->datetime = time();
			$transaction->user_id = $this->user_id;
			$transaction->wallet_id = $this->id;

			return $transaction->save();
		}


		public function giveAccess($email)
		{
			$email = trim($email);
			if (!$email || !$this->regexpes->is_email($email))
				return false;

			$wallets_access = new wallets_access;
			$wallets_access->wallet_id = $this->id;
			$wallets_access->to_email = $email;
			$wallets_access->original_user_id = $this->user_id;
			$to_user = $this->users->get_by_email($email);
			if ($to_user)
			{
				if ($this->hasAccess($to_user->id))
					return true;
				$wallets_access->to_user_id = $to_user->id;
			}
			else
				$wallets_access->to_user_id = 0;
			$success = $wallets_access->save();

			return $success;
		}

		public function getAccesses()
		{
			return $this->wallets_accesses->find_by_wallet_id($this->id);
		}

		public function hasAccess($user_id)
		{
			if ($this->user_id == $user_id || $this->db->getrow('SELECT * FROM `wallets_accesses` WHERE wallet_id = ? AND to_user_id = ?', array($this->id, (int)$user_id)))
				return true;
			else
				return false;
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

			if ($this->id && $this->_original_fields['total'] != $this->total && ($this->_original_fields['total'] != 0 && $this->total != 0))
			{
				/// need to add setup transaction
				$this->fixTotalTo($this->total, $this->_original_fields['total']);
			}

			return parent::save();
		}
	
		public function delete()
		{
			/// remove transactions
			$c = $this->db->delete('transactions', " wallet_id = ?", $this->id);
			$c = $this->db->delete('reccurences', "wallet_id = ?", $this->id);	

			return parent::delete();
		}
	
	}



