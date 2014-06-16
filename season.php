<?php
//error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
//include('functions.php');

$id = $_GET["id"];	
$result_episodes = $mysqli->query("SELECT * FROM episodes WHERE seasonid = '$id' AND location_type != 'Virtual' ORDER BY episode_nr ASC");
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Jokefinger</title>
    <meta name="description" content="">
    <meta name="author" content="">
    
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

  </head>

  <body>

<?php	
include("navbar.php");

while($row=$result_episodes->fetch_assoc()){
	echo '<br>Season: '.$row["season_nr"].' Episode: '.$row["episode_nr"].' Name: '.$row["name"].' Id: '.$row["id"].'';

} 
?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>

  </body>
</html>











