<?
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


		$this->email = trim($this->email);

		if ($pwMismatch)
		{
		    $this->error .= 'The passwords you entered do not match.<br>';
		    $proceed = false;
		}
		if ($this->email == '')
		{
			$this->error .= 'You must enter an email address.<br>';
			$proceed = false;
		}
		if ($this->firstname == '')
		{
			$this->error .= 'You must enter a first name.<br>';
			$proceed = false;
		}
		if ($this->phone == '' && $this->mobile == '')
		{
		//	$this->error .= 'You must enter a valid phone number.<br>';
		//	$proceed = false;
		}
		if ($this->password == '')
		{
			if ($this->id > 0)
			{
				$this->password = $db->getValue("SELECT password FROM users WHERE user_id={$this->id}");
			}
			else
			{
				$this->error .= 'A password is required to create an account.<br>';
				$proceed = false;
			}

		}
		if ($this->usernameExists())
		{
			$this->error .= 'The email address you entered is already in use.<br>';
			$proceed = false;
		}
		return $proceed;
	}

	private function setPermissionId()
	{
		global $db;
		$this->permissionId = $db->getValue("SELECT permissionId FROM users WHERE user_id={$this->id}");
	}
	public function updatePermissions($permId)
	{
		global $db;
		$permId = ($permId==''?0:$permId);
		$db->query("UPDATE users SET permissionId = {$permId} WHERE user_id={$this->id}");
	}

	static public function getUpperManagement($agencyId)
	{
	    global $db;

	    $sql = "SELECT user_id as userId, firstname, lastname, email, phone, mobile FROM users WHERE status = 1 AND permissionId = 6 AND plantId = " . (int)$agencyId;

	    $rows = $db->getRows($sql);
	    return $rows;
	}

	public function getUserById($userId) {
		global $db;
		$sql = "SELECT user_id as userId, firstname, lastname, email, phone, mobile FROM users WHERE status = 1 AND rowStatus = 1 AND user_id = " . (int)$userId;
		$rows = $db->getRows($sql);
	    return $rows;
	}

	public function setExpiration($date='', $days='')
	{
		global $db;
		if ($date == '' && $days == '')
			$date = date('Y-m-d', mktime(0,0,0,date('m'),date('d')+EXPIRATION_TERM,date('Y')));
		elseif ($date == '' && $days != '')
			$date = date('Y-m-d', mktime(0,0,0,date('m'),date('d')+$days,date('Y')));

		if ($this->id != '' && $date != '')
		{
			$db->query("UPDATE users SET expirationDate = '{$date}' WHERE user_id = {$this->id}");
		}

	}
	public function updatetimezone($userId,$timezone)
	{
		global $db;
		$sql =  "UPDATE users SET timezone = ". $timezone."  where  user_id= ".$userId;
		$db->query($sql);
		return true;
	}
	static function setNavigationDirection($userId) {
		global $db;
		
		if ($userId > 0)
		{
		    if (isset($_SESSION['menuDirection']) && $_SESSION['menuDirection'] != '')
		    {
		        return $_SESSION['menuDirection'];
		    }
		    else 
		    {
        		$sql = "SELECT menuDirection FROM users WHERE user_id = ".$userId;
        		$menuDirection = $db->getRows($sql);
        		if($menuDirection[0] == "") {
        			$menuDirection[0] = "Left";
        		}
        		
        		if ($menuDirection[0]['menuDirection'] == '')
        		    $menuDirection[0]['menuDirection'] = 'Left';
        		
    		    $_SESSION['menuDirection'] = $menuDirection[0]['menuDirection'];
    		//    showArray($menuDirection[0]);
    		    return $menuDirection[0]['menuDirection'];
		    }
		}
	}
	static function login($uname, $pw)
	{
		
		global $db, $_SESSION;

		$pw = md5($pw);
		$uname = addslashes(strtoupper(trim($uname)));
		if (trim($uname) != '' && trim($pw) != '')
		{
		    $sql = "SELECT u.*, tm.plants, tm.teams, tz.tz, tz.tzOffset
                    FROM users u
                    LEFT JOIN timezone tz ON tz.id = u.timezone
                    LEFT JOIN
                    (
                        SELECT GROUP_CONCAT(DISTINCT plantId) as plants, GROUP_CONCAT(DISTINCT teamId) as teams, userId
                        FROM team_member tm
                        LEFT JOIN team t ON t.id = tm.teamId
                        WHERE rowStatus=1
                        AND status = 1
                        GROUP BY tm.userId
                    ) tm ON tm.userId = u.user_id
						  WHERE status = 1 AND UPPER(username) = '{$uname}' AND password = '{$pw}'";
		    $user = $db->getRows($sql);
			// showArray($sql);
			// showArray($user);
		}
		if (count($user) == 1)
		{

			if ($user[0]['expirationDate'] != '0000-00-00 00:00:00' && date('U') >= date('U', strtotime($user[0]['expirationDate'])))
			{
				return $user[0]['expirationDate'];
			}
			else
			{
			    $plants = $user[0]['plants'];
			    $plants = explode(',', $plants);
			    
			    $plants = array_unique($plants);
			    $plants = implode(',', $plants);
			    
			    $teams = $user[0]['teams'];
			    $teams = explode(',', $teams);
			    
			    $teams = array_unique($teams);
			    $teams = implode(',', $teams);
			    
				if (isset($user[0]['plantId']) && (int)$user[0]['plantId'] > 0)
				{
					$sql = "SELECT d.document as softwareLogo, dl.document as documentLogo, companyAbbreviation, restricted
							FROM company c
							LEFT JOIN document d ON d.id = c.logoId
							LEFT JOIN document dl ON dl.id = c.documentLogoId
							WHERE c.id = " . $user[0]['plantId'];

					$company = $db->getRows($sql);

					if (isset($company[0]['softwareLogo']) && file_exists($company[0]['softwareLogo']))
					{
						$_SESSION['company_logo'] = $company[0]['softwareLogo'];
						$_SESSION['softwareLogo'] = $company[0]['softwareLogo'];
					}

					if (isset($company[0]['documentLogo']) && file_exists($company[0]['documentLogo']))
						$_SESSION['documentLogo'] = $company[0]['documentLogo'];
					$_SESSION['plantAbbrev'] = $company[0]['companyAbbreviation'];
				}

				$_SESSION['user_id'] = $user[0]['user_id'];
				$_SESSION['user_companyId'] = $user[0]['userCompanyId'];
				$_SESSION['user_firstName'] = $user[0]['firstname'];
				$_SESSION['user_lastName'] = $user[0]['lastname'];
				$_SESSION['user_email'] = $user[0]['email'];
				$_SESSION['user_plantId'] = $user[0]['plantId'];
				$_SESSION['user_usePlantId'] = $user[0]['plantId'];
				$_SESSION['timezone'] = $user[0]['timezone'];
				$_SESSION['tz'] = $user[0]['tz'];
				$_SESSION['tzOffset'] = $user[0]['tzOffset'];
				
				$_SESSION['user_plants'] = $plants;
				$_SESSION['user_teams'] = $teams;
				$_SESSION['restricted'] = ($company[0]['restricted']==1?true:false);

				$_SESSION['filterOwn'] = $user[0]['filterOwn'];

				//Not sure how "Admin" got changed to Management, but I have to deal with it now...
				$_SESSION['is_management'] = ($user[0]['permissionId']==1?true:false);
				$_SESSION['is_admin'] = ($user[0]['permissionId']==1?true:false);
				$_SESSION['is_super_admin'] = ($user[0]['permissionId']==7?true:false);
				$_SESSION['is_upper_mgmt'] = ($user[0]['permissionId']==6?true:false);
				$_SESSION['is_employee'] = ($user[0]['permissionId']==2 || $user[0]['permissionId']==8?true:false);
				$_SESSION['is_agency'] = ($user[0]['permissionId']==3?true:false);
				$_SESSION['is_client'] = ($user[0]['permissionId']==4?true:false);
				$_SESSION['is_contractor'] = ($user[0]['permissionId']==5?true:false);
				$_SESSION['is_read_only'] = ($user[0]['permissionId']==8?true:false);
				if ($user[0]['permissionId']==1)
					$_SESSION['admin_id'] = $user[0]['user_id'];

				$te = new GoogleCloud();
				$_SESSION['googleBucketName'] = $te->bucketName;
				$_SESSION['msg'] = "";

				return true;
			}
		} else {
			return false;
		}
	}

	public function logout()
	{
		session_destroy();
		redirect('/?p=');
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