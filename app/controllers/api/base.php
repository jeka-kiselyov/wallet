<?php

 abstract class api_controller  extends controller_base
 {
 	private $payload = null;

 	public function __construct($registry)
 	{
 		parent::__construct($registry);

 		$this->user = $this->sessions->get_user();

		$this->error_prefix = '0';
		$this->data = null;
		$this->status = 'success';


 		$this->payload = array();
 		$data = @file_get_contents("php://input");
 		if ($data)
 			$this->payload = @json_decode($data);
 	}


 	protected function payload($param = false, $default = false)
 	{
 		if ($param === false)
 			return $this->payload;

 		if (isset($this->payload->$param))
 			return $this->payload->$param;
 		else
 			return $default;
 	}

	public function index()
	{
		$id = (int)$this->gp(0,0);
		$sub = $this->gp(1,'');
		$sub_id = (int)$this->gp(2,0);

		if ($id)
		{
			if ($sub)
			{
				if ($sub_id)
				{
					if ($this->request_method() == 'GET')
						$this->{'crud_'.$sub.'_read'}($id, $sub_id);
					elseif ($this->request_method() == 'PUT')
						$this->{'crud_'.$sub.'_update'}($id, $sub_id);
					elseif ($this->request_method() == 'DELETE')
						$this->{'crud_'.$sub.'_delete'}($id, $sub_id);
					else
						$this->error(1, 'Use an URL without id to create new entity');
				} else {
					if ($this->request_method() == 'POST')
					    $this->{'crud_'.$sub.'_create'}($id);
					elseif ($this->request_method() == 'GET')
						$this->{'crud_'.$sub.'_list'}($id);
					else
						$this->error(2, 'There is no entity id passed');	
				}
			} else {			
				if ($this->request_method() == 'GET')
					$this->crud_read($id);
				elseif ($this->request_method() == 'PUT')
					$this->crud_update($id);
				elseif ($this->request_method() == 'DELETE')
					$this->crud_delete($id);
				else
					$this->error(1, 'Use an URL without id to create new entity');
			}
		} else
		{
			if ($this->request_method() == 'POST')
			    $this->crud_create();
			elseif ($this->request_method() == 'GET')
				$this->crud_list();
			else
				$this->error(2, 'There is no entity id passed');				
		}
	}

 	protected function set_data($data_array)
 	{
 		$this->data = $data_array;
 	}

 	protected function data($data_array)
 	{
 		$this->set_data($data_array);
 		exit();
 	}

 	protected function error($code, $message)
 	{
 		$this->status = 'error';
 		$this->data = array('code'=>$this->error_prefix.$code, 'message'=>$message);
 		exit();
 	}

 	protected function not_found()
 	{
 		$this->status = 'error';
 		$this->data = array('code'=>'404', 'message'=>'Nothing is found');
 		exit();
 	}

 	protected function has_no_rights()
 	{
 		$this->status = 'error';
 		$this->data = array('code'=>'666', 'message'=>'You do not have rights to perform this query');
 		exit();
 	}

 	protected function require_signed_in()
 	{
 		if (!$this->user || !$this->user->id)
 			$this->has_no_rights();
 	}

 	protected function param($param_name)
 	{
 		if (isset($_POST[$param_name]))
 			return $_POST[$param_name];
 		elseif (isset($_GET[$param_name]))
			return $_GET[$param_name];
		else
 			return null;
 	}

 	protected function request_method()
 	{
 		if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], array('GET','PUT','POST','DELETE')))
 			return $_SERVER['REQUEST_METHOD'];
 		else
 			return 'GET';
 	}

 	public function __destruct()
 	{
		header("Content-type: application/json; charset=utf-8");
		if ($this->status == 'success')
		{
			echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		} else {
			if (isset($this->data['code']) && $this->data['code'] == 404)
			{
				header('HTTP/1.0 404 Not Found', true, 404);
				echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			} else {
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			}
		}

		$this->rendered = true;
		exit;
 	}
 }