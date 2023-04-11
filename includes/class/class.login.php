<?php
class Login extends DBObject
{
	public $error;

	public $searchFields = array('ip');
	function __construct($id = "")
	{
		parent::__construct('login', 'id', array('email', 'userId', 'success', 'ip', 'hostname', 'city', 'region', 'country', 'timezone', 'latitude', 'longitude', 'postal', 'datetime'), $id);
	}
	function validate()
	{
		global $db;
		$proceed = true;
		return $proceed;

	}
}