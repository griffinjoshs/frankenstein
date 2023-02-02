<?PHP

	class DBObject
	{
		public $id;
		private $id_name;
		private $table_name;
		private $columns = array();

		function __construct($table_name, $id_name, $columns, $id = "")
		{
			$this->table_name = $table_name;
			$this->id_name = $id_name;

			foreach($columns as $key)
				$this->columns[$key] = null;

			if($id)
				$this->select($id);
		}

		function __get($key)
		{
		    if (array_key_exists($key, $this->columns))
			     return $this->columns[$key];
		}

		function __set($key, $value)
		{
			if(array_key_exists($key, $this->columns))
			{
				$this->columns[$key] = $value;
				return true;
			}
			return false;
		}

		function select($id, $column = "")
		{
			global $db;

			if (isset($id) && (is_numeric($id) || (trim($id) != '' && $this->table_name == 'apis')))
			{
				if($column == "") $column = $this->id_name;

				$id = mysqli_real_escape_string($db->db, $id);
				$column = mysqli_real_escape_string($db->db, $column);

				$db->query("SELECT * FROM " . $this->table_name . " WHERE `$column` = '$id'" . (isset($xSql) ? $xSql : ''));
				if(mysqli_num_rows($db->result) == 0)
				{
				    //echo "SELECT * FROM " . $this->table_name . " WHERE `$column` = '$id'" . (isset($xSql) ? $xSql : '');
				//	die(formatStdErrorMessage("Access Denied", "It appears you have accessed this page incorrectly.  If you feel this is in error, please contact support at " . SUPPORT_PHONE . " OR <a href='mailto:" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a>"));
					return false;
				}
				else
				{
					$this->id = $id;
					$row = mysqli_fetch_array($db->result, MYSQLI_ASSOC);
					foreach($row as $key => $val)
						$this->columns[$key] = $val;
				}
			}
		}

		function replace()
		{
			return $this->insert("REPLACE INTO");
		}

		function save() {
			if($this->id) $res = $this->update();
			else $res = $this->insert();

			//$this->setUserCompanyId();

			return $res;
		}

		function insert($cmd = "INSERT INTO")
		{
			global $db;

			if(count($this->columns) > 0)
			{
				unset($this->columns[$this->id_name]);

				if (array_key_exists('datetime', $this->columns) && $this->columns['datetime'] == '')
					unset($this->columns['datetime']);

				$columns = "`" . join("`, `", array_keys($this->columns)) . "`";
				$values  = "'" . join("', '", $this->quote_column_vals()) . "'";
				//if (USER_SYSTEM_ADMIN){ echo "$cmd " . $this->table_name . " ($columns) VALUES ($values)";  }
				$db->query("$cmd " . $this->table_name . " ($columns) VALUES ($values)");

				$this->id = mysqli_insert_id($db->db);
				return $this->id;
			}
		}

		function update()
		{
			global $db;

			$arrStuff = array();
			unset($this->columns[$this->id_name]);
			foreach($this->quote_column_vals() as $key => $val)
				$arrStuff[] = "`$key` = '$val'";
			$stuff = implode(", ", $arrStuff);

			$id = mysqli_real_escape_string($db->db, $this->id);
			// if (USER_SYSTEM_ADMIN) {echo "UPDATE " . $this->table_name . " SET $stuff WHERE " . $this->id_name . " = '" . $id . "'"; exit;}
			$res = $db->query("UPDATE " . $this->table_name . " SET $stuff WHERE " . $this->id_name . " = '" . $id . "'");
			if ($res)
				return $res;

			//This was the default but I didn't like it...
			return mysqli_affected_rows($db->db); // Not always correct due to mysql update bug/feature
		}

		function delete()
		{
			global $db;
			$id = mysqli_real_escape_string($db->db, $this->id);
			$db->query("DELETE FROM " . $this->table_name . " WHERE `" . $this->id_name . "` = '" . $id . "'");
			return mysql_affected_rows($db->db);
		}

		function postload() { $this->load($_POST); }
		function postvars($vars) { $this->load($vars); }
		function getload()  { $this->load($_GET); }
		function load($arr)
		{


			if(is_array($arr))
			{
				foreach($arr as $key => $val)
					if(array_key_exists($key, $this->columns) && $key != $this->id_name)
					{
						$this->columns[$key] = fix_slashes($val);
					}
					else if($key=='action_id')
					{
						$this->columns[$key] = fix_slashes($val);
					}
					else
					{

					}
				return true;
			}
			else
				return false;
		}

		function set($field, $value)
		{
			$this->columns[$field] = trim(fix_slashes($value));
		}

		function quote_column_vals()
		{
			global $db;
			$columnVals = array();
			foreach($this->columns  as $key => $val)
				$columnVals[$key] = mysqli_real_escape_string($db->db, $val);
			return $columnVals;
		}

		function columns() {
			return $this->columns;
		}

		/*
		returns an array of $class DBObjects matching $where and $sort.
		only uses one DB call.
		have to pass classname as first arg, rather than calling Class::getObjects($where, $order)
		because __CLASS__ = DBObject regardless of actual calling Class.
		*/
		static function getObjects($class, $where = '', $order = '') {
			if($where) $where = 'WHERE ' . $where;
			if($order) $order = 'ORDER BY ' . $order;
			global $db;
			$ex = new $class();
			$id = $ex->id_name;
			$table = $ex->table_name;
			unset($ex);
			$ids = $db->getRows("SELECT * FROM `$table` $where $order");
			$ret = array();
			foreach($ids AS $row) {
				$r = new $class();
				$r->load($row);
				$r->id = $row[$id];
				$ret[] = $r;
			}
			return $ret;
		}

		/*
		returns an array with [0] => an array with all objects where $property = value, and [1] => objects where $property != $value
		*/
		static function partitionObjects($objects, $property, $value) {
			$true = array();
			$false = array();
			foreach($objects AS $object) {
				if($object->$property == $value) $true[] = $object;
				else $false[] = $object;
			}
			return array($true, $false);
		}

		/*
		splits up DBObjects by a property, sorting them into buckets by that property
		*/
		static function organizeObjects($objects, $property) {
			$ret = array();
			foreach($objects AS $object) $ret[$object->$property][] = $object;
			return $ret;
		}

		/*
		TODO: write a 'saveall' function to remove loop of update()s
		*/
		static function sortObjects($objects, $id, $dir) {
			$count = count($objects);
			for($i = 0; $i < $count; $i++) {
				if($id == $objects[$i]->id) {
					if('move-up' == $dir && ($i > 0)) {
						$objects[$i - 1]->sort = $i;
						$objects[$i]->sort = $i - 1;
						$objects[$i - 1]->update();
						$objects[$i]->update();
					} elseif('move-down' == $dir && $i < ($count - 1)) {
						$objects[$i]->sort = $i + 1;
						$objects[$i + 1]->sort = $i;
						$objects[$i]->update();
						$objects[$i + 1]->update();
					}
					$i++;
				} else {
					$objects[$i]->sort = $i;
					$objects[$i]->update();
				}
			}
		}

		/*
		returns an array containing all $objects, keyed by their $id
		*/
		static function idObjects($objects) {
			// fun trick: equivalent to
			// return array_extract(DBObject::organizeObjects($objects, 'id'), 0);
			$ret = array();
			foreach($objects AS $object) $ret[$object->id] = $object;
			return $ret;
		}

		function createSqlSearch($sql, $value, $fields)
		{
			$terms = explode(' ',$value);
			foreach ($terms as $t)
			{
				$t = mysqli_real_escape_string ($this->db, trim($t));
				if ($t!='')
				{
					$ret='';
					foreach ($fields as $fld)
					{
						$ret .= " OR $fld LIKE '%$t%'";
					}

					$tSql .= " AND (1=2 " . $ret . ") ";
				}
			}
			return $sql . $tSql;
		}

		function setUserCompanyId()
		{
			global $db;

			if ((int)COMPANYID > 0 && $this->userCompanyId === 0)
			{
				$companyArr = $db->getRows("SELECT userCompanyId FROM {$this->table_name} WHERE `$this->id_name` = '$this->id'");

				if ((int)$companyArr[0]['userCompanyId'] == 0)
				{
					$db->query("UPDATE {$this->table_name} SET userCompanyId ='" . COMPANYID . "' WHERE `$this->id_name` = '$this->id'");
				}
			}
		}
	}

	function sortDBObjects($objects, $id, $dir) {
		DBObject::sortObjects($objects, $id, $dir);
	}
?>