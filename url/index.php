<?php

// Include utility files
require_once '../config.php';
//include security class file
require_once INCLUDE_DIR.'/security.php';

try
{
	$id  = (isset($_GET["s"])) ? $_GET["s"] : null;
	$url = (isset($_GET["q"])) ? $_GET["q"] : null;

	$obj_security = new security();
    $id           = $obj_security->filter_num($id);
    if(!isset($url))
    {
    	throw new Exception('url is invalid.');
    }

	$obj_connect  = new PDO(DSN,DBUSER,DBPASS);
	$quid         = $obj_connect->quote($id);
    $obj_connect->exec("UPDATE link SET clickcount = clickcount + 1 WHERE id = $quid");

    header('Location: ' . $url, true, 303);
    die();
}
catch( Exception $e )
{
	echo "redirecting is failed.  (".$e->getMessage().").";
}
exit();