<?php
header("Content-type:text/html; charset=utf-8"); 
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

echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-xs-3">';
echo '<h3>Update Database</h3>';
echo '<p class="text-danger"><strong>This will take a while!</strong> <br> (Depending on how much movies, series or people you got!)</p>';
echo '<button style="margin-bottom:1em;" type="button" class="btn btn-default" id="movies">Update Movies</button><br>';
echo '<button style="margin-bottom:1em;" type="button" class="btn btn-default"id="series">Update Series</button><br>';
echo '<button style="margin-bottom:1em;" type="button" class="btn btn-default" id="people">Update People</button><br>';
echo '</div>';
echo '<div class="col-xs-8">';
echo '<h3>Output</h3>';
echo '<pre>';
echo '<div id="content" style="overflow:auto; height:500px;">';
echo '<div id="wait" style="display:none;"><img src="images/loader.gif" width="16" height="16" /><br>Updating Database... (This may take a while)</div>';
echo '</div>';
echo '</pre>';
echo '</div>';
echo '</div>';
echo '</div>';

?>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<!--Load Script-->
	<script>
	$(document).ready(function(){
		$(document).ajaxStart(function(){
			$("#wait").css("display","block");
		});
		$(document).ajaxComplete(function(){
			$("#wait").css("display","none");
		});
		$("#movies").click(function(){
			$("#content").load("scripts/cachemovies.php");
		});
		$("#series").click(function(){
			$("#content").load("scripts/cacheseries.php");
		});
		$("#people").click(function(){
			$("#content").load("scripts/cachepeople.php");
		});
	});
	</script>
	

  </body>
</html>











