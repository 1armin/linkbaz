<?php

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
// Include session files
require_once  INCLUDE_DIR.'session.php';
// Include apicaller files
require_once  INCLUDE_DIR.'apicaller.php';
//include message file
require_once INCLUDE_DIR.'message.php';


//get message
$message = message();

$user_loged = ($loged_username == "guest") ? false : true ;

$username = (isset($_GET["u"])) ? $_GET["u"] : null;

$obj_security = new security();
$username     = $obj_security->filter_username($username);

if(!empty($username))
{
    //profile page code is here
    include "profile_page.php";
}
else
{
    //landing page code is here
    include "landing_page.php";
}
exit;