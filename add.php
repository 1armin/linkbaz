<?php

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
// Include session files
require_once INCLUDE_DIR.'primary_session.php';
// Include apicaller files
require_once INCLUDE_DIR.'apicaller.php';

$result = array();
try
{
    if(isset($_POST["title"]))
    {
         if(isset($_POST["url"]))
        {
            $title        = $_POST["title"];
            $url          = $_POST["url"];
            $description  = $_POST["description"];

            $obj_security = new security();
            $title        = $obj_security->filter_text($title);
            $url          = $obj_security->filter_url($url);
            $description  = $obj_security->filter_text($description);

            $apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', API_URL);

            $new_item = $apicaller->sendRequest(array(
                'controller' => 'link',
                'action' => 'create',
                'title' => $title,
                'url' => $url,
                'description' => $description,
                'username' => $loged_username,
                'password' => $loged_password
            ));

            //
            $result['success'] = true;
            $result['msg'] = "link saccessfuly added";
        }
        else
        {
            //
            $result['success'] = false;
            $result['errormsg'] = "your request is invalid( link url not defind).";
        }
    }
}
catch( Exception $e )
{
	//catch any exceptions and report the problem
    $result['success'] = false;
    $result['errormsg'] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>linkbaz :: add new link</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/add_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>

<script language="javascript">
    
    $(document).ready(function(){

        $("#ad_send").click(function(){

            var title       = $("#ad_title").val();
            var url         = $("#ad_url").val();

            var title_validation       = false;
            var url_validation         = false;

        
            //title validation
            if ((title == ""))
            {
                $("#ad_titleerr").text("title not entered.");
            }
            else
            {
                $("#ad_titleerr").text("");
                title_validation = true;
            }


            //url validation
            if ((url == ""))
            {
                $("#ad_urlerr").text("url not entered.");
            }
            else
            {
                $("#ad_urlerr").text("");
                url_validation = true;
            }


            //all validation
            if (title_validation && url_validation){
                addlinkform = document.forms["addlink"];
                addlinkform.submit();
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
            <?php echo "<a id='u_site' href=".$loged_site.">site</a>"; ?>
            
        </div>
        <div id="tooldiv">
            <a id="logout" href="<?php echo SITE_URL;?>logout.php">logout</a>
            <a id="setting" href="<?php echo SITE_URL;?>setting.php">setting</a>
            <a id="back" href="<?php echo SITE_URL;?>index.php?u=<?php echo $loged_username ;?>">back</a>
        </div>
    </header>




    <section>
    <?php if( isset($result['success']) && $result['success'] === false ) { echo "<span id='message'>".$result['errormsg']."</span>"; } ?>

    <?php if( isset($result['success']) && $result['success'] === true ) { echo "<span id='message'>".$result['msg']."</span>"; } ?>


        <form action="add.php" method="post" name="addlink" id="addlink">

        <input name="title" type="text" id="ad_title" maxlength="500" placeholder="add title">
        <span id="ad_titleerr" class="error"></span>

        <input name="url" type="text" id="ad_url" placeholder="link url">
        <span id="ad_urlerr" class="error"></span>

        <input name="description" type="text" id="ad_description" placeholder="add a description for link">

        <input name="tags" type="text" id="ad_tags" placeholder="add tags">

        <input name="send" type="button" value="add" id="ad_send">

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