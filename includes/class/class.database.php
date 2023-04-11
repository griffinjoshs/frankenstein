<?PHP
	class Database
	{
		var $onError   = ""; // die, email, or nothing
		var $errorTo   = "blancosays@gmail.com";
		var $errorFrom = "blancosays@gmail.com";
		var $errorPage = "db-error.php";

		var $db;
		var $dbname;
		var $host;
		var $password;
		var $queries;
		var $result;
		var $user;
		var $redirect = false;

		function __construct($host, $user, $password, $dbname = null)
		{
			$this->host     = $host;
			$this->user     = $user;
			$this->password = $password;
			$this->dbname   = $dbname;			
			$this->queries  = array();
		}
		
		function connect()
		{
			$this->db = mysqli_connect($this->host, $this->user, $this->password, $this->dbname) or $this->notify();
		}

		function query($sql)
		{
			$this->queries[] = $sql;
			$this->result = mysqli_query($this->db, $sql);
			// $this->result = mysqli_query($this->db, $sql) or $this->notify();
			if (count($this->db->error_list) > 0)
			{
			   // showArray($this, false);
			    $errNo = $this->db->error_list[0]['errno'];
			    $err = $this->db->error_list[0]['error'];
			    $query = $this->queries[ count($this->queries)-1];
			    $page = $_SERVER['REQUEST_URI'];
			    if (SYSTEM_ADMIN)
			    {
                     echo systemMessage('error', 'Database Error', '<b>Error #</b>: ' . $errNo . '<br><b>Error</b>: ' . $err . '<br><b>Page</b>: ' . $page . '<br><b>Query</b>: ' . $query, "", false);
			    }
                // TODO: create error log
			    // $e = new ErrorLog();
			    // $e->userId = USERID;
			    // $e->contactId = (isset($_SESSION['contactId']) ? $_SESSION['contactId'] : 0);
			    // $e->errorNumber = $errNo;
			    // $e->errorText = $err;
			    // $e->url = $page;
			    // $e->badQuery = $query;
			    // $e->datetime = date('Y-m-d H:i:s');
			    // $e->save();
			}
			return $this->result;
		}
		

		// You can pass in nothing, a string, or a db result
		function getValue($arg = null)
		{
			if(is_null($arg) && $this->isValid())
				return mysql_result($this->result, 0, 0);
			elseif(is_resource($arg) && $this->isValid($arg))
				return mysql_result($arg, 0, 0);
			elseif(is_string($arg))
			{
				$this->query($arg);
				if($this->isValid())
				{
				    while($row = mysqli_fetch_assoc($this->result)) {
				        sort($row);
				        return $row[0];
				    }
				    //return mysql_result($this->result, 0, 0);
				}
			}
			return false;
		}

		function numRows($arg = null)
		{
			if(is_null($arg) && $this->isValid())
				return mysqli_num_rows($this->result);
			elseif(is_resource($arg) && $this->isValid($arg))
				return mysqli_num_rows($arg);
			elseif(is_string($arg))
			{
				$this->query($arg);
				if($this->isValid())
					return mysqli_num_rows($this->result);
			}
			return false;
		}

		// You can pass in nothing, a string, or a db result
		function getRow($arg = null)
		{
			if(is_null($arg) && $this->isValid())
				return mysqli_fetch_array($this->result, MYSQL_ASSOC);
			elseif(is_resource($arg) && $this->isValid($arg))
				return mysqli_fetch_array($arg, MYSQL_ASSOC);
			elseif(is_string($arg))
			{
				$this->query($arg);
				if($this->isValid())
					return mysqli_fetch_array($this->result, MYSQL_ASSOC);
			}
			return false;
		}

		function getRows($arg = null, $killit=false)
		{
		    //echo $arg.'<br><br>';
			if(is_null($arg) && $this->isValid())
				$result = $this->result;
			elseif(is_resource($arg) && $this->isValid($arg))
				$result = $arg;
			elseif(is_string($arg))
			{
				$result = $this->query($arg);
			
			}
			else
				return array();
				
			$rows = array();
		//	mysql_data_seek($result, 0);
			
			
			//showArray($result);
			//while($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			$i=0;
			//echo $arg . '<br>';
			if (is_object($result))
			{
    		    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    		    {
    		        IF (USERID == 1 && $i==200 && $killit){
    		            break;}
    				$rows[] = $row;
    				$i++;
    			}
			}
			return $rows;
		}

		// You can pass in nothing, a string, or a db result
		function getObject($arg = null)
		{
			if(is_null($arg) && $this->isValid())
				return mysql_fetch_object($this->result);
			elseif(is_resource($arg) && $this->isValid($arg))
				return mysql_fetch_object($arg);
			elseif(is_string($arg))
			{
				$this->query($arg);
				if($this->isValid())
					return mysql_fetch_object($this->result);
			}
			return false;
		}

		function getObjects($arg = null)
		{
			if(is_null($arg) && $this->isValid())
				$result = $this->result;
			elseif(is_resource($arg) && $this->isValid($arg))
				$result = $arg;
			elseif(is_string($arg))
			{
				$this->query($arg);
				if($this->isValid())
					$result = $this->result;
				else
					return array();
			}
			else
				return array();

			$objects = array();
			mysql_data_seek($result, 0);
			while($object = mysql_fetch_object($result))
				$objects[] = $object;
			return $objects;
		}

		function isValid($result = null)
		{
			if(is_null($result)) $result = $this->result;
			return (mysqli_num_rows($result) > 0);
		}

		function quote($var) { return "'" . mysqli_real_escape_string($this->db, $var) . "'"; }
		function quoteParam($var) { return $this->quote($this->fix_slashes($_REQUEST[$var])); }
		function numQueries() { return count($this->queries); }
		function lastQuery() { return $this->queries[count($this->queries) - 1]; }

		function fix_slashes($arr = "")
		{
			if(empty($arr)) return;
			if(!get_magic_quotes_gpc()) return $arr;
			return is_array($arr) ? array_map('fix_slashes', $arr) : stripslashes($arr);
		}

        // TODO: see if you want / need this
		// function notify()
		// {
		// 	global $auth;
			
		// 	$err_msg = mysqli_error($this->db);
		// 	error_log($err_msg);

		// 	switch($this->onError)
		// 	{
		// 		case "die":
		// 			echo "<p style='border:5px solid red;background-color:#fff;padding:5px;'><strong>Database Error:</strong><br/>$err_msg</p>";
		// 			echo "<p style='border:5px solid red;background-color:#fff;padding:5px;'><strong>Last Query:</strong><br/>" . $this->lastQuery() . "</p>";
		// 			echo "<pre>";
		// 			debug_print_backtrace();
		// 			echo "</pre>";
		// 			die();
		// 			break;
				
		// 		case "email":
		// 			$msg  = $_SERVER['PHP_SELF'] . " @ " . date("Y-m-d H:ia") . "\n";
		// 			$msg .= $err_msg . "\n\n";
		// 			$msg .= implode("\n", $this->queries) . "\n\n";
		// 			$msg .= "CURRENT USER\n============\n"     . var_export($auth, true)  . "\n" . $_SERVER['REMOTE_ADDR'] . "\n\n";
		// 			$msg .= "POST VARIABLES\n==============\n" . var_export($_POST, true) . "\n\n";
		// 			$msg .= "GET VARIABLES\n=============\n"   . var_export($_GET, true)  . "\n\n";
		// 			mail($this->errorTo, $_SERVER['PHP_SELF'], $msg, "From: {$this->errorFrom}");
		// 			break;
		// 	}

		// 	if($this->redirect === true)
		// 	{
		// 		header("Location: {$this->errorPage}");
		// 		exit();
		// 	}			
		// }
	}
?>