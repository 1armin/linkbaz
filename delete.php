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
    $link_id = (isset($_GET["id"])) ? $_GET["id"] : null;

    if(is_numeric($link_id))
    {
        $obj_security = new security();
        $link_id      = $obj_security->filter_num($link_id);

        $apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', API_URL);

        $delete_item = $apicaller->sendRequest(array(
            'controller' => 'link',
            'action' => 'delete',
            'id' => $link_id,
            'username' => $loged_username,
            'password' => $loged_password
        ));

        $result['success'] = true;
        $result['msg'] = "link saccessfuly deleted.";
    }
    else
    {
        //
        $result['success'] = false;
        $result['errormsg'] = "your request is invalid.";
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
    <title>linkbaz :: delete the link</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/delete_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>
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