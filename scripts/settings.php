<?php

// MediaBrowser Connection Information.
// Note: We are not doing a complete authentication protocol. 
$serverIP   = "";
$serverPort = "8096";
$userHash   = "";

// Database Information
$hostname   = "localhost";
$username   = "";
$password   = "";
$dbname     = "";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
