<?php
header("Content-type:text/html; charset=utf-8"); 
// require('config.php');
// $result = mysql_query("SELECT * FROM movies ORDER BY date_added DESC LIMIT 10");
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
include('navbar.php');
?>
  <div class="col-md-4 col-md-offset-4">
    <form class="form-signin" role="form" action="user.php" method="post">
       <h2 class="form-signin-heading">Login</h2>
       <input name ="username" type="text" class="form-control" placeholder="Name" required autofocus>
       <input name ="password" type="password" class="form-control" placeholder="Password" required>
       <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
</div>








    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>


  </body>
</html>











