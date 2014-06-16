<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
require('functions.php');

$id = $_GET["id"];	
$result = $mysqli->query("SELECT * FROM movies WHERE id = '".$id."'") or die(mysql_error());

//Get Movie Details
while($row=$result->fetch_assoc()){
	if ($row["id"] == $id)
		{
		 $name = $row["name"];
		 $overview = $row["overview"];
		 $critic_rating = $row["critic_rating"];
		 $community_rating = $row["community_rating"];
		 $metascore = $row["metascore"];
		 $award_summary = $row["award_summary"];
		 $keywords = $row["keywords"];
		 $mpaa_rating = $row["mpaa_rating"];
		 $runtime_ticks = $row["runtime_ticks"];
		 $release_year = $row["release_year"];
		 $imdb_url = $row["imdb_url"];
		 $critic_rating_summary = $row["critic_rating_summary"];
		 $offline_path = $row["offline_path"];
		 $tmdb_url = $row["tmdb_url"];
		 $size = $row["size"];
		 $container = $row["container"];
		 $remote_trailer = $row["remote_trailer"];
		 $taglines = $row["taglines"];
		 $homepage_url = $row["homepage_url"];
		 $production_locations = $row["production_location"];
		 $imploded_people = $row["people"];
		 $imploded_studios = $row["studios"];
		 $serilialized_media_streams = $row["media_streams"];
		 $imploded_media_name = $row["media_name"];
		}
    }
//Unserilialize Media Streams
$media_streams = unserialize($serilialized_media_streams);
$count_streams = count($media_streams);

//Explode Media Name
$media_names = explodeMediaName($imploded_media_name);

//Exploded People
$exploded_people = explodePeople($imploded_people);
$count_people = count($exploded_people);

//Exploded Studios
$exploded_studios = explodeStudios($imploded_studios);

//Explode Production Locations
$production_location = explode(":: ",$production_locations);
	
//Remote Trailer Substring
if($remote_trailer != NULL) {
	$remote_trailer_sub = substr(strrchr($remote_trailer, "="), 1);
}
	
//Runtime
$runtime_min = convertRunTimeTicks($runtime_ticks);

