<?php
//error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
//include('functions.php');

$id = $_GET["id"];	
//Get Season id,name
$result_season = $mysqli->query("SELECT id,name FROM seasons WHERE id='$id'");
while($row=$result_season->fetch_assoc()){
	if ($row["id"] == $id){
		$id = $row["id"];
		$name =$row["name"];
	}
}
//Get Episodes for Seasons
$result_episodes = $mysqli->query("SELECT * FROM episodes WHERE seasonid = '$id' AND location_type != 'Virtual' ORDER BY episode_nr ASC");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MB3flix</title>
    <meta name="description" content="">
    <meta name="author" content="">
    
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

  </head>

  <body>

<?php	
include("navbar.php");
echo '<div class="container">';
echo '<div class="col-md-11" >';
echo '<h2>';

//Name and Picture
if(file_exists('images/series/'.$id.'/poster.jpg')){
			echo '<img alt="'.$name.'" src="images/series/'.$id.'/poster.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.' ';
		}
		else{
			echo '<img alt="nothumb" src="images/nothumb.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.'';
		}
echo '</h2>';
echo '<br style="clear:both"></p>';//End
//Episodes
echo '<div class="well">Episodes</div>';
while($row=$result_episodes->fetch_assoc()){
	echo '<div>'.$row["season_nr"].' x '.$row["episode_nr"].' - '.$row["name"].'</div>';

}
echo '</div>'; 
echo '</div>'; 
?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>

  </body>
</html>











