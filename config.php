<?php
//Hostname, User, Password and Database
$hostname = "";
$user = "";
$password = "";
$database = "";
 
//set up mysql connection
$mysqli = new mysqli($hostname, $user, $password) or die(mysql_error());
//select database
$mysqli->select_db($database) or die(mysql_error());
$mysqli->query("SET NAMES 'utf8'"); 

?>