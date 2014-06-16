<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
require('functions.php');

$id = $_GET["id"];	
$result = $mysqli->query("SELECT * FROM people WHERE id = '".$id."'") or die(mysql_error());

//Get Movies 
$movie_query = "SELECT id, name FROM movies WHERE people LIKE '%$id%'";
$movie_result = $mysqli->query($movie_query);

//Get Series
$serie_query = "SELECT id, name FROM series WHERE people LIKE '%$id%'";
$serie_result = $mysqli->query($serie_query);

function mysqli_result($res, $row, $field=0) {
	    $res->data_seek($row);
	    $datarow = $res->fetch_array();
	    return $datarow[$field];
	} 


//Get People Details
while($row=$result->fetch_assoc()){
	if ($row["id"] == $id)
		{
		 $name = $row["name"];
		 $birthdate = $row["birthdate"];
		 $deathdate = $row["deathdate"];
		 $overview = $row["overview"];
		 $imdb_url = $row["imdb_url"];
		 $tmdb_url = $row["tmdb_url"];
		 $birthplace = $row["birth_place"];

		}
    }
	
	
$imdb_average_fetch = $mysqli->query("SELECT AVG(community_rating) FROM movies WHERE people LIKE '%$name%'");
$imdb_average = round(mysqli_result($imdb_average_fetch, 0), 2);



//Adjust Birth and Deathdate
$birthday1 = str_replace('-', '/', substr($birthdate,0,10));
$birthday_final = date('Y-m-d',strtotime($birthday1 . "+1 days"));
$deathday1 = str_replace('-', '/', substr($deathdate,0,10));
$deathday_final = date('Y-m-d',strtotime($deathday1 . "+1 days"));
//Get Age
$current_time = date('Y-m-d');
if (!empty($deathdate)){
	$age = $deathday_final - $birthday_final;
	}
else{
	$age = $current_time - $birthday_final;
	}
?>

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
}
div.col-xs-3:hover img{
    -moz-box-shadow: 0 0 20px 3px #38c;
    -webkit-box-shadow: 0 0 20px 3px #38c;
    box-shadow: 0 0 20px 3px #38c;
}
div.movies p {
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
echo '<div class="col-md-11">';
echo '<h2>';
//Name and Picture
if(file_exists('images/persons/'.$id.'/poster.jpg')){
			echo '<img alt="'.$name.'" src="images/persons/'.$id.'/poster.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.'';
		}
		else{
			echo '<img alt="nothumb" src="images/nothumb.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.'';
		}
echo '</h2>';
echo '<div style="height:150px; overflow:auto;margin-bottom:1em;">';
//Overview
echo '<p>'.$overview.'<br>';
echo '</div>';
//Born, Birthplace, Links
if($deathdate != NULL){
	echo '<div style="margin-bottom:1em;">Born: '.$birthday_final.' Died: '.$deathday_final.' (Age: '.$age.')<br></div>';
}
else{
	echo '<div style="margin-bottom:1em;">Born: '.$birthday_final.' (Age: '.$age.')<br></div>';
}
echo '<div style="margin-bottom:1em;">Birthplace: <a href=https://maps.google.com/maps?q='.str_replace(" ", "+",$birthplace).' target="_blank">'.$birthplace.'</a><br></div>';
echo '<div style="margin-bottom:1em;">Links: <a href='.$imdb_url.' target="_blank">IMDb</a> / <a href='.$tmdb_url.' target="_blank">TheMovieDb</a></div>';
echo '<br style="clear:both"></p>';


//Additonal Things
echo '<div style="margin-bottom:1em;vertical-align:middle; " class="btn-group" id="menu"><button type="button" class="btn btn-primary" data-id="movies" name="movies">Movies ('.$movie_result->num_rows.')</button><button type="button" class="btn btn-primary" data-id="series" name="series">TV Shows ('.$serie_result->num_rows.')</button><button type="button" class="btn btn-primary" data-id="additional" name="additional">Additional Infos</button></div>';
//Participated Movies
echo '<div class="pbox" id="movies" style="margin:3em">';
echo '<div class="row">';
while($row=$movie_result->fetch_assoc()){
    if(strlen($row["name"])> 16){
	echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.substr($row["name"],0,15).'...'.'</small></div>';
	}
	else echo '<div class="col-xs-3"> <a href="details.php?id='.$row["id"].'"><img src="images/movies/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.$row["name"].'</small></div>';
}
echo '</div>';
echo '</div>';
//Participated Series 
echo '<div class="pbox" id="series" style="margin:3em">';
echo '<div class="row">';
while($row=$serie_result->fetch_assoc()){
    if(strlen($row["name"])> 16){
	echo '<div class="col-xs-3"> <a href="tvdetails.php?id='.$row["id"].'"><img src="images/series/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.substr($row["name"],0,15).'...'.'</small></div>';
	}
	else echo '<div class="col-xs-3"> <a href="tvdetails.php?id='.$row["id"].'"><img src="images/series/'.$row["id"].'/poster_thumb.jpg" width="134px" height="200px" alt="'.$row["name"].'"></a><small>'.$row["name"].'</small></div>';
}
echo '</div>';
echo '</div>';
//Additional Infos
echo '<div class="pbox" id="additional" style="margin:3em">';
echo '<strong>Average IMDb Rating: </strong>'.$imdb_average.'';
echo '</div>';
echo '</div>';//col-md-11

echo '</div>';//container

		

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