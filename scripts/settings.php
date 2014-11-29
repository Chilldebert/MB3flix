<?php

// MediaBrowser Connection Information.
$serverIP   = "";									// Your MB3 server IP
$serverPort = "8096";								// Port
$userHash   = "";									// User hash (if you are on your profile in the webclient, you can see the hash in the url)
$user = "";											// MB3 Server username
$pass = "";											// MB3 Server user passwort

// Additional settings
$limit = 4;  										// Similar Movies /Series to retrieve (the more, the slower)
$movielimit = 100; 									// Movies to retrieve when updating (Sort by Date added)

// Database Information
$hostname   = "localhost";							// adress of the MYSQL DB (normally it's localhost)
$username   = "";									// username for MYSQL
$password   = "";									// password for MYSQL
$dbname     = "";									// Database name in MYSQL

#########################DO NOT EDIT BEYOND THIS LINE#########################
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

// Functions
function mb3getdata($dataurl, $resp ){
// Send Auth header
$URL = $dataurl;
 
$opts2 = array('http' =>
    array(
        'header'  => 'Content-type: application/x-www-form-urlencoded',
		'header'  => 'Authorization=MediaBrowser UserId="'. $userHash.'", Client="PHP", Device="PHP DataBase Script", DeviceId="xxx", Version="1.0.0.0"',
		'header'  => 'X-MediaBrowser-Token: '.$resp['AccessToken'],
        'method'  => 'GET',
        'ignore_errors' => true,
    )
);
$context  = stream_context_create($opts2);
$result2 = file_get_contents($URL, false, $context);
$result3 = json_decode($result2,true);
 
 
return $result2;
}

////////////////////////////////////////////////////// 

 
// Logon
session_start();
// Do Logon
$URL = "http://".$serverIP.":".$serverPort."/mediabrowser/Users/AuthenticateByName?format=json";
 
 
$postdata = http_build_query(
    array(
        'username' => $user,
        'Password' => sha1($pass),
        'PasswordMd5' => md5($pass),
    )
);
 
$opts = array('http' =>
    array(
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'method'  => 'POST',
        'content' => $postdata,
        'ignore_errors' => true,
    )
);
 
$context  = stream_context_create($opts);
$result = file_get_contents($URL, false, $context);
 
$resp = (array) json_decode($result,false);
 
//////////////////////////////////////////////

