<?php

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
// Include session files
require_once INCLUDE_DIR.'primary_session.php';


$result = array();
try {
    if(isset($_POST["email"]))
    {
        
        $email        = $_POST["email"];
        $url          = (isset($_POST["url"])) ? $_POST["url"] : null;

        $obj_security = new security();
        $email        = $obj_security->filter_email($email);
        $url          = $obj_security->filter_url($url);
        $username     = $obj_security->filter_username($loged_username);

        $obj_connect  = new PDO(DSN,DBUSER,DBPASS);
        $quusername   = $obj_connect->quote($username);
        $quemail      = $obj_connect->quote($email);
        $quurl        = $obj_connect->quote($url);
        
        $obj_connect->exec("UPDATE users SET email=$quemail WHERE username=$quusername");
        $obj_connect->exec("UPDATE users SET siteurl=$quurl WHERE username=$quusername");

        // 
        $result['success'] = true;
        $result['msg'] = "user information saccessfuly updated.";

    }
    elseif (isset($_POST["cu_pass"]) && isset($_POST["ne_pass"])) {

        $cu_pass      = $_POST["cu_pass"];
        $ne_pass      = $_POST["ne_pass"];

        $obj_security = new security();
        $username     = $obj_security->filter_username($loged_username);
        $cu_pass      = md5($cu_pass);
        $ne_pass      = md5($ne_pass);

        $obj_connect  = new PDO(DSN,DBUSER,DBPASS);
        $qery = "select * from users where password=:password and username=:username";
        $stmt = $obj_connect->prepare($qery);
        $stmt->bindValue(':password', $cu_pass);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        if( $stmt->fetchAll() )
        {
            $quusername   = $obj_connect->quote($username);
            $qune_pass    = $obj_connect->quote($ne_pass);
        
            $obj_connect->exec("UPDATE users SET password=$qune_pass WHERE username=$quusername");

           //
           $result['success'] = true;
           $result['msg'] = "password saccessfuly chenged.";
        }
        else
        {
            throw new Exception('old password is incorrect');
        } 
    }


    $obj_security = new security();
    $username     = $obj_security->filter_username($loged_username);

    $obj_connect  = new PDO(DSN,DBUSER,DBPASS);
    $qery = "select * from users where username=:username";
    $stmt = $obj_connect->prepare($qery);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    if( $user_items = $stmt->fetchAll(PDO::FETCH_ASSOC) )
    {
       //
       $result['success'] = true;
    }
    else
    {
        throw new Exception('username is incorrect :|');
    }
    
    
} catch (Exception $e) {
    //catch any exceptions and report the problem
    $result['success'] = false;
    $result['errormsg'] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>linkbaz :: edit your information</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/edit_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>

<script language="javascript">
    
    $(document).ready(function(){

        $("#send_info").click(function(){

            var email       = $("#email").val();

            var email_pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        
            //email validation
            if ((email == ""))
            {
                $("#emailerr").text("email not entered.");
            }
            else if (!email_pattern.test(email))
            {
                $("#emailerr").text("Email address not valid.");
            }
            else
            {
                settinginfo = document.forms["setting_info"];
                settinginfo.submit();
            }

        });



        $("#send_pass").click(function(){

            var cupass  = $("#cu_pass").val();
            var nepass = $("#ne_pass").val();
            var repass  = $("#re_pass").val();

            var cupass_validation = false;
            var nepass_validation = false;
            var repass_validation = false;
        
            //current password validation
            if ((cupass == ""))
            {
                $("#cu_passerr").text("password not entered.");
            }
            else
            {
                $("#cu_passerr").text("");
                cupass_validation = true;
            }

            //new password validation
            if ((nepass == ""))
            {
                $("#ne_passerr").text("new password not entered.");
            }
            else if ((nepass.length < 5) && !(nepass.length == 0))
            {
                $("#ne_passerr").text("new password is short.");
            }
            else
            {
                $("#cu_passerr").text("");
                nepass_validation = true;
            }

            //repeat password validation
            if ((repass != nepass))
            {
                $("#re_passerr").text("password not equal whit repeat.");
            }
            else
            {
                $("#re_passerr").text("");
                repass_validation = true;
            }

            //all validation
            if (cupass_validation && nepass_validation && repass_validation){
                settingpass = document.forms["setting_pass"];
                settingpass.submit();
            }
        });
    });

</script>

</head>

<body>
    <header>
        <div id="logo"></div>
        <div id="userinfo">
            <p><?php echo $loged_username;?></p>
            <span></span>
            <a id="u_rss" href="#">rss</a>
            <?php echo "<a id='u_site' href=".$user_items[0]["siteurl"].">site</a>"; ?>
            
        </div>
        <div id="tooldiv">
            <a id="logout" href="<?php echo SITE_URL;?>logout.php">logout</a>
            <a id="setting" href="<?php echo SITE_URL;?>setting.php">setting</a>
            <a id="back" href="<?php echo SITE_URL;?>index.php?u=<?php echo $loged_username ;?>">back</a>
        </div>
    </header>
    <section>
    <?php if( $result['success'] === false ): ?>
        <span id="message"><?php echo $result['errormsg']; ?></span>
    <?php else: ?>
        <span id="message"><?php if ( isset($result['msg']) ) { echo $result['msg']; } ?></span>


        <form action="setting.php" method="post" name="setting_info" id="setting_info">

        <label>username:</label><p><?php echo $user_items[0]["username"]; ?></p>

        <input name="email" type="email" id="email" placeholder="your email" value="<?php echo $user_items[0]["email"]; ?>">
        <span id="emailerr" class="error"></span>

        <input name="url" type="text" id="url" placeholder="your site url" value="<?php echo $user_items[0]["siteurl"]; ?>">
        <span id="urlerr" class="error"></span>

        <input name="send_info" type="button" value="save" id="send_info">
        </form>




        <form action="setting.php" method="post" name="setting_pass" id="setting_pass">

        <input name="cu_pass" type="text" id="cu_pass" placeholder="current password">
        <span id="cu_passerr" class="error"></span>

        <input name="ne_pass" type="password" id="ne_pass" placeholder="new password">
        <span id="ne_passerr" class="error"></span>

        <input name="re_pass" type="password" id="re_pass" placeholder="repeat new password">
        <span id="re_passerr" class="error"></span>

        <input name="send_pass" type="button" value="chenge" id="send_pass">
        </form>
    <?php endif; ?>

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