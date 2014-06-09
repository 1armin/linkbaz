<?php
class User
{
    private $_params;
     
    public function __construct($params)
    {
        $this->_params = $params;
    }

    public function readAction()
    {

        //read the user items
        $user_items = useritem::getItem($this->_params['username'], $this->_params['password']);

        return $user_items;
    }
}