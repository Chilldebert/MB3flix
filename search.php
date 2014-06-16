<?php
error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
include('functions.php');

$search = $_POST['search'];
$genre = $_GET['genre'];
$year = $_GET['year'];
$country = $_GET['country'];
$studio = $_GET['studio'];
$rating = $_GET['rating'];

if (!empty($search)) {
	$result = $mysqli->query("SELECT id,name FROM movies WHERE movies.sort_name LIKE '%$search%' ORDER BY name ASC");
	$result_serie = $mysqli->query("SELECT id,name FROM series WHERE series.sort_name LIKE '%$search%' ORDER BY name ASC");
	$result_people = $mysqli->query("SELECT id,name FROM people WHERE name LIKE '%$search%' ORDER BY name ASC");
	}
else if(!empty($genre)){
	$result = $mysqli->query("SELECT * , movies.name AS name, movies.id AS id
                          FROM movies, movies_to_genres, genres 
                          WHERE movies.id = movies_to_genres.movieid
						  AND genres.id = movies_to_genres.genreid
						  AND genres.name = '$genre'
						  ORDER BY movies.name ASC");
	$result_serie = $mysqli->query("SELECT * , series.name AS name, series.id AS id
                          FROM series, movies_to_genres, genres 
                          WHERE series.id = movies_to_genres.movieid
						  AND genres.id = movies_to_genres.genreid
						  AND genres.name = '$genre'
						  ORDER BY series.name ASC");
}
else if(!empty($studio)){
	$result = $mysqli->query("SELECT id,name FROM movies WHERE movies.studios LIKE '%$studio%' ORDER BY name ASC");
    $result_serie = $mysqli->query("SELECT id,name FROM series WHERE series.studios LIKE '%$studio%' ORDER BY name ASC");
}
else if(!empty($country)){
	$result = $mysqli->query("SELECT id,name FROM movies WHERE movies.production_location LIKE '%$country%' ORDER BY name ASC");
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


echo '<div class="container">';

echo '<div style="margin-bottom:1em;" class="btn-group" id="menu">';
if($result->num_rows > 0){
	echo '<button type="button" class="btn btn-primary" data-id="movies" name="movies">Movies ('.$result->num_rows.')</button>';
}
if($result_serie->num_rows > 0){
	echo '<button type="button" class="btn btn-primary" data-id="series" name="series">TV Shows ('.$result_serie->num_rows.')</button>';
}
if($result_people->num_rows > 0){
	echo '<button type="button" class="btn btn-primary" data-id="people" name="people">People ('.$result_people->num_rows.')</button>';
}
echo '</div>';

//Found Movies
if($result->num_rows > 0){
echo '<div class="pbox" id="movies">';
echo '<div class="row">';
  while($row=$result->fetch_assoc()){
    if(strlen($row["name"])> 16){
	echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.substr($row["name"],0,15).'...'.'</small></div>';
	}
	else echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.$row["name"].'</small></div>';
  }
echo '</div>';
echo '</div>';
}

//Found Series
if($result_serie->num_rows > 0){
echo '<div class="pbox" id="series">';
echo '<div class="row">';
  while($row=$result_serie->fetch_assoc()){
    if(strlen($row["name"])> 16){
	echo '<div class="col-xs-3"> <a href="tvdetails.php?id='.$row["id"].'"><img src="images/series/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.substr($row["name"],0,15).'...'.'</small></div>';
	}
	else echo '<div class="col-xs-3"> <a href="tvdetails.php?id='.$row["id"].'"><img src="images/series/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.$row["name"].'</small></div>';
  }
echo '</div>';
echo '</div>';
}

//Found People
if($result_people->num_rows > 0){
	echo '<div class="pbox" id="people">';
	echo '<div class="row">';
	while($row=$result_people->fetch_assoc()){
		echo '<div class="col-md-2"><a href=person.php?id='.$row["id"].'>'.$row["name"].'</a></div>';
	}
	echo '</div>';
	echo '</div>';
}
//////
echo '</div>';// \\\container\\\\!
?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>
	<!--Hide and Load Additional Details-->
	<script>
	$(document).ready(function () {
		$('.pbox').hide();

		var $mnu = $('#menu').on('click', 'button', function () {
			var $this = $(this), $li = $(this).closest('button');
			if($li.is('.current')){
				return;
			}
			$li.addClass('current');

			$mnu.find('.current').not($li).removeClass('current');
			// fade out all open subcontents
			$('.pbox:visible').hide(0);
			// fade in new selected subcontent
			$('#' + $this.data('id')).show(0);
		});

		$mnu.find('button:first').click();
	});
	</script>
  </body>
</html>


























