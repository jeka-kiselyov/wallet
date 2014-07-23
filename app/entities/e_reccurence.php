<?php

	class reccurence extends entity_base
	{	
		public function moveToNext()
		{
			$original_transaction = false;
			if ($this->transaction_id)
				$original_transaction = $this->transactions->get_by_id($this->transaction_id);

			if (!$original_transaction)
				throw new Exception('Can not find original transaction');

			if (!$this->wallet || $this->wallet_id != $original_transaction->wallet_id)
				throw new Exception('Can not find wallet');

			$transaction = $this->wallet->addTransaction($original_transaction->amount, $original_transaction->description, $original_transaction->type, 'confirmed', $this->next, $original_transaction->user_id);

			if (!$transaction)
				throw new Exception("Can not save new transaction");

			$transaction->save();

			if (!$transaction->id)
				throw new Exception('Can not save new transaction');

			$this->start = $this->next;
			$this->next = $this->time_helper->findNextOccurenceDate($this->next, $this->year, $this->month, $this->day, $this->week, $this->weekday);
			$this->transaction_id = $transaction->id;

			$this->save();

			return $transaction;
		}

	
	
	}



