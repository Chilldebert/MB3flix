<?php
error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
include('functions.php');

$selector = $_GET["c"];


	if ($selector == "all" ) {
		$result = $mysqli->query("SELECT id,name FROM movies ORDER BY name ASC");
		}
	elseif($selector == "newest") {
		$result = $mysqli->query("SELECT id,name FROM movies ORDER BY date_added DESC LIMIT 100");
		}
	elseif($selector == "imdb") {
		$result = $mysqli->query("SELECT id,name FROM movies ORDER BY community_rating DESC LIMIT 250");
		}
	elseif ($selector == "hd"){
		$result = $mysqli->query("SELECT id,name FROM movies WHERE hd = '1' ORDER BY name ASC");
		}




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MB3flix</title>
    <meta name="description" content="">
    <meta name="author" content="">

<style type="text/css"> 
div.col-xs-3 {
  float: left;
  width: 134px;
  height: 200px;
  margin-bottom: 2em;
  margin-right: 1em;
  border: none; 


}
div.col-xs-3:hover img{
          -moz-box-shadow: 0 0 20px 3px #38c;
        -webkit-box-shadow: 0 0 20px 3px #38c;
        box-shadow: 0 0 20px 3px #38c;

}
div.col-xs-3:hover img {
	
}

div.col-xs-3 small {
  text-align: center;
  text-indent: 0;
} 
</style>
    
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

  </head>

  <body>

<?php	
include("navbar.php");

// $alphabet=65;
// while($alphabet<=90){
	// echo '<a href="movies.php?c='.chr($alphabet).'&genre='.$genre.'">'.chr($alphabet).' </a>';
	// $alphabet++;
// }
echo '<div class="container">';
echo '<div class="row">';
  while($row=$result->fetch_assoc()){
    if(strlen($row["name"])> 16){
	echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.substr($row["name"],0,15).'...'.'</small></div>';
	}
	else echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.$row["name"].'</small></div>';
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











