<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE );

// error_reporting(0); // Turn off all error reporting

session_start();

/**
 * 
 * Database connection configuration
 * 
 */
$dbhost='localhost';	
$dbname='dghs_hrm_main';	
$dbuser='root';
$dbpass='';

mysql_select_db($dbname,mysql_connect($dbhost, $dbuser, $dbpass))or die(mysql_errno());


/**
 * 
 * Application basic configuration
 * 
 */
$app_name  = "DGHS HRM Application";
$site_name = "DGHS HRM";
$copyright = "DGHS";

require_once 'include/functions_app_specific.php';
require_once 'include/functions_generic.php';


