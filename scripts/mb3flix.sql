SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `episodes` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `sort_name` varchar(100) NOT NULL DEFAULT '0',
  `release_year` varchar(50) DEFAULT NULL,
  `mpaa_rating` varchar(50) DEFAULT NULL,
  `overview` varchar(2500) DEFAULT NULL,
  `virtual` smallint(6) DEFAULT '0',
  `hd` smallint(6) DEFAULT '0',
  `similar` varchar(600) DEFAULT NULL,
  `season_nr` smallint(2) DEFAULT NULL,
  `episode_nr` smallint(2) DEFAULT NULL,
  `seriesid` varchar(100) DEFAULT NULL,
  `seasonid` varchar(100) DEFAULT NULL,
  `location_type` varchar(20) DEFAULT NULL,
  UNIQUE KEY `mediabrowserid` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `genres` (
  `id` varchar(100) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '0',
  `count` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `movies` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `sort_name` varchar(100) NOT NULL DEFAULT '0',
  `release_year` varchar(50) DEFAULT NULL,
  `mpaa_rating` varchar(50) DEFAULT NULL,
  `overview` varchar(2500) DEFAULT NULL,
  `virtual` smallint(6) DEFAULT '0',
  `hd` smallint(6) DEFAULT '0',
  `similar` varchar(600) DEFAULT NULL,
  `critic_rating` smallint(6) DEFAULT NULL,
  `community_rating` varchar(50) DEFAULT NULL,
  `metascore` smallint(6) DEFAULT NULL,
  `award_summary` varchar(2500) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `runtime_ticks` varchar(50) DEFAULT NULL,
  `played` tinyint(1) DEFAULT NULL,
  `imdb_url` varchar(100) DEFAULT NULL,
  `critic_rating_summary` varchar(2500) DEFAULT NULL,
  `offline_path` varchar(500) DEFAULT NULL,
  `tmdb_url` varchar(100) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `container` varchar(10) DEFAULT NULL,
  `remote_trailer` varchar(200) DEFAULT NULL,
  `taglines` varchar(250) DEFAULT NULL,
  `homepage_url` varchar(200) DEFAULT NULL,
  `production_location` varchar(100) DEFAULT NULL,
  `people` text,
  `studios` text,
  `media_streams` text,
  `media_name` varchar(50) DEFAULT NULL,
  UNIQUE KEY `mediabrowserid` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `movies_similar` (
  `movieid` varchar(100) DEFAULT NULL,
  `similarid` varchar(100) DEFAULT NULL,
  UNIQUE KEY `movie_id_similar_id` (`movieid`,`similarid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `movies_to_genres` (
  `genreid` varchar(100) DEFAULT NULL,
  `movieid` varchar(100) DEFAULT NULL,
  UNIQUE KEY `genreid_movieid` (`genreid`,`movieid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `movies_to_users` (
  `userid` int(1) NOT NULL,
  `movieid` varchar(50) NOT NULL,
  `seen` int(1) NOT NULL DEFAULT '0',
  `last_seen` date DEFAULT NULL,
  `rating` smallint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `people` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `deathdate` datetime DEFAULT NULL,
  `overview` text,
  `birth_place` varchar(100) DEFAULT NULL,
  `imdb_url` varchar(200) DEFAULT NULL,
  `tmdb_url` varchar(200) DEFAULT NULL,
  `movie_count` smallint(3) DEFAULT NULL,
  `series_count` smallint(3) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `seasons` (
  `id` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  `index_number` smallint(2) DEFAULT NULL,
  `sort_name` varchar(100) NOT NULL DEFAULT '0',
  `seriesid` varchar(100) DEFAULT NULL,
  `location_type` varchar(20) DEFAULT NULL,
  `path` varchar(300) DEFAULT NULL,
  UNIQUE KEY `mediabrowserid` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `series` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `sort_name` varchar(100) NOT NULL DEFAULT '0',
  `release_year` varchar(50) DEFAULT NULL,
  `mpaa_rating` varchar(50) DEFAULT NULL,
  `overview` varchar(2500) DEFAULT NULL,
  `virtual` smallint(6) DEFAULT '0',
  `hd` smallint(6) DEFAULT '0',
  `similar` varchar(600) DEFAULT NULL,
  `premiere_date` datetime NOT NULL,
  `imdb_url` varchar(250) DEFAULT NULL,
  `tmdb_url` varchar(250) DEFAULT NULL,
  `tvdb_url` varchar(250) DEFAULT NULL,
  `zap2it_url` varchar(250) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `community_rating` varchar(3) DEFAULT NULL,
  `people` text,
  `studios` text,
  `status` varchar(20) DEFAULT NULL,
  `airtime` varchar(20) DEFAULT NULL,
  `airdays` varchar(20) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `runtime_ticks` varchar(50) DEFAULT NULL,
  `homepage_url` varchar(250) DEFAULT NULL,
  UNIQUE KEY `mediabrowserid` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
