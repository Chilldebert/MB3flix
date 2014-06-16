MB3flix
===

A web application of your Media Browser 3 library written in PHP / MySQL.
This is just a basic vesion right now, it needs some more optimization to work better!

Based on https://github.com/wayneluke/wayneflix-build

Installation
---
You need
- Server with PHP and MySQL installed.
- Media Browser 3

Insert the SQL located at /scripts/mb3flix.sql to create the tables.

Update /config.php and /scripts/settings.php to match your current database setup.

Now you need to populate your tables. Therefore Media Browser has to be running on your system.
There are 3 different scripts: cachemovies.php, cacheseries.php and cachepeople.php located at /scripts
If you run one of these scripts, the tables will be populated. The scripts then will cache all your movies, series and people into the MySQL tables. If you want to update MB3flix you have to run these scripts again.

Warning: If you got a big library, this will take some time or even exceed the maximum script execution time of your PHP setup.


Screenshots
---

### Landing Page
Landing page shows the latest added movies

![Landing Page](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/01.png)

### IMDb rating
Shows the movies with the highest IMDb ratings

![IMDb rating](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/02.png)

### Genres
Movies and TV shows can be sorted by genres

![Genres](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/03.png)

### TV Shows
MB3flix lists your TV shows with a detailed view of the show

![TV Shows](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/04.png)
![TV Show Details](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/05.png)

### Movie Details
A detailed view of a movie, similar to the original Media Browser 3 application.

![Movie Details](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/06.png)

Similar movies and additional infos, like Awards & Reviews
![Similar Movies & Awards](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/07.png)

### Person Details
A detailed view with informations about the person. Shows in which movies or TV shows the person participated.
![Person Details](https://raw.githubusercontent.com/Chilldebert/MB3flix/master/docs/screenshots/08.png)

To-Do
---

- Security
- User management
- Custom settings
- Clean code
- More sorting features
- Fix/Optimize caching scripts
- Language support
