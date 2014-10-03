<?php
header("Content-type:text/html; charset=utf-8"); 
require("config.php");
?>

<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Mediabrowser</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
      <ul class="nav navbar-nav">
	    <li class="dropdown">
              <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Movies <b class="caret"></b></a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="movies.php?c=newest">Newest</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="movies.php?c=hd">HD</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="movies.php?c=imdb">Top IMDB Rating</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="movies.php?c=unseen">Unseen</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="movies.php?c=all">All Movies</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="list.php">Movie List</a></li>
              </ul>
        </li>
        <li><a href="genres.php">Genres</a></li>
        <li><a href="series.php">Series</a></li>
		<li><a href="people.php">People</a></li>
		<li><a href="statistics.php">Statistics</a></li>

      </ul>
      <form action="search.php" class="navbar-form navbar-right" role="search" method="post">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="search">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
	  <ul class="nav navbar-nav navbar-right">
        <li><a href="options.php"><span class="glyphicon glyphicon-cog"></span></a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>