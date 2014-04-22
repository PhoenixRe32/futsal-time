<?php
	include_once(dirname(__FILE__)."/lib/nusoap.php");
	
	class WebsmsClient
	{
		private $soap_client;
		private $username;
		private $password;
		private $session_path;
		private $time_to_live;

		function __construct($cfg)
		{
			$this->soap_client=new nusoap_client($cfg['wsdl_file'],"WSDL");
			$this->session_path=$cfg['session_path'];
			$this->username=$cfg['username'];
			$this->password=$cfg['password'];
			$this->time_to_live=$cfg['session_time_to_live'];
			
		}

		function authenticate()
		{
			$obj=new stdClass();
			$obj->username=$this->username;
			$obj->password=$this->password;
			
			$ret=$this->soap_client->call("Authenticate",array("parameters"=>$obj));
			//var_dump($this->soap_client);
			if($ret['success']==1)
			{
				$session=$ret["session_id"];
				$this->setFileSession($session);
				return $ret;
			}else
			{
				return $ret;
			}
		}
		
		function getSession()
		{
			$session=$this->getFileSession();
			

			if($session==null)
			{
				$ret = $this->authenticate();
				$session=$this->$ret["session_id"];
				$this->setFileSession($session);
			}

			return $session;
		}

		function getFileSession()
		{

			if(file_exists($this->session_path))
			{
				$tm=filemtime($this->session_path);

				if(time()-$tm<$this->time_to_live)
				{
					$session=file_get_contents($this->session_path);
				}else
				{
					$session=null;
				}
				return $session;
			}

			return null;
		}

		function setFileSession($session)
		{
			file_put_contents($this->session_path,$session);
		}
		
		function touchFile()
		{
			touch($this->session_path);
		}
		
		function getCredits()
		{
			$session=$this->getSession();
			$obj=new stdClass();
			$obj->session_id=$session;
			//new soapval("session_id2",false,"asd")
			//array("parameters"=>);
			$res=$this->soap_client->call("getCredits",array("parameters"=>$session));
			//var_dump($this->soap_client);
			$this->touchFile();
			return $res;
		}

		function submitSM($from,$to,$message,$encoding="GSM")
		{
			$obj=new stdClass();
			$obj->session_id=$this->getSession();
			$obj->from=$from;
			$obj->message=$message;
			//$obj->message="A[]{}";
			$obj->data_coding=$encoding;
			if(is_array($to))
				$obj->to=$to;
			else
				$obj->to=array($to);
			
			
			$ret=$this->soap_client->call("sendSM",array("parameters"=>$obj));	
			return $ret;
		}

		function getBatch($batchId)
		{
			$obj=new stdClass();
			$obj->sessionId=$this->getSession();
			$obj->batchId=$batchId;
			

			try
			{
				$ret=$this->soap_client->call("getBatchStatus",array("parameters"=>$obj));
				return $ret;
			}catch (SoapFault $soapFault) {
				var_dump($c);
				echo "Request :<br>", $this->soap_client->__getLastRequest(), "<br>";
				echo "Response :<br>", $this->soap_client->__getLastResponse(), "<br>";
			}
		}
	}
?>