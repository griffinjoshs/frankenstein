<?php
class PortfolioItem extends DBObject
{
	public $error;

	public $searchFields = array('id', 'portfolioId');
	function __construct($id = "")
	{
		parent::__construct('portfolio_item', 'portfolioId', array('id', 'itemName', 'itemTicker', 'itemType', 'itemQuantity', 'itemValue'), $id);
	}
	function validate()
	{
		global $db;
		$proceed = true;
		return $proceed;

	}

	function addItem($itemListArray)
	{
		global $db;
		$sql = "SHOW COLUMNS FROM portfolio_item";
		$res = $db->query($sql);
		$cols = "";
		foreach($res as $key=>$value)
		{
			// showArray($key, "key");
			// showArray($res->num_rows, "res num rows");
			// showArray($value['Field'], "value");
			$cols .= $value['Field'] . ($key <= $res->num_rows ? ", " : " ");
		}
		// showArray($cols);
		// $sql = "INSERT INTO portfolio_item (portfolioId, )";
		// foreach($itemListArray as $ila)
		// {
		// 	showArray($ila);
		// }

	}
}