<?php

	class model_wallets extends model_base
	{
		public function find_shared_with_user_id($user_id)
		{
			return new collection($this->entity_class_name, $this->db->returnPreparedQuery("SELECT `wallets`.* FROM `wallets` JOIN
				`wallets_accesses` ON `wallets_accesses`.wallet_id = `wallets`.id
				WHERE `wallets_accesses`.to_user_id = ?", array($user_id) ) );
		}
	
	
	}



