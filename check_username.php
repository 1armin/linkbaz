<?php
// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'/security.php';
//include securimage class file



$username = $_POST["username"];

$obj_security= new security();
$username = $obj_security->filter_username($username);

try
{
	$obj_connect= new PDO(DSN,DBUSER,DBPASS);

	$qery = "select * from users where username=:username";
	$stmt = $obj_connect->prepare($qery);
	$stmt->bindValue(':username', $username);
	$stmt->execute();

  	if($stmt->fetchAll(PDO::FETCH_ASSOC))
  	{
		echo "username already exists ,please enter another.";
  	}

  	$obj_connect = null;
}
catch(PDOException $e)
{
	 echo 'Connection failed: ' . $e->getMessage();
	
}
