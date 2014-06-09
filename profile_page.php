<?php

try
{
    //user profile code.
    $obj_connect = new PDO(DSN,DBUSER,DBPASS);
    $qery        = "select * from users where username=:username";
    $stmt        = $obj_connect->prepare($qery);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    if( !$user_items = $stmt->fetchAll(PDO::FETCH_ASSOC) ) {
        throw new Exception('user is invalid.');
    }

    $apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', API_URL);

    $links = $apicaller->sendRequest(array(
        'controller' => 'link',
        'action' => 'read',
        'username' => $username
    ));
    $result['success'] = true;
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
    <title>linkbaz :: <?php echo ( $result['success'] ) ? $username : "user not find" ; ?> </title>
    <meta charset="UTF-8">
    <meta name="author" content="Armin Mohammadian">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">

    <meta property="og:title" content="linkbaz"/>
    <meta property="og:site_name" content="linkbaz"/>
    <meta property="og:description" content=""/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://linkbaz.net/"/>
    <meta property="og:image" content=""/>
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/profile_style.css">
</head>

<body>

    <header>

        <div id="logo"></div>

        <?php if ( $result['success'] ) { ?>
        <div id="userinfo">
            <p><?php echo $username;?></p>
            <span></span>
            <a id="u_rss" href="#">rss</a>
            <?php if (!empty($user_items["0"]["siteurl"])) { echo "<a id='u_site' href=".$user_items["0"]["siteurl"].">site</a>"; } ?>
        </div>
        <?php } ?>

        <?php if ($user_loged): ?>
        <div id="tooldiv">
            <a id="logout" href="<?php echo SITE_URL;?>logout.php">logout</a>
            <a id="setting" href="<?php echo SITE_URL;?>setting.php">setting</a>
            <a id="add" href="<?php echo SITE_URL;?>add.php">add</a>
        </div>

        <?php else: ?>
        <div id="log_or_jo">
            <a id="login" href="<?php echo SITE_URL;?>login.php">login</a>
            <p>or</p>
            <a id="join" href="<?php echo SITE_URL;?>join.php">create your account</a>
        </div>
        <?php endif; ?>

    </header>
    <section>

    <?php if (isset($_SESSION["userverify"]) and $user_loged) { print('<span id="msgnotvrfy">your email address not verified, please check it and click on verify link.<span id="timer"></span><span id="msgnotvrfyclose" onclick="msgnotvrfyhideFunction()">X</span></span>'); }?>
    <?php print($message); ?>

    <?php if( $result['success'] === false ): ?>
        <span id="message"><?php echo $result['errormsg']; ?></span>
    <?php else: ?>    
    <ul>
        <?php
        foreach ($links as $value) {
            $parseurl = parse_url($value->url);
            echo "<li>
            <a id='link_". $value->id ."' href='". URL_URL ."?s=". $value->id ."&q=". $value->url ."'>". $value->title ."</a>
            <p>". $value->description ."</p>
            <span class='link_scorce'>". $parseurl["host"] ."</span>
            <span class='link_date'>". $value->date ."</span>
            <span class='link_clickcount'>". $value->clickcount ."</span>
            <span class='link_share'>share</span>";
            
            if ($user_loged) {
                echo "<a href='". SITE_URL ."edit.php?id=". $value->id ."' class='link_edit'>edit</a>
                <a href='". SITE_URL ."delete.php?id=". $value->id ."' class='link_delete'>delete</a>";
            }
            echo "</li>";
        } ?>

    </ul>
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