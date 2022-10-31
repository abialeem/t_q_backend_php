<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'Applications'.DS.'XAMPP'.DS.'xamppfiles'.DS.'htdocs'.DS.'t_q_back' );
defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'core');

session_start();
//load the config file
require_once(INC_PATH.DS.'config.php');



// echo SITE_ROOT.'<br>';
// echo INC_PATH.'<br>';
// echo CORE_PATH;


$database = new Database();
$db = $database->connect();


?>
