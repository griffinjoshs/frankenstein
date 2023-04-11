<?php
class User extends DBObject
{
	public $error;
	public $searchFields = array('lastname', 'firstname', 'email', 'phone', 'mobile');
	function __construct($id = "")
	{
		parent::__construct('users', 'id', array('status', 'username', 'permissionId', 'password', 'email', 'firstname', 'lastname', 'phone', 'privateKey', 'rowStatus'), $id);
	}

	function validate($pwMismatch=false)
	{
		global $db;
		$proceed = true;


		// $this->email = trim($this->email);

		// if ($pwMismatch)
		// {
		//     $this->error .= 'The passwords you entered do not match.';
		//     $proceed = false;
		// }
		// if ($this->email == '')
		// {
		// 	$this->error .= 'You must enter an email address.';
		// 	$proceed = false;
		// }
		// if ($this->firstname == '')
		// {
		// 	$this->error .= 'You must enter a first name.';
		// 	$proceed = false;
		// }
		// if ($this->phone == '' && $this->mobile == '')
		// {
		// //	$this->error .= 'You must enter a valid phone number.<br>';
		// //	$proceed = false;
		// }
		// if ($this->password == '')
		// {
		// 	if ($this->id > 0)
		// 	{
		// 		$this->password = $db->getValue("SELECT password FROM users WHERE user_id={$this->id}");
		// 	}
		// 	else
		// 	{
		// 		$this->error .= 'A password is required to create an account.<br>';
		// 		$proceed = false;
		// 	}

		// }
		// if ($this->usernameExists())
		// {
		// 	$this->error .= 'The email address you entered is already in use.<br>';
		// 	$proceed = false;
		// }
		return $proceed;
	}

	// private function setPermissionId()
	// {
	// 	global $db;
	// 	$this->permissionId = $db->getValue("SELECT permissionId FROM users WHERE user_id={$this->id}");
	// }
	// public function updatePermissions($permId)
	// {
	// 	global $db;
	// 	$permId = ($permId==''?0:$permId);
	// 	$db->query("UPDATE users SET permissionId = {$permId} WHERE user_id={$this->id}");
	// }

	public function getUserById($userId) {
		global $db;
		$sql = "SELECT u.user_id as userId, u.firstname, u.lastname, u.email, u.phone, u.mobile FROM users u

		WHERE u.status = 1 AND u.rowStatus = 1 AND u.user_id = " . (int)$userId;
		$rows = $db->getRows($sql);
	    return $rows;
	}


	// public function updatetimezone($userId,$timezone)
	// {
	// 	global $db;
	// 	$sql =  "UPDATE users SET timezone = ". $timezone."  where  user_id= ".$userId;
	// 	$db->query($sql);
	// 	return true;
	// }


