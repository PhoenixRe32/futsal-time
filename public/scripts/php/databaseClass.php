<?php
class MyDBManager
{
	private $database	= 'futsalti_futsal_thoi';
	private $host		= 'localhost'; 
	private $username	= 'futsalti_guest';
	private $password	= '1qazXSW@3edcVFR$';
	private $options	= array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
	
	private $errorfile	= '/../../../error_logs/error.log';
	private $timeZone	= "Europe/Nicosia";
	private $mailSupport= 'support@futsal-time.com';
	
	private $connection	= null;
	private $report		= array();
	
	const RESULT_ASSOC	= PDO::FETCH_ASSOC;
	const RESULT_NUM	= PDO::FETCH_NUM;
	const RESULT_BOTH	= PDO::FETCH_BOTH;

	function __construct()
	{
		$dsn = "mysql:dbname={$this->database};host={$this->host}";
		try 
		{
			$this->connection = new PDO($dsn, $this->username, $this->password, $this->options);
		}
		catch (Exception $e) 
		{
			$this->errorManagement($e, 'Connection', '');
		}
		$this->runQuery("SET CHARACTER SET utf8");
		$this->runQuery("SET COLLATION_CONNECTION=utf8_general_ci");
	}
	
	function runQuery($statement)
	{
		$this->report = array('error'=>false);
		try 
		{
			$query = $this->connection->prepare($statement);
			if (!$query->execute()) throw new Exception('Query execution returned false');
		}
		catch (Exception $e) 
		{
			$this->errorManagement($e, 'Query-Run', $query->queryString."\n".$statement);
		}
		return $query;
	}
	
	function fetchRow($statement, $mode=self::RESULT_ASSOC)
	{
		return $this->runQuery($statement)->fetch($mode);
	}
	
	function fetchCell($statement)
	{
		return $this->runQuery($statement)->fetchColumn();
	}
	
	function fetchSet($statement, $mode=self::RESULT_ASSOC)
	{
		return $this->runQuery($statement)->fetchAll($mode);
	}
	
	function errorManagement($error, $topic, $notes)
	{
		date_default_timezone_set($this->timeZone);
		$now = new DateTime();
		$message = "================================================================================\n";
		$message .= $now->format('[Y-m-d H:i:s]');
		$message .= "\t$topic\n\n";
		$message .= "$error\n";
		$message .= "\n$notes\n";
		$message .= "\n";
		error_log($message,3, __DIR__.$this->errorfile);
		@mail($this->mailSupport, $topic, $message);
		$this->report['error'] = true;
	}
	
	function closeConnection()
	{
		$this->connection = null;
	}
	
	function __destruct()
	{
		$this->closeConnection();
		
		unset($this->database);
		unset($this->host);
		unset($this->username);
		unset($this->password);
		unset($this->options);
		unset($this->errorfile);
		unset($this->timeZone);
		unset($this->mailSupport);
		unset($this->connection);
		unset($this->report);
	}

	function getLastInsertId()
	{
		return $this->connection->lastInsertId();
	}
	
	function getReport()
	{
		return $this->report;
	}
	
	function getErrorStatus()
	{
		return $this->report['error'];
	}
	
	function getTimeZone()
	{
		return $this->timeZone;
	}
}
?>