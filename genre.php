<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
$genre = $_GET["genre"];
$query = mysql_query("SELECT movies.id AS id, movies.name AS name, movies.date_added, movies.release_year
                          FROM movies, movies_to_genres, genres 
                          WHERE movies.id = movies_to_genres.movieid
						  AND genres.id = movies_to_genres.genreid
						  AND genres.name = '".$genre."'");
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
 ?>

<!-- The table -->
<table class="table table-striped table-bordered table-hover table-condensed"id="movietable">
    <thead>
        <tr>
		    <th> </th>
            <th>ID </th>
            <th>Name </th>
			<th>Date added </th>
            <th>Release Year </i></th>
        </tr>
    </thead>
    <tbody>

<?php

  while($row=mysql_fetch_object($query)){
  
    echo '<tr>'."\r\n";

	echo '<td><a href="details.php?id='.$row->id.'"><img src="images/movies/'.$row->id.'/poster_thumb.jpg"</a></td>'."\r\n";
    echo '<td>'.$row->id.'</td>'."\r\n";
    echo '<td>'.$row->name.'</td>'."\r\n";
	echo '<td>'.$row->date_added.'</td>'."\r\n";
    echo '<td>'.$row->release_year.'</td>'."\r\n";

    echo '<tr>'."\r\n";
  }

?>
    </tbody>
</table>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>
	
	<!-- Table sorter -->
	<script>
	$(document).ready(function(){
	$(function(){
	$("#movietable").tablesorter();
	});
	});
    </script>
  </body>
</html>











