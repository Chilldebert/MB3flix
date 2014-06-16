<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
require('functions.php');

$id = $_GET["id"];	
$result = $mysqli->query("SELECT * FROM series WHERE id = '".$id."'") or die(mysql_error());

//Get Seasons
$result_season = $mysqli->query("SELECT id,name FROM seasons WHERE seriesid = '".$id."' AND location_type != 'Virtual' ORDER BY name ASC");

//Get Series Details
while($row=$result->fetch_assoc()){
	if ($row["id"] == $id)
		{
		$id = $row["id"];
		$name = $row["name"];
		$overview = $row["overview"];
		$release_year = $row["release_year"];
		$mpaa_rating = $row["mpaa_rating"];
		$premiere_date = $row["premiere_date"];
		$imdb_url = $row["imdb_url"];
		$tmdb_url = $row["tmdb_url"];
		$tvdb_url = $row["tvdb_url"];
		$zap2it_url = $row["zap2it_url"];
		$path = $row["path"];
		$community_rating = $row["community_rating"];
		$imploded_people = $row["people"];
		$imploded_studios = $row["studios"];
		$status = $row["status"];
		$airtime = $row["airtime"];
		$airdays = $row["airdays"];
		$end_date = $row["end_date"];
		$runtime_ticks = $row["runtime_ticks"];	
		$homepage_url = $row["homepage_url"];
		}
    }
	
//Check End Date
if($end_date == NULL){
	$end_date = "Present";
}	
else{
	$end_date = substr($end_date,0,4);
}

//Exploded People
$exploded_people = explodePeople($imploded_people);
$count_people = count($exploded_people);

//Exploded Studios
$exploded_studios = explodeStudios($imploded_studios);

//Runtime
$runtime_min = convertRunTimeTicks($runtime_ticks);

//Genres
$query = $mysqli->query("SELECT genres.name AS genrename
                          FROM genres, movies_to_genres 
                          WHERE genres.id = movies_to_genres.genreid
						  AND movies_to_genres.movieid = '".$id."'");

$i = 0;
$genrenames = array();

while($obj=$query->fetch_object()){
    
    $genrenames[$i] = $obj->genrename;
    $i++;
  }

  
// //Similar Movies
// $query_similar = $mysqli->query("SELECT movies_similar.similarid AS similar_movies
                          // FROM movies, movies_similar 
                          // WHERE movies.id = movies_similar.movieid
						  // AND movies_similar.movieid = '".$id."'");

// $j = 0;
// $similar_movies = array();

// while($obj=$query_similar->fetch_object()){
    
    // $similar_movies[$j] = $obj->similar_movies;
    // $j++;
  // }
?>

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

div.col-xs-2 {
  height: 200px;
  width: 143px
  border: none; 
  margin-bottom: 1em;
}
div.col-xs-2:hover img{
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

//Container
echo '<div class="container" >';
echo '<div class="col-md-11" >';
echo '<h2>';

//Name and Picture
if(file_exists('images/series/'.$id.'/poster.jpg')){
			echo '<img alt="'.$name.'" src="images/series/'.$id.'/poster.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.' <small>'.substr($premiere_date,0,4).' - '.$end_date.' '.$runtime_min.'min '.$mpaa_rating.'</small>';
		}
		else{
			echo '<img alt="nothumb" src="images/nothumb.jpg" width="200" height="300" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.'';
		}
echo '</h2>';

//IMDb Rating
echo '<div style="margin-bottom:1em">';
echo '<img src="images/star.png" width=16px" height="16px"> '.$community_rating.'';
echo '</div>';

//Genres
echo '<div style="margin-bottom:1em">';
foreach($genrenames as $genrename){
				echo '<a href="search.php?genre='.$genrename.'"><strong>'.$genrename.'</strong></a> / ';
				}
echo '</div>';

//Overview
echo '<div style="height:150px; overflow:auto;margin-bottom:1em;">';
echo '<p>'.$overview.'<br>';
echo '</div>';

echo '<br style="clear:both"></p>';//End

//Additional Details
echo '<div style="margin-bottom:1em;" id="menu"><button type="button" class="btn btn-primary" data-id="details" name="details">Details</button></div>';

//Load Content div
//Details
echo '<div class="pbox" id="details">';
echo '<p>Aired '.$airdays.' at '.$airtime.' on <strong><a href=search.php?studio='.$exploded_studios[0].'>'.$exploded_studios[0].'</a></strong>';
echo '<p>Links: ';
echo '<a href="'.$imdb_url.'" target="_blank"><strong>IMDb</strong></a> / ';
echo '<a href="'.$tmdb_url.'" target="_blank"><strong>TheMovieDb</strong></a> / ';
echo '<a href="'.$homepage_url.'" target="_blank"><strong>Website</strong></a>';
echo '</p></div>';

/////

//Seasons
echo '<div class="well">Seasons</div>';
echo '<div class="row" style="margin-bottom: 1em;">';
while($row=$result_season->fetch_assoc()){
			if(file_exists('images/series/'.$row["id"].'/poster.jpg')){
				echo '<div class="col-xs-2"><a href="season.php?id='.$row["id"].'"><img src="images/series/'.$row["id"].'/poster.jpg"  width="134" height="200" alt="'.$row["name"].'"></a><p>'.$row["name"].'</p></div>';
				}
			else{
				echo '<div class="col-xs-2"><a href="season.php?id='.$row["id"].'"><img src="images/nothumb.jpg" width="134" height="200" alt="'.$row["name"].'"></a><p>'.$row["name"].'</p></div>';
			}
		}
echo '</div>';
//Cast & Crew
echo '<div class="well">Cast & Crew</div>';
echo '<div>';
 $people_i = 0;
		 echo '<div class="row">';
		 while($people_i < $count_people){
			if(file_exists('images/persons/'.$exploded_people[$people_i][1].'/poster.jpg')){
				echo '<div style="margin-bottom:1em;" class="col-md-4"><a href="person.php?id='.$exploded_people[$people_i][1].'"><img src="images/persons/'.$exploded_people[$people_i][1].'/poster.jpg" height="150px" width="100px" align="left"></a><p>'.$exploded_people[$people_i][0].'<br>as '.$exploded_people[$people_i][2].'</p></div>'; 
			}
			else{
				echo '<div style="margin-bottom:1em;" class="col-md-4"><a href="person.php?id='.$exploded_people[$people_i][1].'"><img src="images/nothumb.jpg" height="150px" width="100px" align="left"></a><p>'.$exploded_people[$people_i][0].'<br>as '.$exploded_people[$people_i][2].'</p></div>'; 
			}
			$people_i++;
		 }
		 echo '</div>';
echo '</div>';


// //Similar Movies
// echo '<div class="well">If you like '.$name.', check these out... </div>';
// echo '<div class="row">';
// foreach($similar_movies as $similar_movie) {
		     // echo '<div class="col-xs-2"><a href="details.php?id='.$similar_movie.'"><img src="images/movies/'.$similar_movie.'/poster_thumb.jpg"</a></div>';
		     // }
// echo '</a></div>';
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