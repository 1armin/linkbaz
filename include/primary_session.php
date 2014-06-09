<?php

// for admin section

session_start();


 if (isset($_COOKIE['rememberme']) and !isset($_SESSION['usersession']))
 {

 	$rememberme = explode(",", $_COOKIE["rememberme"]);
 	$cookie_username = $rememberme[0];
    $cookie_key = $rememberme[1];

    $cookie_username = filter_var($cookie_username, FILTER_SANITIZE_STRING);
    $cookie_key = filter_var($cookie_key, FILTER_SANITIZE_STRING);

    $obj_connect= new PDO(DSN,DBUSER,DBPASS);
    $qery = "select * from users where username=:username";
    $stmt = $obj_connect->prepare($qery);
    $stmt->bindValue(':username', $cookie_username);
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $obj_connect = null;

    $token = $row["0"]['token'];
    $randomnumber = $row["0"]['randomnumber'];
    $key = sha1($token . $randomnumber);

    if ($key == $cookie_key)
    {
    	if ($row["0"]["verify"] != "verified")
		{
			$_SESSION["userverify"]="not verified";
		}

		$_SESSION["usersession"]=$cookie_username;
    }
 }


if(!isset($_SESSION['usersession']))
{
    include INCLUDE_DIR . 'mytools.php';
    print(redirect( 'login.php?message=52'));
}
else
{
    $loged_username = $_SESSION['usersession'];
    $loged_username = filter_var($loged_username, FILTER_SANITIZE_STRING);

    $obj_connect= new PDO(DSN,DBUSER,DBPASS);
    $qery = "select * from users where username=:username";
    $stmt = $obj_connect->prepare($qery);
    $stmt->bindValue(':username', $loged_username);
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $obj_connect = null;

    $loged_password = $row["0"]["password"];
    $loged_site     = $row["0"]["siteurl"];
}