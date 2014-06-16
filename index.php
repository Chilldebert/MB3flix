<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
$result = $mysqli->query("SELECT * FROM movies ORDER BY date_added DESC LIMIT 25");
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
include('navbar.php');
echo '<div class="container" style="margin-bottom:5em;">';
		echo '<h3>Latest Movies</h3>';
		echo '<div class="row">';
		while($row=$result->fetch_assoc()){
			if(file_exists('images/movies/'.$row["id"].'/poster_thumb.jpg')){
				echo '<div class="col-xs-3"><a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" alt="'.$row["name"].'"></a></div>';
				}
			else{
				echo '<div class="col-xs-3"><a href="details.php?id='.$row["id"].'"><img src="images/nothumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a></div>';
			}
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











