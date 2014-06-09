<?php
class useritem
{
    public $email;
    public $siteurl;




    public static function getItem($username, $password)
    {
        self::_checkIfUserExists($username, $password);
        
        $user_items  = array();

        $obj_security= new security();
        $username    = $obj_security->filter_username($username);

        $obj_connect = new PDO(DSN,DBUSER,DBPASS);
        $qery        = "select * from users where username=:username";
        $stmt        = $obj_connect->prepare($qery);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $user_items  = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($user_items["0"]["password"]);
        unset($user_items["0"]["id"]);
        unset($user_items["0"]["email"]);
        $obj_connect = null;
        
        return $user_items["0"];
    }





/*public function update($username, $password)
    {
        self::_checkIfUserExists($username, $password);

        $obj_security  = new security();
        $email         = $obj_security->filter_text($this->email);
        $siteurl       = $obj_security->filter_text($this->siteurl);
        $username      = $obj_security->filter_username($username);

        $obj_connect   = new PDO(DSN,DBUSER,DBPASS);

        $quemail       = $obj_connect->quote($email);
        $qusiteurl     = $obj_connect->quote($siteurl);
        $quusername    = $obj_connect->quote($username);

        $obj_connect->exec("UPDATE users SET email=$quemail WHERE username=$quusername");
        $obj_connect->exec("UPDATE users SET siteurl=$qusiteurl WHERE username=$quusername");
        
        $obj_connect = null;
    }
*/

    private static function _checkIfUserExists($username, $password)
    {
        $obj_security= new security();
        $username    = $obj_security->filter_username($username);
        $passhash    = md5($password);

        $obj_connect= new PDO(DSN,DBUSER,DBPASS);

        $qery = "select * from users where username=:username and password=:password";
        $stmt = $obj_connect->prepare($qery);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $passhash);
        $stmt->execute();

        if( !$stmt->fetchAll(PDO::FETCH_ASSOC) ) {
            throw new Exception('Username  or Password is invalid');
        }
        $obj_connect = null;
        return true;
    }
}