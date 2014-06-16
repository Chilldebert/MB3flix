<?php

// MediaBrowser Connection Information.
// Note: We are not doing a complete authentication protocol. 
$serverIP   = "localhost";
$serverPort = "8096";
$userHash   = "";

// Database Information
$hostname   = "";
$username   = "";
$password   = "";
$dbname     = "";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
