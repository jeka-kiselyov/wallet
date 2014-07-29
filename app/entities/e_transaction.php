<?php

	class transaction extends entity_base
	{	
		public function makeReccurented($year = 0, $month = 0, $day = 0, $week = 0, $weekday = 0)
		{
			$reccurence = new reccurence;
			$reccurence->transaction_id = $this->id;
			$reccurence->wallet_id = $this->wallet_id;

			$reccurence->year = $year;
			$reccurence->month = $month;
			$reccurence->day = $day;
			$reccurence->week = $week;
			$reccurence->weekday = $weekday;

			$reccurence->start = $this->datetime;

			$next = $this->time_helper->findNextOccurenceDate($this->datetime, $year, $month, $day, $week, $weekday);

			if ($next)
				$reccurence->next = $next;
			$reccurence->save();
		}

		public function moveToNext()
		{
			if ($this->subtype != 'scheduled')
				throw new Exception('Transaction should have subtype sheduled to activate it');

			$reccurence = $this->reccurences->get_by_transaction_id($this->original_transaction_id);

			return $reccurence->moveToNext();
		}

		public function save()
		{
			if ($this->id)
			{
				if ($this->_original_fields['amount'] != $this->amount)
				{
					
				}
			}

			if (isset($this->_changed_fields['amount']))
				$this->abs_amount = abs($this->_changed_fields['amount']);

			if (!$this->type)
			{
				if ($this->amount >= 0)
					$this->type = 'profit';
				else
					$this->type = 'expense';
			}

			if ($this->type && !$this->subtype)
			{
				/// have type but don't have subtype
				$this->subtype = 'confirmed';
			}

			if ($this->subtype == 'scheduled')
			{
				$e = new entityvalidation_exception('Scheduled transactions could not be saved to database');
				$e->set_error_messages($this->_validation_errors);
				throw $e;
			}
			if ($this->type == 'profit' && $this->amount < 0)
			{
				$e = new entityvalidation_exception('Amount should be positive for profit transactions');
				$e->set_error_messages($this->_validation_errors);
				throw $e;
			}
			elseif ($this->type == 'expense' && $this->amount > 0)
			{
				$e = new entityvalidation_exception('Amount should be negative for expense transactions');
				$e->set_error_messages($this->_validation_errors);
				throw $e;
			}

			return parent::save();
		}
	
		public function delete()
		{
			if ($this->id)
			{
				//// need to update wallet total
				if ($this->type == 'profit' || $this->type == 'expense')
				{
					if ($this->subtype == 'confirmed' || $this->subtype == 'setup')
					{
						/// 1st need to check if there's no 'setup' transaction after this transaction
						$setup_transaction_id = $this->db->getone("SELECT id FROM transactions 
							WHERE wallet_id = '".(int)$this->wallet_id."' AND subtype = 'setup' AND datetime >= '".(int)$this->datetime."' AND id > '".(int)$this->id."' ORDER BY id ASC LIMIT 1;");

						/// if there's no - just update wallet total 
						if (!$setup_transaction_id)
						{
							$diff = -$this->amount;
							$wallet = $this->wallets->get_by_id($this->wallet_id);
							if ($wallet)
							{
								/// can't dirrectly change as this will coall wallet->save(); logic. Update record with direct query.
								$this->db->update('wallets', array('total'=>$wallet->total + $diff), 'id = ?', array($wallet->id));
							}
						} else {
							/// if there's - need to update setup transaction, not wallet total
							$diff = $this->amount;
							$setup_transaction = $this->transactions->get_by_id($setup_transaction_id);
							if ($setup_transaction)
							{
								$setup_transaction->amount = $setup_transaction->amount + $diff;
								$setup_transaction->save();
							}
						}
					}
				}
		
			}
			/// 
			/// 	
			$this->db->delete('reccurences', "transaction_id='".(int)$this->id."' ");		

			return parent::delete();
		}
	
	
		protected function validation()
		{
			return array(
					array('field'=>'description', 'checker' => checker_rules::MAX_LENGTH(255), 'error_message' => 'Description is too long'),
					array('field'=>'wallet_id', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'wallet_id is not set'),
					array('field'=>'user_id', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'user_id is not set'),
					array('field'=>'datetime', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'datetime is not set')
			);
		}

	}



