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
try {
    if(isset($_POST["linkid"]))
    {
         if( isset($_POST["url"]) && isset($_POST["title"]) )
        {
            $link_id      = $_POST["linkid"];
            $title        = $_POST["title"];
            $url          = $_POST["url"];
            $description  = $_POST["description"];

            $obj_security = new security();
            $link_id      = $obj_security->filter_num($link_id);
            $title        = $obj_security->filter_text($title);
            $url          = $obj_security->filter_url($url);
            $description  = $obj_security->filter_text($description);

            $apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', API_URL);

            $link = $apicaller->sendRequest(array(
                'controller' => 'link',
                'action' => 'update',
                'title' => $_POST['title'],
                'url' => $_POST['url'],
                'description' => $_POST['description'],
                'id' => $link_id,
                'username' => $loged_username,
                'password' => $loged_password
            ));

            // 
            $result['success'] = true;
            $result['msg'] = "link saccessfuly updated.";
        }
        else
        {
            //
            throw new Exception('your request is invalid (title or url not defind).');
        }
    }
    else
    {
        $id = (isset($_GET["id"])) ? $_GET["id"] : null;

        $obj_security = new security();
        $id = $obj_security->filter_num($id);

        if(!empty($id))
        {
            $apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', API_URL);

            $link = $apicaller->sendRequest(array(
                'controller' => 'link',
                'action' => 'read',
                'id' => $id,
                'username' => $loged_username
            ));
            $result['success'] = true;
        }
        else
        {
            throw new Exception('your request is invalid.');
        }
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
    <title>linkbaz :: edit the link</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/edit_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>

<script language="javascript">
    
    $(document).ready(function(){

        $("#ed_send").click(function(){

            var title       = $("#ed_title").val();
            var url         = $("#ed_url").val();

            var title_validation       = false;
            var url_validation         = false;

        
            //title validation
            if ((title == ""))
            {
                $("#ed_titleerr").text("title not entered.");
            }
            else
            {
                $("#ed_titleerr").text("");
                title_validation = true;
            }


            //url validation
            if ((url == ""))
            {
                $("#ed_urlerr").text("url not entered.");
            }
            else
            {
                $("#ed_urlerr").text("");
                url_validation = true;
            }


            //all validation
            if (title_validation && url_validation){
                editlinkform = document.forms["editlink"];
                editlinkform.submit();
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
    <?php if( $result['success'] === false ): ?>
        <span id="message"><?php echo $result['errormsg']; ?></span>
    <?php else: ?>
        <span id="message"><?php if ( isset($result['msg']) ) { echo $result['msg']; } ?></span>
        <form action="edit.php" method="post" name="editlink" id="editlink">

        <input name="linkid" type="hidden" value="<?php echo $link->id; ?>">

        <input name="title" type="text" id="ed_title" maxlength="500" placeholder="add title" value="<?php echo $link->title; ?>">
        <span id="ed_titleerr" class="error"></span>

        <input name="url" type="text" id="ed_url" placeholder="link url" value="<?php echo $link->url; ?>">
        <span id="ed_urlerr" class="error"></span>

        <input name="description" type="text" id="ed_description" placeholder="add a description for link" value="<?php echo $link->description; ?>">

        <input name="tags" type="text" id="ed_tags" placeholder="add tags" value="">

        <input name="send" type="button" value="update" id="ed_send">

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