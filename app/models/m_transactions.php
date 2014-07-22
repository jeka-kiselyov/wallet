<?php


	class model_transactions extends model_base
	{
		public function getForWalletId($wallet_id, $from = false, $to = false)
		{
			if ($to == false)
				$to = time();
			if ($from = false)
				$from = time() - date('t')*24*60*60;

			$nto = $this->time_helper->ceilDate($to);
			$nfrom = $this->time_helper->floorDate($from);
			$wallet_id = (int)$wallet_id;

			$query = "SELECT * FROM transactions WHERE wallet_id = '".$wallet_id."' AND `datetime` > '".$nfrom."' AND `datetime` < '".$nto."' ";

			$transactions = new collection('transaction', $query);
			$scheduled = $this->getScheduledForWalletId($wallet_id, $from, $to);

			$ret = array();
			foreach ($scheduled as $t) 
				$ret[] = $t;
			foreach ($transactions as $t) 
				$ret[] = $t;

			usort($ret, function ($a, $b) { if ($a->datetime > $b->datetime) return 1; if ($a->datetime < $b->datetime) return -1; if ($a->id > $b->id) return 1; else return -1; });

			return $ret;
		}

		public function getScheduledForWalletId($wallet_id, $from = false, $to = false)
		{
			if ($to == false)
				$to = time();
			if ($from = false)
				$from = time() - date('t')*24*60*60;

			$nto = $this->time_helper->ceilDate($to);
			$nfrom = $this->time_helper->floorDate($from);
			$wallet_id = (int)$wallet_id;

			$ret = array();
			for ($d = $nfrom; $d < $nto; $d+=24*60*60)
			{
				//echo $nfrom."   ".$nto."\n";
				$week = date("W", $d) - date("W", strtotime( date("Y-m-01", $d) ) ) + 1;

				$query = "SELECT transactions.* FROM transactions RIGHT JOIN transactions_reccurences ON transactions_reccurences.transaction_id = transactions.id WHERE 
				transactions.wallet_id = '".$wallet_id."' AND 
				transactions_reccurences.start <= ".$d." AND 
				(transactions_reccurences.weekday = '0' OR transactions_reccurences.weekday = '".date("N",$d)."') AND 
				(transactions_reccurences.week = '0' OR transactions_reccurences.week = '".$week."') AND 
				(transactions_reccurences.day = '0' OR transactions_reccurences.day = '".date("j",$d)."') AND 
				(transactions_reccurences.month = '0' OR transactions_reccurences.month = '".date("n",$d)."') AND 
				(transactions_reccurences.year = '0' OR transactions_reccurences.year = '".date("Y",$d)."') ";
				//echo $query."\n\n";
				$arrays = $this->db->getall($query);
				if (is_array($arrays))
					foreach ($arrays as $data) 
					{
						$transaction = new transaction;
						$transaction->type = $data['type'];
						$transaction->subtype = 'scheduled';
						$transaction->user_id = $data['user_id'];
						$transaction->wallet_id = $data['wallet_id'];
						$transaction->datetime = $d;
						$transaction->amount = $data['amount'];
						$transaction->abs_amount = $data['abs_amount'];
						$transaction->description = $data['description'];
						$transaction->original_transaction_id = $data['id'];

						$ret[] = $transaction;
					}
			}
			return $ret;

		}

	
	}



