<?php

//SITE_ROOT contain the full path
define('SITE_ROOT', dirname(__FILE__));
// site url
//you can chenge this line according to your directory file
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/linkbaz/');

//api url
define('API_URL', SITE_URL.'api/');

//url redirect section url
define('URL_URL', SITE_URL.'URL/');

//accessories url
define('ACCE_URL', SITE_URL.'acce/');

//URL_DIR contains the full path to the url folder
define('URL_DIR', SITE_ROOT . '/url/');

//INCLUDE_DIR contains the full path to the include folder
define('INCLUDE_DIR', SITE_ROOT . '/include/');

//MODELS_DIR contains the full path to the models folder
define('MODELS_DIR', SITE_ROOT . '/api/models/');

//CONTROLLERS_DIR contains the full path to the controllers folder
define('CONTROLLERS_DIR', SITE_ROOT . '/api/controllers/');



//*****************MySql DataBase Information*******************//

///** MySQL hostname */
define('SERVERNAME', 'localhost');

///** MySQL database port */
define('PORT', '3306');

///** The name of the database */
define('DBNAME', 'linkbaz');

///** MySQL database username */
define('DBUSER', 'root');

///** MySQL database password */
define('DBPASS', '');

///** MySQL database password */
define('DSN', 'mysql:host='.SERVERNAME.';dbname='.DBNAME.';');

