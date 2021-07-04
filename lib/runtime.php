<?php

$datalink = dbconnect();

/**
 * @return object
 * @param dsn = string unknown
 * @desc Establishes a database connection. DB parameters in config.inc.php
 */

function dbconnect() {
	$dsn = DSN;

	$connx =& MDB2::connect($dsn, __PCONNECT__);
	if (MDB2::isError($connx)) {
	    // You would need to implement graceful error control here, of course.
		die("Database connection failed: " . $connx->getUserInfo());
		return FALSE;
	} else {
	    // See the Pear::DB documentation for more options
		$connx->setFetchMode(DB_FETCHMODE_ASSOC);
		//$connx->query("SET CHARACTER SET 'utf8'");
		$connx->query("SET NAMES utf8");
		//$connx->query("SET character_set_client = 'utf8'");

		return $connx;
	}
}

?>
