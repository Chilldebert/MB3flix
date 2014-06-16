<?php
error_reporting(E_ALL ^  E_NOTICE); 
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
include('functions.php');


$result = $mysqli->query("SELECT * FROM series ORDER BY name ASC");
		
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Jokefinger</title>
    <meta name="description" content="">
    <meta name="author" content="">
<style type="text/css">
tr:hover .thumb{
	width: 135px;
	height: 200px;
	visibility:visible;
}
.thumb{
	position:absolute;
	top:0px;
	left:300px;
	width:0px;
	height:0px;
	visibility:hidden;
}	
</style>
    
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

  </head>

  <body>

<?php	
include("navbar.php");

echo '<div class="row">';
  while($row=$result->fetch_assoc()){
	echo '<div class="col-md-4"> <a href="details.php?id='.$row["id"].'<img src="images/series/'.$row["id"].'/landscape.jpg"></div>';

  }
echo '</div>';
?>

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
<script>
jQuery(document).ready(function($) {
      $(".clickableRow").click(function() {
            window.document.location = $(this).attr("href");
      });
});
</script>
  </body>
</html>











