MB3
===

A web application of your Media Browser 3 library written in PHP / MySQL.
This is just a basic vesion right now, it needs some more optimization to work better!

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

Screenshots
---

To-Do
---

- Security
- User management
- Custom settings
- Clean code
- More sorting features
- Fix/Optimize caching scripts
