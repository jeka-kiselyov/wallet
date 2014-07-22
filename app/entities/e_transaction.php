<?php

	class transaction extends entity_base
	{	
		public function makeReccurented($year = 0, $month = 0, $day = 0, $week = 0, $weekday = 0)
		{
			$reccurence = new transactions_reccurence;
			$reccurence->wallet_id = $this->wallet_id;
			$reccurence->year = $year;
			$reccurence->month = $month;
			$reccurence->day = $day;
			$reccurence->week = $week;
			$reccurence->weekday = $weekday;

			$reccurence->transaction_id = $this->id;
			$reccurence->start = $this->datetime;
			$next = $this->time_helper->findNextOccurenceDate($this->datetime, $year, $month, $day, $week, $weekday);
			if ($next)
				$reccurence->next = $next;
			$reccurence->save();
		}


		public function save()
		{
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

			return parent::delete();
		}
	
	
		protected function validation()
		{
			return array(
					array('field'=>'description', 'checker' => checker_rules::MAX_LENGTH(255), 'error_message' => 'Description is too long'),
					array('field'=>'wallet_id', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'wallet_id is not set'),
					array('field'=>'user_id', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'user_id is not set'),
					array('field'=>'datetime', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'datetime is not set'),
					array('field'=>'datetime', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'datetime is not set')
			);
		}

	}



