<?php
header("Content-type:text/html; charset=utf-8"); 
require('config.php');
$result = mysql_query("SELECT * FROM studios") or die(mysql_error());
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
 ?>

<!-- The table -->
<table class="table table-striped table-bordered table-hover table-condensed table-responsive"id="movietable">
    <thead>
        <tr>
		    <th> </th>
            <th>ID </th>
            <th>Name </th>
			<th>Count </th>
        </tr>
    </thead>
    <tbody>
  <colgroup>
    <col width="134">
    <col width="100">
    <col width="500">
	<col width="320">
  </colgroup>
<?php

  while($row=mysql_fetch_object($result)){
    
	echo '<tr>'."\r\n";
    echo '<td><a href="movies.php?c=all&genre=all&studio='.$row->name.'"><img src="images/studios/'.$row->id.'/poster.jpg" height="200"</a></td>'."\r\n";
    echo '<td>'.$row->id.'</td>'."\r\n";
    echo '<td>'.$row->name.'</td>'."\r\n";
	echo '<td>'.$row->count.'</td>'."\r\n";
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











