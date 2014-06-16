<?php
error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
require('functions.php');


$result_movie_count = $mysqli->query("SELECT id, name, movie_count FROM people  ORDER BY movie_count DESC LIMIT 0,50");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MB3flix</title>
    <meta name="description" content="">
    <meta name="author" content="">

<style type="text/css"> 
div.col-md-4 {
  float: left;
  height: 150px;
  border: none; 
}
div.col-md-4:hover {
        -moz-box-shadow: 0 0 20px 3px #38c;
        -webkit-box-shadow: 0 0 20px 3px #38c;
        box-shadow: 0 0 20px 3px #38c;
}


</style>	
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  </head>
  
<body>

<?php	
include("navbar.php");

echo '<div class="container">';
echo '<h3>Most Movies</h3>';
echo '<div class="row">';
		 while($row=$result_movie_count->fetch_assoc()){
			if(file_exists('images/persons/'.$row["id"].'/poster.jpg')){
				echo '<div style="margin-bottom:1em;" class="col-md-4"><a href="person.php?id='.$row["id"].'"><img src="images/persons/'.$row["id"].'/poster.jpg" height="150px" width="100px" align="left"></a><p>'.$row["name"].'<br>participated in '.$row["movie_count"].' movies</p></div>'; 
			}
			else{
				echo '<div style="margin-bottom:1em;" class="col-md-4"><a href="person.php?id='.$row["id"].'"><img src="images/nothumb.jpg" height="150px" width="100px" align="left"></a><p>'.$row["name"].'<br>participated in '.$row["movie_count"].' movies</p></div>'; 
			}
		 }
echo '</div>';
echo '</div>';
?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

	

  </body>
</html>











