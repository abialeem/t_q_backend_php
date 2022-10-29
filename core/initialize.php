<?php

// defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
// defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'xampp'.DS.'htdocs'.DS.'t_q_back' );
// //xampp/htdocs/t_q_back/includes
// defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
// defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'core');

session_start();
//load the config file
require_once('../includes/config.php');




$database = new Database();
$db = $database->connect();


?>