<?php
	include_once(rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/lib/nusoap.php");
	

	/** 
	* Configuration Section:
	*  Set the username and password that you used during the registration
	*/
	$cfg['wsdl_file']=rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/websms.wsdl";
	$cfg['username']="vhf_andrew@yahoo.gr";
	$cfg['password']='1q@W3e$R5t';
	$cfg['session_path']=rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/websms.com.cy.ses";
	$cfg['session_time_to_live']=60;

	/**
	* Class Deffinition
	*l1[w108D7wGUK4Wbm8hN
	*/

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
				return $ret["session_id"];
			}else
				die("Invalid Username and or password");
		}
		
		function getSession()
		{
			$session=$this->getFileSession();
			

			if($session==null)
			{
				$session=$this->authenticate();
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
	
	parse_str($argv[1]);
	// var_dump($recipients);
	// exit();
	$modSMS = str_replace('_','
',$sms);

	$ws = new WebsmsClient($cfg);
	$response = $ws->submitSM($sender, $recipients, $modSMS, "GSM");
	$remainingCredits=$ws->getCredits();
	
	date_default_timezone_set("Europe/Nicosia");
	$now = new DateTime();
	$errorString = "================================================================================\n";
	$errorString .= $now->format('[Y-m-d H:i:s]');
	$errorString .= "\tSMS Send Report\n\n";
	$errorString .= "Recipients:\n-----------\n";
	$errorString .= print_r($recipients, true);
	$errorString .= "Message:\n--------\n$modSMS\n";
	$errorString .= "\nReport:\n-------\n";
	$errorString .= "Remaining Credits: $remainingCredits\n";
	$errorString .= print_r($response, true);
	$errorString .= "\n";
	error_log($errorString,3,rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/../error_logs/sms_stats.log");
?>