<?php

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
//include securimage class file
require_once 'securimage.php';
//include message file
require_once INCLUDE_DIR.'message.php';
//include mytools file
require_once INCLUDE_DIR.'mytools.php';

try
{
if(isset($_POST["rg_username"]))
{

    $obj_securimage = new Securimage();
    if ($obj_securimage->check($_POST['captcha_code']) == true)
    {
        if(isset($_POST["rg_email"]))
        {


            $username = $_POST["rg_username"];
            $password = $_POST["rg_password"];
            $email = $_POST["rg_email"];
            $site = $_POST["rg_site"];

            $obj_security= new security();
            $username = $obj_security->filter_username($username);
            $email = $obj_security->filter_email($email);
            $site = $obj_security->filter_url($site);

            $date = date("y/m/d");
            $randno1 = mt_rand();
            $randno2 = mt_rand();
            $verifycode = md5($email.'hgfds558sd555ssax44cvbnmml'.$randno1.''.$randno2);
            $emailmessage = "<p>please click below link</p><br><a href='SITE_URL/verify.php?code=$verifycode'></a>";
            $password = md5($password);



            $obj_connect= new PDO(DSN,DBUSER,DBPASS);

            $qery = "select * from users where username=:username";
            $stmt = $obj_connect->prepare($qery);
            $stmt->bindValue(':username', $username);
            $stmt->execute();

            if($stmt->fetchAll(PDO::FETCH_ASSOC))
            {
                //goto join page
                print( redirect('join.php?message=50') );
            }
            else
            {
                $quusername   = $obj_connect->quote($username);
                $qupassword   = $obj_connect->quote($password);
                $quemail      = $obj_connect->quote($email);
                $qusite       = $obj_connect->quote($site);
                $qudate       = $obj_connect->quote($date);
                $quverifycode = $obj_connect->quote($verifycode);

                $obj_connect->exec("INSERT INTO users (username,password,email,siteurl,joindate,verify) VALUES ($quusername,$qupassword,$quemail,$qusite,$qudate,$quverifycode)");


                $_SESSION["userverify"]="not verified";

                $_SESSION["usersession"]=$username;


                mail($email,"verify code",$emailmessage);

                //goto admin page
                print( redirect('index.php?u='.$username.'&message=54') );

            }
        }
        else
        {
            //goto join page
            print( redirect('join.php?message=5') );
        }
    }
    else
    {
        //goto join page
        print( redirect('join.php?message=5') );
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
    <title>linkbaz :: create your account</title>
    <meta charset="UTF-8">
    <meta name="author" content="Armin Mohammadian">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">

    <meta property="og:title" content="linkbaz"/>
    <meta property="og:site_name" content="linkbaz"/>
    <meta property="og:description" content=""/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://linkbaz.net/"/>
    <meta property="og:image" content=""/>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/join_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>

    <script language="javascript">
    
    $(document).ready(function(){

        $("#rg_username").focusout(function(){

            var username = $("#rg_username").val();
            var username_pattern = /\W/; // allow letters, numbers, and underscores

            if ((username.length < 3) && !(username.length == 0))
            {
                $("#rg_usererr").text("username is short [limit 3].");
                $("#rg_username").focus();
            }
            else if (username_pattern.test(username))
            {
                $("#rg_usererr").text("username not valid [just numbers, letters and underscores].");
                $("#rg_username").select();
            }
            else if ((username.length > 3) && !(username_pattern.test(username)))
            {
                $.post("check_username.php",{username:username},function(result){$("#rg_usererr").text(result)});
            }
            else
            {
                $("#rg_usererr").text("");
            }

        });

        $("#rg_password").focusout(function(){
            var password = $("#rg_password").val();
            if ((password.length < 5) && !(password.length == 0))
            {
                $("#rg_passerr").text("password is short.");
                $("#rg_password").focus();
            }
            else
            {
                $("#rg_passerr").text("");
            }
        });

        $("#rg_repeat_password").focusout(function(){

            var password = $("#rg_password").val();
            var repassword = $("#rg_repeat_password").val();

            if (!(password == repassword) && !(repassword.length == 0) && !(password.length == 0))
            {
                $("#rg_repasserr").text("password not equal whit repeat.");
            }
            else
            {
                $("#rg_repasserr").text("");
            }
        });
        
        $("#rg_email").focusout(function(){

            var email_pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var email = $("#rg_email").val();

            if (!(email_pattern.test(email)) && !(email.length == 0))
            {
                $("#rg_emailerr").text("Email address not valid.");
                $("#rg_email").select();
            }
            else
            {
                $("#rg_emailerr").text("");
            }
        });



        $("#rg_send").click(function(){

            var username   = $("#rg_username").val();
            var password   = $("#rg_password").val();
            var repassword = $("#rg_repeat_password").val();
            var email      = $("#rg_email").val();
            var captcha    = $("#fcaptcha").val();

            var username_validation   = false;
            var password_validation   = false;
            var repassword_validation = false;
            var email_validation      = false;
            var captcha_validation    = false;

            var username_pattern = /\W/; // allow letters, numbers, and underscores
            var email_pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;


            //username validation
            if ((username == ""))
            {
                $("#rg_usererr").text("username not entered.");
            }
            else if ((username.length < 3) && !(username.length == 0))
            {
                $("#rg_usererr").text("username is short [limit 3].");
            }
            else if (username_pattern.test(username))
            {
                $("#rg_usererr").text("username not valide.");
            }
            else
            {
                $("#rg_usererr").text("");
                username_validation = true;
            }


            //password validation
            if ((password == ""))
            {
                $("#rg_passerr").text("password not entered.");
            }
            else if ((password.length < 5) && !(password.length == 0))
            {
                $("#rg_passerr").text("password is short.");
            }
            else
            {
                $("#rg_passerr").text("");
                password_validation = true;
            }


            //repeat password validation
            if ((repassword == ""))
            {
                $("#rg_repasserr").text("repeat password not entered.");
            }
            else if (!(password === repassword))
            {
                $("#rg_repasserr").text("password not equal whit repeat.");
            }
            else
            {
                $("#rg_repasserr").text("");
                repassword_validation = true;
            }

            //Email validation
            if (email == "")
            {
                $("#rg_emailerr").text("Email address not entered.");
            }
            else if (!email_pattern.test(email))
            {
                $("#rg_emailerr").text("Email address not valid.");
            }
            else
            {
                $("#rg_emailerr").text("");
                email_validation = true;
            }


            //captcha validation
            if (captcha == "")
            {
                $("#rg_captchaerr").text("captcha not entered.");
            }
            else
            {
                $("#rg_captchaerr").text("");
                captcha_validation = true;
            }


            //all validation
            if (username_validation && password_validation && repassword_validation && email_validation && captcha_validation){
                registform = document.forms["register"];
                registform.submit();
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
        <form action="join.php" method="post" name="register" id="fregister">

        <input name="rg_username" type="text" id="rg_username" maxlength="30" placeholder="username">
        <span id="rg_usererr" class="error"></span>

        <input name="rg_password" type="password" id="rg_password" placeholder="password">
        <span id="rg_passerr" class="error"></span>

        <input name="rg_repeat_password" type="password" id="rg_repeat_password" placeholder="repeat password">
        <span id="rg_repasserr" class="error"></span>

        <input name="rg_email" type="email" id="rg_email" placeholder="email">
        <span id="rg_emailerr" class="error"></span>

        <input name="rg_site" type="text" id="rg_site" placeholder="your site">

        <img id="captcha" src="<?php print( SITE_URL.'securimage_show.php'); ?>" alt="CAPTCHA Image" />

        <a href="#" id="refresh" onclick="document.getElementById('captcha').src = '<?php print( SITE_URL."securimage_show.php?"); ?>' + Math.random(); return false">[ Different Image ]</a>
        
        <input type="text" id="fcaptcha" name="captcha_code" size="10" maxlength="6" placeholder="enter above text" />
        <span id="rg_captchaerr" class="error"></span>

        <input name="rg_send" type="button" value="create" id="rg_send">

        </form>

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