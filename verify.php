<?php

// Include utility files
require_once 'config.php';
//include security class file
require_once INCLUDE_DIR.'security.php';
//include primary_session file
require_once  INCLUDE_DIR. 'session.php';

try {
    if(isset($_GET['code']))
    {
        $verifycode   = $_GET['code'];
        $obj_security = new security();
        $verifycode   = $obj_security->filter_md5code($verifycode);

        $obj_connect  = new PDO(DSN,DBUSER,DBPASS);

        $qery = "select * from users where verify=:verifycode";
        $stmt = $obj_connect->prepare($qery);
        $stmt->bindValue(':verifycode', $verifycode);
        $stmt->execute();

        if( $stmt->fetchAll() )
        {
           $quverifycode = $obj_connect->quote($verifycode);
           $obj_connect->exec("UPDATE users SET verify='verified' WHERE verify=$quverifycode");

           //
           $result['success'] = true;
           $_SESSION["userverify"]="";
        }
        else
        {
            throw new Exception('verify code is invalid.');
        }
    }
    else
    {
        throw new Exception('verify code is invalid.');
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
    <title>linkbaz :: login</title>
    <meta charset="UTF-8">
    <meta name="author" content="Armin Mohammadian">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="<?php echo ACCE_URL;?>js/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo ACCE_URL;?>css/verify_style.css">
    <script src="<?php echo ACCE_URL;?>js/jquery.js"></script>
</head>

<body>
    <header>
        <div id="logo"></div>
    </header>
    <section>
    <?php if( $result['success'] === false ): ?>
        <span id="message"><?php echo $result['errormsg']; ?></span>
    <?php else: ?>
        <span id="message"><?php echo "your accont verify successfully." ?></span>
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