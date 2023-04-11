<?php
class Portfolio extends DBObject
{
	public $error;

	public $searchFields = array('ownerName', 'ownerId');

	function __construct($id = "")
	{
		parent::__construct('portfolio', 'ownerId', array('ownerName', 'ownerId', 'id'), $id);
	}

	function validate()
	{
		global $db;
		$proceed = true;
		return $proceed;

	}

	function createPortfolio($ownerName, $ownerId)
	{
		global $db;
		$sql = "INSERT INTO portfolio (ownerName, ownerId)
		VALUES ('" . $ownerName . "', " . $ownerId . ")";
		showArray($sql);
		$db->query($sql);
	}

}