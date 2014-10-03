<?php
error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
include('functions.php');



$result = $mysqli->query("SELECT id,name,date_added,release_year,community_rating FROM movies ORDER BY date_added DESC");
$heading = "Movie List";




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
echo '<div class="col-md-7">';
echo '<h3>'.$heading.'</h3>';
echo '<table class="table" name="list" id="list">';
echo '<thead>';
echo '<tr>';
echo '<th>Name</th>';
echo '<th>Year</th>';
echo '<th>IMDb Rating</th>';
echo '<th>Date Added</th>';
echo '</thead>';
echo '<tbody>';
$count = 0;
  while($row=$result->fetch_assoc()){
    echo '<tr>';
	echo '<td><a href="details.php?id='.$row["id"].'">'.$row["name"].'</a></td>';
	echo '<td>'.$row["release_year"].'</td>';
	echo '<td>'.$row["community_rating"].'</td>';
	echo '<td>'.substr($row["date_added"],0,10).'</td>';
	echo '</tr>';
	$count++;
  }
echo '</tbody>';
echo '</table>';
echo '<div align="center"><strong>'.$count.' movies found</strong></div>';
echo '</div>';
echo '</div>';//container
?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>
	
	<script>
	$(document).ready(function() 
		{ 
			$("#list").tablesorter(); 
		} 
	); 
    </script>
	
  </body>
</html>











