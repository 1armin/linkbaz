<?php
session_start();

// Include utility files
require_once 'config.php';

//include mytools file
require_once INCLUDE_DIR.'/mytools.php';

$loged_username = (isset($_SESSION['usersession'])) ? $_SESSION['usersession'] : "" ;
$loged_username = filter_var($loged_username, FILTER_SANITIZE_STRING);

setcookie("rememberme", "", time()-3600, "/");

session_unset("usersession");

print(redirect('index.php?message=53&u='.$loged_username));