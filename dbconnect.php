<?php 
error_reporting(E_ERROR | E_PARSE);
define('DBHOST', 'localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','farmermarket');

$conn = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);

if(!$conn){
	die("Connection failed: " .mysqli_error());
}