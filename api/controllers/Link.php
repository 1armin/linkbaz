<?php
class Link
{
    private $_params;
     
    public function __construct($params)
    {
        $this->_params = $params;
    }
     
    public function createAction()
    {
        if (!filter_var($this->_params['url'], FILTER_VALIDATE_URL))
        {
            throw new Exception('url is invalid.');
        }
        elseif (empty($this->_params['title']))
        {
            throw new Exception('title is invalid.');
        }
        else
        {
            //create a new link item
            $link              = new linkitem();
            $link->title       = $this->_params['title'];
            $link->description = $this->_params['description'];
            $link->url         = $this->_params['url'];
             
            //pass the user's username and password to authenticate the user
            $link->save($this->_params['username'], $this->_params['password']);
        }
        
    }
     
    public function readAction()
    {
        if (empty($this->_params['id'])) {
        //read the all link items
        $link_items = linkitem::getItems($this->_params['username'], $this->_params['limit']);
        }
        else
        {
        //read the link items
        $link_items = linkitem::getItem($this->_params['username'], $this->_params['id']);
        }
        
        //return the list
        return $link_items;
    }
     
    public function updateAction()
    {
        if (!filter_var($this->_params['url'], FILTER_VALIDATE_URL))
        {
            throw new Exception('url is invalid.');
        }
        elseif (empty($this->_params['title']))
        {
            throw new Exception('title is invalid.');
        }
        else
        {
            //create a new link item
            $link              = new linkitem();
            $link->title       = $this->_params['title'];
            $link->description = $this->_params['description'];
            $link->url         = $this->_params['url'];
             
            //pass the user's username and password to authenticate the user
            $link->update($this->_params['username'], $this->_params['password'], $this->_params['id']);
            //read the link items
            $link_items = linkitem::getItem($this->_params['username'], $this->_params['id']);

            //return the list
            return $link_items;
        }
    }
     
    public function deleteAction()
    {
        //create a new link item
        $link = new linkitem();
        //delete a link item
        $link->delete($this->_params['username'], $this->_params['password'], $this->_params['id']);
    }
}