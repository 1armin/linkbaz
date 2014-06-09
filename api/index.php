<?php
 
// Include utility files
require_once '../config.php';
//include security class file
require_once INCLUDE_DIR.'/security.php';
//include our models
require_once MODELS_DIR.'/linkitem.php';
//include our models
require_once MODELS_DIR.'/useritem.php';

//wrap the whole thing in a try-catch block to catch any wayward exceptions!
try {
    //Define our id-key pairs
    $applications = array(
        'APP001' => '28e336ac6c9423d946ba02d19c6a2632', //randomly generated app key 
    );
    //get the encrypted request
    $enc_request = $_REQUEST['enc_request'];
    
    //get the provided app id
    $app_id = $_REQUEST['app_id'];
    
    //check first if the app id exists in the list of applications
    if( !isset($applications[$app_id]) ) {
        throw new Exception('Application does not exist!');
    }

    //decrypt the request
    $params = json_decode(trim(mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB )));
    
    //check if the request is valid by checking if it's an array and looking for the controller and action
    if( $params == false || isset($params->controller) == false || isset($params->action) == false ) {
        throw new Exception('Request is not valid');
    }

    //cast it into an array
    $params = (array) $params;

    //check params items
    $params['controller']  = (isset($params['controller'])) ? $params['controller'] : null ;
    $params['action']      = (isset($params['action'])) ? $params['action'] : null ;
    $params['title']       = (isset($params['title'])) ? $params['title'] : null ;
    $params['description'] = (isset($params['description'])) ? $params['description'] : null ;
    $params['url']         = (isset($params['url'])) ? $params['url'] : null ;
    $params['limit']       = (isset($params['limit'])) ? $params['limit'] : null ;
    $params['id']          = (isset($params['id'])) ? $params['id'] : null ;
    $params['username']    = (isset($params['username'])) ? $params['username'] : null ;
    $params['password']    = (isset($params['password'])) ? $params['password'] : null ;
    $params['email']       = (isset($params['email'])) ? $params['email'] : null ;
    $params['siteurl']     = (isset($params['siteurl'])) ? $params['siteurl'] : null ;


    $controller = ucfirst(strtolower($params['controller']));
    $action = strtolower($params['action']).'Action';
 
    //check if the controller exists. if not, throw an exception
    if( file_exists(CONTROLLERS_DIR."/{$controller}.php") ) {
        include_once CONTROLLERS_DIR."/{$controller}.php";
    } else {
        throw new Exception('Controller is invalid.');
    }
     
    //create a new instance of the controller, and pass
    //it the parameters from the request
    $controller = new $controller($params);
     
    //check if the action exists in the controller. if not, throw an exception.
    if( method_exists($controller, $action) === false ) {
        throw new Exception('Action is invalid.');
    }
     
    //execute the action
    $result['data'] = $controller->$action();
    $result['success'] = true;
     
} catch( Exception $e ) {
    //catch any exceptions and report the problem
    $result = array();
    $result['success'] = false;
    $result['errormsg'] = $e->getMessage();
}
 
//echo the result of the API call
echo json_encode($result);
exit();