//Gigabytes
$gigabytes = convertBytes($size);

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

  
//Similar Movies
$query_similar = $mysqli->query("SELECT movies_similar.similarid AS similar_movies
                          FROM movies, movies_similar 
                          WHERE movies.id = movies_similar.movieid
						  AND movies_similar.movieid = '".$id."'");

$j = 0;
$similar_movies = array();

while($obj=$query_similar->fetch_object()){
    
    $similar_movies[$j] = $obj->similar_movies;
    $j++;
  }
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

</style>

  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

  </head>

<body>
<?php
//echo '<body style="background: url(images/movies/'.$id.'/backdrop.jpg) no-repeat center center fixed; -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover; background-size: cover;">';
include("navbar.php");

//echo '<div style="position:relative;z-index:1;"><img src="images/movies/'.$id.'/backdrop.jpg" width="100%" height="100%"></div>';
//echo '<div z-index:2; background-image:url(images/bg.png); width:100%; height:320px; background-repeat:repeat-x;"></div>';
echo '<div class="container" style="margin-bottom:5em;" >';
echo '<div class="col-md-11" >';
echo '<h2>';

//Name and Picture
if(file_exists('images/movies/'.$id.'/poster.jpg')){
			echo '<img alt="'.$name.'" src="images/movies/'.$id.'/poster.jpg" width="200" height="300" data-toggle="modal" data-target=".bs-example-modal-lg" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.' <small>'.$release_year.' '.$runtime_min.'min '.$mpaa_rating.'</small>';
		}
		else{
			echo '<img alt="nothumb" src="images/nothumb.jpg" width="200" height="300" data-toggle="modal" data-target=".bs-example-modal-lg" style="box-shadow:10px 10px 10px black; float:left; margin-right:20px; margin-bottom:10px">'.$name.'';
		}
echo '</h2>';

//IMDb etc. Ratings
echo '<div style="margin-bottom:1em">';
echo '<img src="images/star.png" width=16px" height="16px"> '.$community_rating.'';
if($critic_rating != NULL){
	if($critic_rating >= 60){
		echo ' <img src="images/fresh.png" width=16px" height="16px"> '.$critic_rating.'%';
	}
	else {
	 echo ' <img src="images/rotten.png" width=16px" height="16px"> '.$critic_rating.'%';
	}
}
if($metascore != NULL){
	echo ' <span class="badge alert-success">'.$metascore.'</span>';
}
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

//Media Flags
echo '<div class="row">';
echo '<div class="col-md-7">';
foreach($media_names as $media_name){
				if(file_exists('images/flags/'.$media_name.'.png')){
					echo '<img src="images/flags/'.$media_name.'.png" width="70px">  ';
				}
				else{
					echo '<img src="images/flags/default.png" width="7%" height="7%">';
				}
			}
echo '</div>';
//Trailer
if($remote_trailer != NULL){
	echo '<div class="col-md-2"><form ><input type="button" class="btn btn-primary" value="Trailer" onClick="window.open(\''.$remote_trailer.'\')"></form></div>';
}
echo '</div>';
/////
echo '<br style="clear:both"></p>';//End

 
//Additional Details
echo '<div style="margin-bottom:1em;" class="btn-group" id="menu"><button type="button" class="btn btn-primary" data-id="details" name="details">Details</button><button type="button" class="btn btn-primary" data-id="mediainfo" name="mediainfo">Media Info</button><button type="button" class="btn btn-primary" data-id="tags" name="tags">Tags</button></div>';

//Load Content div
//Details
echo '<div class="pbox" id="details">';
echo '<p>Links: ';
echo '<a href="'.$imdb_url.'" target="_blank"><strong>IMDb</strong></a> / ';
echo '<a href="'.$tmdb_url.'" target="_blank"><strong>TheMovieDb</strong></a> / ';
echo '<a href="'.$homepage_url.'" target="_blank"><strong>Website</strong></a>';
echo '</p></div>';

//Media Info
echo '<div class="pbox" id="mediainfo">';
echo '<div class="row"><small>';
		$x = 0;
		while ($x < $count_streams){
			if($media_streams[$x]['Type'] != "Subtitle"){
				echo '<div  class="col-md-3">';
				echo '<strong>'.$media_streams[$x]['Type'].'</strong>';
				echo '<dl class="dl-horizontal">';
					foreach ($media_streams[$x] as $stat => $value){
						if($value != ""){
							echo '<dt>'.$stat.'</dt>';
							echo '<dd>'.$value.'<dd>';
						}
					}
				echo '</div>';
			}
			$x++;
		}
		echo '</div>';
			echo '<div>';
				echo '<strong>File</strong>';
				echo '<dl class="dl-horizontal">';
				echo '<dt>Size</dt>';
				echo '<dd>'.$gigabytes.' GB</dd>';
				echo '<dt>Container</dt>';
				echo '<dd>'.$container.'</dd>';
				echo '<dt>Path</dt>';
				echo '<dd>'.$offline_path.'</dd>';
			echo '</div>';
echo '</small></div>';

//Tags
echo '<div class="pbox" id="tags">';
echo '<p>Studios: ';
foreach($exploded_studios as $studio){
				echo '<a href="search.php?studio='.$studio.'"><strong>'.$studio.'</strong></a> / ';
				}
echo '</p>';
echo '<p>Country: ';
foreach($production_location as $productionlocation){
				echo '<a href="search.php?country='.$productionlocation.'"><strong>'.$productionlocation.'</strong></a> / ';
				}
echo '</p>';
echo '</div>';

/////

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


//Similar Movies
echo '<div class="well">If you like '.$name.', check these out... </div>';
echo '<div class="row">';
foreach($similar_movies as $similar_movie) {
		     echo '<div class="col-xs-3"> <a href="details.php?id='.$similar_movie.'"><img src="images/movies/'.$similar_movie.'/poster_thumb.jpg" width="134px" height="200px"></a></div>';
		     }
echo '</div>';


//Awards & Reviews
echo '<div class="well">Awards & Reviews</div>';
echo '<p>'.$award_summary.'</p>';
		 if($critic_rating_summary != NULL){
			if ($critic_rating >= 60) {
				echo '<div class="panel panel-warning">
				<div class="panel-heading">
							<h3 class="panel-title"><strong>TOMATOMETER</strong></h3>
							<strong><img src="images/fresh.png"> '.$critic_rating.' %</strong><br>
						
							'.$critic_rating_summary.'
						</div>
					</div>';
			}
			else {
				echo '<div class="panel panel-warning">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>TOMATOMETER</strong></h3>
							<strong><img src="images/rotten.png"> '.$critic_rating.' %</strong>
						</div>
						<div class="panel-body">
							'.$critic_rating_summary.'
						</div>
					</div>';
			}
		}


echo '</div>';//col-md-11
echo '</div>';//container


 ?>
<!-- Modal Popup -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			<?php
			echo '<img src="images/movies/'.$id.'/poster.jpg"  class="img-responsive">';
			?>
			</div>
		</div>
	</div>
</div>	
 
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