<?php
class linkitem
{
    public $title;
    public $description;
    public $url;




    public static function getItem($username, $id)
    {
        $link_items  = array();

        $obj_security= new security();
        $username    = $obj_security->filter_username($username);
        $id          = $obj_security->filter_num($id);

        $obj_connect = new PDO(DSN,DBUSER,DBPASS);
        $qery        = "select * from link where id=:id and username=:username";
        $stmt        = $obj_connect->prepare($qery);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        if( !$link_items = $stmt->fetchAll(PDO::FETCH_ASSOC) ) {
            throw new Exception('id is invalid.');
        }

        $obj_connect = null;
        
        return $link_items["0"];
    }





    public static function getItems($username, $limit)
    {
        $link_items  = array();

        $obj_security= new security();
        $username    = $obj_security->filter_username($username);
        $limit       = $obj_security->filter_limit($limit);
        $limit       = (empty($limit)) ? 10 : $limit ;

        $obj_connect = new PDO(DSN,DBUSER,DBPASS);
        $qery        = "select * from users where username=:username";
        $stmt        = $obj_connect->prepare($qery);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        if( !$stmt->fetchAll() ) {
            throw new Exception('username is invalid.');
        }
        else
        {
            $obj_connect = null;
            $obj_connect = new PDO(DSN,DBUSER,DBPASS);
            $qery        = "select * from link where username=:username order by id desc limit $limit";
            $stmt        = $obj_connect->prepare($qery);
            $stmt->bindValue(':username', $username);
            $stmt->execute();

            if ( !$link_items = $stmt->fetchAll(PDO::FETCH_ASSOC) ) {
                throw new Exception('no link available.');
            }

            $obj_connect = null;
        }


        return $link_items;
    }





    public function save($username, $password)
    {
        self::_checkIfUserExists($username, $password);

        $obj_security= new security();
        $username    = $obj_security->filter_username($username);
        $title       = $obj_security->filter_text($this->title);
        $description = $obj_security->filter_text($this->description);
        $url         = $obj_security->filter_url($this->url);

        $date        = date("y/m/d");

        $obj_connect = new PDO(DSN,DBUSER,DBPASS);

        $qutitle       = $obj_connect->quote($title);
        $qudescription = $obj_connect->quote($description);
        $quurl         = $obj_connect->quote($url);
        $qudate        = $obj_connect->quote($date);
        $quusername    = $obj_connect->quote($username);

        $obj_connect->exec("INSERT INTO link (title,description,url,date,username) VALUES ($qutitle,$qudescription,$quurl,$qudate,$quusername)");

        $obj_connect = null;
    }





public function update($username, $password, $id)
    {
        self::_checkIfUserExists($username, $password);

        $obj_security  = new security();
        $title         = $obj_security->filter_text($this->title);
        $description   = $obj_security->filter_text($this->description);
        $url           = $obj_security->filter_url($this->url);
        $username      = $obj_security->filter_username($username);
        $id            = $obj_security->filter_num($id);

        $obj_connect   = new PDO(DSN,DBUSER,DBPASS);

        $qery        = "select * from link where id=:id and username=:username";
        $stmt        = $obj_connect->prepare($qery);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        if( !$stmt->fetchAll() ) {
            throw new Exception('id is invalid.');
        }

        $qutitle       = $obj_connect->quote($title);
        $qudescription = $obj_connect->quote($description);
        $quurl         = $obj_connect->quote($url);
        $quid          = $obj_connect->quote($id);

        $obj_connect->exec("UPDATE link SET title=$qutitle WHERE id=$quid");
        $obj_connect->exec("UPDATE link SET description=$qudescription WHERE id=$quid");
        $obj_connect->exec("UPDATE link SET url=$quurl WHERE id=$quid");
        
        $obj_connect = null;
        
        return true;
    }




    public function delete($username, $password, $id)
    {
        self::_checkIfUserExists($username, $password);

        $obj_security  = new security();
        $id            = $obj_security->filter_num($id);
        $username      = $obj_security->filter_username($username);

        $obj_connect = new PDO(DSN,DBUSER,DBPASS);
        $qery        = "select * from link where id=:id and username=:username";
        $stmt        = $obj_connect->prepare($qery);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        if( !$stmt->fetchAll() ) {
            throw new Exception('id is invalid.');
        }

        $quid  = $obj_connect->quote($id);
        $obj_connect->exec("DELETE FROM link WHERE id=$quid");

        return true;
    }



    private static function _checkIfUserExists($username, $password)
    {
        $obj_security= new security();
        $username    = $obj_security->filter_username($username);
        $password    = $obj_security->filter_md5code($password);

        $obj_connect= new PDO(DSN,DBUSER,DBPASS);

        $qery = "select * from users where username=:username and password=:password";
        $stmt = $obj_connect->prepare($qery);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->execute();

        if( !$stmt->fetchAll(PDO::FETCH_ASSOC) ) {
            throw new Exception('Username  or Password is invalid');
        }
        $obj_connect = null;
        return true;
    }
}