	static function login($username, $password)
	{
		global $db, $_SESSION;
		// sanitize username
		$username = addslashes(strtoupper(trim($username)));
		// hash password
		$hashPass = md5($password);
		// showArray($username);
		// showArray($hashPass);
		if (trim($username) != '' && trim($password) != '')
		{
			$sql = "SELECT u.id, u.userId, u.email, u.username, u.password, u.firstname, u.lastname, u.permissionId, u.timezone as userTimezone, tz.tz, tz.tzOffset FROM users u
			LEFT JOIN timezone tz ON tz.id = u.timezone
			WHERE u.status = 1 AND UPPER(u.username) = '" . $username . "' AND UPPER(u.password) = '" . $hashPass . "'";
			// showArray($sql);
			$user = $db->getRows($sql);
			// showArray($user);
		} 
		else
		{
			$error = 'Your ';
			if(trim($username) == ' && $password == ')
			{ $error .= 'username and password are '; }
			elseif($username == '')
			{ $error .= 'username is '; }
			elseif($password == '')
			{ $error .= 'password is '; }
			$error .= 'missing. Please try again.'; 
			return $error;
			// return false;
		}
		if (count($user) == 1)
		{
			// showArray($user);
			if($user[0]['userTimezone'] == '') {
				$user[0]['userTimezone'] = ($_POST['timezone'] != '' ? $_POST['timezone'] : "Not Available");
			}
			// showArray($user, "success user: ");
			$_SESSION['user_id'] = $user[0]['userId'];
			$_SESSION['user_firstName'] = $user[0]['firstname'];
			$_SESSION['user_lastName'] = $user[0]['lastname'];
			$_SESSION['user_email'] = $user[0]['email'];
			// $_SESSION['timezone'] = $user[0]['timezone'];
			$_SESSION['userTimezone'] = $user[0]['userTimezone'];
			$_SESSION['tzOffset'] = $user[0]['tzOffset'];
				
			if ($user[0]['permissionId']==1)
			{
				$_SESSION['admin_id'] = $user[0]['userId'];
				$_SESSION['is_super_admin'] = true;
			}

			$ip = $_SERVER['REMOTE_ADDR'];
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

			// TODO: this is causing an issue when i try and save. this mighjt
			// be an over all save issue. need to investigate
			// $td = explode(',',$details->loc);
			// $details->latitude = $td[0];
			// $details->longitude = $td[1];
			// $details->email = $_POST['username'];
			// $details->datetime = date('Y-m-d H:i:s');
			// $details->userId = $_SESSION['user_id'];
			// $details->timezone = $_POST['timezone'];

			// $login = new Login();
			// $login->postvars((array)$details);
			// $login->save();
			return true;
		
		}
		else
		{
			$error = "This username and password combination does not exist. Please try again.";
			return $error; 
			// return false;
		}
		exit;
	}

	static function logout()
	{
		session_destroy();
		redirect('login.php');
	}

	function usernameExists()
	{
		global $db;
		$email = strtoupper($this->email);
		$sql = "SELECT * FROM users WHERE UPPER(email) = '{$email}'";
		if ((int)$this->id > 0)
			$sql .= " AND user_id <> ".(int)$this->id;
		$user = $db->getRows($sql);
		if (count($user) > 0)
			return true;
		else
			return false;
	}

	function getRfpEmailList($pid=0)
	{
		global $db;
		
		/*
		$sql = "SELECT email FROM users WHERE status = 1 AND rfpResponse = 1";
		$emails = $db->getRows($sql);
		foreach ($emails as $e)
			$ret[$e['email']] = $e['email'];
        */
		if ((int)$pid > 0)
		{
			$sql = "
                SELECT u.email 
                FROM proposal p 
                LEFT JOIN users u ON u.user_id = p.userId 
                WHERE u.status = 1 AND u.rowStatus = 1 AND p.id = " . (int)$pid;
			$emails = $db->getRows($sql);
			
			//The selected user for the campaign is no longer available, so let's get the remaining users in the team assigned to the campaign
			if (count($emails) == 0)
			{
			    $sql = "
                SELECT u.email
                FROM proposal p
                LEFT JOIN team_member tm ON tm.teamId = p.teamId
                LEFT JOIN users u ON u.user_id = tm.userId
                WHERE u.status = 1 AND u.rowStatus = 1 AND p.id = " . (int)$pid;
			    
			    $emails = $db->getRows($sql);
			}
			foreach ($emails as $e)
				$ret[$e['email']] = $e['email'];

		}

		if (is_array($ret))
			sort($ret);

		return $ret;
	}
	function getTheTeam()
	{
		global $db;
		$sql = "SELECT * FROM users WHERE status = 1 AND permissionId IN (1,2)";
		$emails = $db->getRows($sql);
		foreach ($emails as $e)
			$ret[] = $e;

		return $ret;
	}
	function resetPassword($key = '')
	{
		global $db;
		$res = [];
		$sql = "SELECT * FROM users WHERE  pwResetLink = '" . $key."'";
		
		$res = $db->getRows($sql);
		
		if(!empty($res))
		{
			foreach ($res as $v)
			{
			    
				$currentTime = $v['pwResetExpiration'];
				$end_date = date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($currentTime)));
				$links = "SELECT * FROM users WHERE  pwResetLink = '" . $key."' AND pwResetExpiration BETWEEN '".$currentTime."' AND '".$end_date."'";
	 			
				$rows = $db->getRows($links);
				
				return $rows[0];	
			}
		}
		else{
			return $res;	
		}
	}

}