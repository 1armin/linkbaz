<?php

session_start();

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
//include message file
require_once INCLUDE_DIR.'message.php';
//include mytools file
require_once INCLUDE_DIR.'mytools.php';

try
{
if(isset($_POST["username"]))
{

 
  $username = $_POST["username"];
  $password = $_POST["password"];
  $remember = isset($_POST['remember']) ? '1' : '0';
  
  $obj_security= new security();
  $username = $obj_security->filter_username($username);
  $password = md5($password);

  $obj_connect= new PDO(DSN,DBUSER,DBPASS);
  $qery = "select * from users where username=:username and password=:password";
  $stmt = $obj_connect->prepare($qery);
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':password', $password);
  $stmt->execute();

  

    if($stmt->fetch(PDO::FETCH_ASSOC))
    {

        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($remember == '1')
        {

            $randomnumber = mt_rand(99,999999);                     //generates a random number to serve as a key
            $token        = dechex(($randomnumber*$randomnumber));  //convert number to HEXADECIMAL form
            $key          = sha1($token . $randomnumber);
            $timenow      = time()+60*60*24*30;                     //stocks 30 day in the var

            $quusername     = $obj_connect->quote($username);
            $qutoken        = $obj_connect->quote($token);
            $qurandomnumber = $obj_connect->quote($randomnumber);

            $obj_connect->exec("UPDATE users SET token=$qutoken WHERE username=$quusername");
            $obj_connect->exec("UPDATE users SET randomnumber=$qurandomnumber WHERE username=$quusername");
            

            setcookie("rememberme", $username . "," . $key, $timenow, "/");

        }

        if ($row["verify"] != "verified")
        {
            $_SESSION["userverify"] = "not verified";
        }

        $_SESSION["usersession"] = $username;

        print( redirect('index.php?u='.$username) );
    }
    
    else
    {
        //bake to login page 
        print( redirect('login.php?message=51') );
    }
}
// get message.
$message = message();
}
catch( Exception $e )
{
	echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>linkbaz :: login</title>
    <meta charset="UTF-8">
    <meta name="author" content="Armin Mohammadian">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/login_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>

    <script language="javascript">
    $(document).ready(function(){

        $("#username").focusout(function(){

            var username = $("#username").val();
            var username_pattern = /\W/; // allow letters, numbers, and underscores

            if (username_pattern.test(username))
            {
                $("#usererr").text("username not valide.");
            }
            else
            {
                $("#usererr").text("");
            }
        });

        $("#password").focusout(function(){
            var password = $("#password").val();
            if (password != "")
            {
                $("#passerr").text("");
            }
        });

        $("#logbutt").click(function(){

            var username = $("#username").val();
            var password = $("#password").val();

            var username_validation = false;
            var password_validation = false;

            var username_pattern = /\W/; // allow letters, numbers, and underscores


            //username validation
            if ((username == ""))
            {
                $("#usererr").text("username not entered.");
            }
            else if (username_pattern.test(username))
            {
                $("#usererr").text("username not valide.");
            }
            else
            {
                $("#usererr").text("");
                username_validation = true;
            }


            //password validation
            if ((password == ""))
            {
                $("#passerr").text("password not entered.");
            }
            else
            {
                $("#passerr").text("");
                password_validation = true;

            }

            //all validation
            if (username_validation && password_validation){
                oForm = document.forms["login"];
                oForm.submit(); 
            }
        });


    });
    </script>
</head>

<body>
    <header>
        <div id="logo"></div>
    </header>
    <section>
        <span id="message"><?php print($message); ?></span>
        <form name="login" method="post" action="login.php" id="flogin">

            <input name="username" type="text" id="username" placeholder="username">
            <span id="usererr" class="error"></span>
            <input name="password" type="password" id="password" placeholder="password">
            <span id="passerr" class="error"></span>
            <span id="spanremember"><input name="remember" type="checkbox" id="remember">remember me</span>
            <input name="login" type="button" value="login" id="logbutt">

        </form>
        <p class="spanlink">not a member yet? <a href="join.php">registr now</a></p>
        <p class="spanlink">lost your password? <a href="forget_password.php">reset</a></p>


    </section>
    <footer>
        <p>This site has no copyright.</p>
        <a href="#">Contact</a>
        <a href="#">Help</a>
        <a href="#">Term Of Service</a>
        <a href="#">About Linkbaz</a>
    </footer>
</body>

</html>