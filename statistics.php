<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
require('functions.php');

$result = $mysqli->query("SELECT * FROM movies");
$result_genres = $mysqli->query("SELECT * FROM genres");
$result_people = $mysqli->query("SELECT * FROM people");

//Number of Movies
$number_of_movies = $result->num_rows;

//Number of Genres
$number_of_genres = $result_genres->num_rows;

//Number of People
$number_of_people = $result_people->num_rows;
$i = 0;
while($row=$result_genres->fetch_assoc()){
	$genre_name[$i] = $row["name"];
	$genre_count[$i] = $row["count"];
	$i++;
    }

$total_runtime = 0;
$total_runtime_result = $mysqli->query("SELECT runtime_ticks FROM movies");
while($row=$total_runtime_result->fetch_assoc()){
	$movies_runtime[$i] = $row["runtime_ticks"];
	$i++;
    }
foreach($movies_runtime as $movie_runtime) {
		     $total_runtime = $total_runtime + $movie_runtime;
		     }
//Total Movie Runtime
$total_runtime_min = convertRunTimeTicks($total_runtime);
$total_runtime_hour = round(($total_runtime_min / 60), 2);
//Average Movie Runtime
$avg_runtime = round($total_runtime_min / $number_of_movies, 2);

//Number of Movies after language
$movie_language_result = $mysqli->query("SELECT audio_language FROM movies");
$movies_language_eng = 0;
$movies_language_ger = 0;
$movies_language_unk = 0;
while($row=$movie_language_result->fetch_assoc()){
	if($row["audio_language"] == "eng"){
		$movies_language_eng++; 
	}
	elseif($row["audio_language"] == "deu" OR $row["audio_language"] == "de" OR $row["audio_language"] == "ger"){
		$movies_language_ger++; 
	}
	else {
	$movies_language_unk++;
	}
}
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

echo '<dl class="dl-horizontal">';
echo '<dt>Total Movies</dt>';
echo '<dd>'.$number_of_movies.'</dd>';
echo '<dt>Number of Genres</dt>';
echo '<dd>'.$number_of_genres.'</dd>';
echo '<dt>Number of People</dt>';
echo '<dd>'.$number_of_people.'</dd>';
echo '<dt>Total Runtime</dt>';
echo '<dd>'.$total_runtime_hour.' h</dd>';
echo '<dt>Average Runtime</dt>';
echo '<dd>'.$avg_runtime.' min</dd>';
echo '<dt>English Movies</dt>';
echo '<dd>'.$movies_language_eng.'</dd>';
echo '<dt>German Movies</dt>';
echo '<dd>'.$movies_language_ger.'</dd>';
echo '<dt>Language Unknown</dt>';
echo '<dd>'.$movies_language_unk.'</dd>';
echo '</dl>';

 ?>

 


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

	

  </body>
</html>











