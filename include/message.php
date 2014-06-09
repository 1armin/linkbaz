<?php
// message function

function message()
{
	if(isset($_GET['message']))
	{
	$message = $_GET['message'];

	switch($message)
	{
		case '50':
		//save.php
		return '<span class="msg_r" id="messagebox">message: This username already exists, please choose a different username.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;
		
		case '51':
		//check.php
		return '<span class="msg_r" id="messagebox">message: The username or password you entered is incorrect.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;
		
		case '52':
		//session.php
		return '<span class="msg_y" id="messagebox">message: Please login again.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;
		
		case '53':
		//logout.php
		return '<span class="msg_g" id="messagebox">message: You have successfully signed out of administration.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;
		
		case '54':
		//save.php   frist visit
		return '<style>#msgnotvrfy {display: none;}</style><span class="msg_g" id="messagebox">message: welcome to your accont. for varify accont check your email and click on varify link in email.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;

		case '55':
		//save.php   frist visit
		return '<span class="msg_y" id="messagebox">message: password chenged successfully.<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		break;

		case '56':
		//save.php   frist visit
		return '';
		break;

		case '57':
		//save.php   frist visit
		return 'please check your email and click on reset password link.';
		break;

		case '58':
		//save.php   frist visit
		return 'enrered data is incorect.';
		break;
		
		case '59':
		//save.php   frist visit
		return 'password saved successfully.';
		break;
		
		case '60':
		//save.php   frist visit
		return 'error :(';
		break;
		
		default:
		return '<span class="msg_y" id="messagebox">message: message not fund<span id="msgclose" onclick="msghideFunction()">X</span></span>';
		
	}
	}
}