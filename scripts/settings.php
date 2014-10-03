<?php

// MediaBrowser Connection Information.
// Note: We are not doing a complete authentication protocol. 
$serverIP   = "localhost";
$serverPort = "8096";
$userHash   = "3f79fc1c4eceb393a69a06a1b739454c";

// Database Information
$hostname   = "localhost";
$username   = "root";
$password   = "7799225";
$dbname     = "badger-movies";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
