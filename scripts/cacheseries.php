<?php
ini_set('max_execution_time', 500);
/* Wayneflix.com Data Creation Script
 */
error_reporting(E_ALL ^  E_NOTICE); 


require_once('settings.php');

$start = microtime(true);

$serverURL = 'http://' . $serverIP . ':' . $serverPort . '/mediabrowser/'; // Constructed Server URL.
$imagePath  = '../images/series/';  // Image Storage Path.
$imagePathGenre = '../images/genres/';  // Image Storage Path for Genres.

function createDirectory($path) {
    if (!is_dir($path)) {
        return mkdir($path, 0777, true);
    }
    return 1;
}

function writeFile($file, $localPath, $newFileName, $getJSON) {
/*
 * Pulls files from MediaBrowser (i.e. Images) and writes them to disk.
 */ 
    $err_msg = ''; 
    if (!file_exists($localPath. DIRECTORY_SEPARATOR . $newFileName)) {
        $out = fopen($localPath. DIRECTORY_SEPARATOR . $newFileName,"wb");

        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_FILE, $out); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        if ($getJSON == true) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        }
        curl_setopt($ch, CURLOPT_URL, $file); 
        curl_exec($ch); 
        curl_close($ch); 
        echo "File Written\n";
    } else {
        echo "File Exists, skipping\n";
    }
    
}

function getData($url) {
/* 
 * Pulls data from MediaBrowser and returns the JSON object.
 */

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);

	return $result;
}

function buildSeries($dbh, $serie) {
/* 
 * Inserts the data for the 'series' table overall.
 * Table: series
 * Field: id
 * Field: date_added
 * Field: name
 * Field: sort_name
 * Field: release_year
 * Field: mpaa_rating
 * Field: overview
 * Field: virtual
 * Field: hd
 * Field: premiere_date
 * Field: imdb_url
 * Field: tmdb_url
 * Field: tvdb_url
 * Field: zap2it_url
 * Field: path
 * Field: community_rating
 * Field: people
 * Field: studios
 * Field: status
 * Field: airtime
 * Field: airdays
 * Field: end_date
 * Field: runtime_ticks
 * Field: homepage_url
 */

    try {
            echo "\tUpdating Database Record...\n";
			
			//Implode People into one Table
			$i = 0;
			$people = array();
			$result_people = count($serie['People']);
			while ($i < $result_people)
			{
				$people[$i] = implode("|",$serie['People'][$i]);
				$i++;
			}
			$imploded_people = implode("::::", $people);
			
			//Implode Studios into one Table
			$j = 0;
			$studios = array();
			$result_studios = count($serie['Studios']);
			while ($j < $result_studios)
			{
				$studios[$j] = implode("::::",$serie['Studios'][$j]);

				$j++;
			}
			$imploded_studios = implode("::::",$studios);
			
            $added= explode(".", $serie['DateCreated']);
			$added2= explode(".", $serie['PremiereDate']);
			
			//Hier Hinzufügen: JSON Befehl
            $values = array($serie['Id'], $added[0], $serie['Name'], $serie['SortName'], $serie['ProductionYear'], $serie['OfficialRating'], $serie['Overview'], $serie['IsPlaceHolder'], $serie['IsHD'], $added2[0], $serie['ExternalUrls'][0]['Url'], $serie['ExternalUrls'][0]['Url'], $serie['ExternalUrls'][2]['Url'], $serie['ExternalUrls'][3]['Url'], $serie['Path'], $serie['CommunityRating'], $imploded_people, $imploded_studios, $serie['Status'], $serie['AirTime'], $serie['AirDays'][0], $serie['EndDate'], $serie['RunTimeTicks'], $serie['HomePageUrl']);
            //Hier Hinzufügen: SQL Zeile und ?
            $query = $dbh->prepare("REPLACE INTO series (id, date_added, name, sort_name, release_year, mpaa_rating, overview, virtual, hd, premiere_date, imdb_url, tmdb_url, tvdb_url, zap2it_url, path, community_rating, people, studios, status, airtime, airdays, end_date, runtime_ticks, homepage_url) VALUES (?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?, ?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $query->execute($values);
    }
    catch(PDOException $e) {
            echo $e->getMessage();
            exit(0);
    }
}

function buildGenres($dbh, $genre) {
/* 
 * Inserts the data for the 'genres' table overall.
 * Table: genres
 * Field: id
 * Field: name
 * Field: count
 */
    try {
            echo "\tCreating Database Record for " . $genre['Name'] . "\n";

            $values = array($genre['Id'], $genre['Name'], $genre['MovieCount']);

            $query = $dbh->prepare("REPLACE INTO genres (id, name, count) VALUES (?, ?, ?)");

            $query->execute($values);
    }
    catch(PDOException $e) {
            echo $e->getMessage();
            exit(0);
    }

}



function updateGenres($dbh, $movie) {
/* 
 * Updates the Genres for a specific Movie.
 * Table: movies_to_genres
 * Field: genreid
 * Field: movieid
 */
   try {
            echo "\tUpdating Genres...\n";
            foreach ($movie['Genres'] as $genre) {
                $genreLookup = $dbh->prepare("SELECT id FROM genres WHERE name=?");
                $genreLookup->execute(array($genre));
                $result = $genreLookup->fetch();

                $addGenre = $dbh->prepare("REPLACE INTO movies_to_genres (genreid, movieid) VALUES (?, ?)");
                $addGenre->execute(array($result['id'],$movie['Id']));
            }
    }
    catch(PDOException $e) {
            echo $e->getMessage();
            exit(0);
    }
}


function buildEpisodes($dbh, $episode) {
/* 
 * Inserts the data for the 'episodes' table overall.
 * Table: episodes
 * Field: id
 * Field: date_added
 * Field: name
 * Field: sort_name
 * Field: release_name
 * Field: mpaa_rating
 * Field: overview
 * Field: virtual
 * Field: hd
 * Field: season_nr
 * Field: episode_nr
 * Field: seriesid
 * Field: seasonid
 * Field: location_type
 */

    try {
            echo "\tUpdating Database Record...\n";
			
			
            $added= explode(".", $episode['DateCreated']);
			//Hier Hinzufügen: JSON Befehl
            $values = array($episode['Id'], $added[0], $episode['Name'], $episode['SortName'], $episode['ProductionYear'], $episode['OfficialRating'], $episode['Overview'], $episode['IsPlaceHolder'], $episode['IsHD'], $episode['DvdSeasonNumber'], $episode['DvdEpisodeNumber'], $episode['SeriesId'], $episode['SeasonId'], $episode['LocationType']);
            //Hier Hinzufügen: SQL Zeile und ?
            $query = $dbh->prepare("REPLACE INTO episodes (id, date_added, name, sort_name, release_year, mpaa_rating, overview, virtual, hd, season_nr, episode_nr, seriesid, seasonid, location_type) VALUES (?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $query->execute($values);
    }
    catch(PDOException $e) {
            echo $e->getMessage();
            exit(0);
    }
}


function buildSeasons($dbh, $season) {
/* 
 * Inserts the data for the 'seasons' table overall.
 * Table: seasons
 * Field: id
 * Field: date_added
 * Field: name
 * Field: index_number
 * Field: sort_name
 * Field: seriesid
 * Field: location_type
 * Field: path
 */

    try {
            echo "\tUpdating Database Record...\n";
			
			
            $added= explode(".", $season['DateCreated']);
			//Hier Hinzufügen: JSON Befehl
            $values = array($season['Id'], $added[0], $season['Name'], $season['IndexNumber'], $season['SortName'], $season['SeriesId'], $season['LocationType'], $season['Path']);
            //Hier Hinzufügen: SQL Zeile und ?
            $query = $dbh->prepare("REPLACE INTO seasons (id, date_added, name, index_number, sort_name, seriesid, location_type, path) VALUES (?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?)");

            $query->execute($values);
    }
    catch(PDOException $e) {
            echo $e->getMessage();
            exit(0);
    }
}


try
{
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password, $options);
}
catch(PDOException $ex)
{
    exit("Failed to connect to the database: " . $ex->getMessage());
}

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

echo "WayneFlix Database Update.\n\n";
// Create image path if it doesn't exist.
createDirectory($imagePath);

// Build Genres
echo "Retrieving list of genres from server...\n";
$genres = mb3getdata($serverURL . 'Genres?UserId=' . $userHash . '&IncludeItemTypes=Series&format=json', $resp);
$genres = json_decode($genres,true);
echo "Found " . $genres['TotalRecordCount'] . " genres.\n";
echo "Updating Database...\n";
foreach ($genres['Items'] as $genre) {
   $success = buildGenres($dbh, $genre);
   
   $path = $imagePathGenre . $genre['Id'];
   $success = createDirectory($path);
   // Get and store landscape
	echo "\tWriting landscape...";
	$landscape = $serverURL . 'Items/' . $genre['Id'] . '/Images/Thumb?Width=500&Height=281';
	writeFile($landscape, $path, 'landscape.jpg', false);
}

// Build Episodes
echo "\n\n";
echo "Retrieving list of Episodes from server...\n";
//Hier Hinzufügen: JSON
$episodes = mb3getdata($serverURL . '/Users/' . $userHash . '/Items?Recursive=true&IncludeItemTypes=Episode&SortBy=SortName&Fields=DateCreated,Genres,IndexOptions,Overview,SortName,DvdSeasonNumber,DvdEpisodeNumber,SeriesId,SeasonId,LocationType&format=json', $resp);
$episodes = json_decode($episodes, true);
echo "Found " . $episodes['TotalRecordCount'] . " Episodes.\n\n";


// Build Seasons
echo "\n\n";
echo "Retrieving list of seasons from server...\n";
//Hier Hinzufügen: JSON
$seasons = mb3getdata($serverURL . '/Users/' . $userHash . '/Items?Recursive=true&IncludeItemTypes=Season&SortBy=Name&Fields=Name,Id,DateCreated,IndexOptions,SortName,IndexNumber,SeriesId,LocationType,Path&format=json', $resp);
$seasons = json_decode($seasons, true);
echo "Found " . $seasons['TotalRecordCount'] . " seasons.\n\n";


// Build Series
echo "\n\n";
echo "Retrieving list of series from server...\n";
//Hier Hinzufügen: JSON
$series = mb3getdata($serverURL . '/Users/' . $userHash . '/Items?Recursive=true&IncludeItemTypes=Series&SortBy=SortName&Fields=DateCreated,Genres,IndexOptions,Overview,SortName,PremiereDate,ExternalUrls,Path,CommunityRating,People,Studios,Status,AirTime,AirDays,EndDate,RunTimeTicks,HomePageUrl&format=json', $resp);
$series = json_decode($series, true);
echo "Found " . $series['TotalRecordCount'] . " Series.\n\n";
sleep(1);

$i = 0; // Count Processed Records.
// Process series.
foreach ($series['Items'] as $serie) {
    $i++;
	echo "Processing " . $serie['Name'] . " (ID:" . $serie['Id'] .").\n";
    $success = buildSeries($dbh, $serie);
    $success = updateGenres($dbh, $serie);
    $path = $imagePath . $serie['Id'];
    $success = createDirectory($path);
    
	// Get and store poster.
	echo "\tWriting poster...";
	$poster = $serverURL . 'Items/' . $serie['Id'] . '/Images/Primary';
	writeFile($poster, $path, 'poster.jpg', false);
	
	// Get and store poster thumbnail
	echo "\tWriting poster thumbnail...";
	$poster = $serverURL . 'Items/' . $serie['Id'] . '/Images/Primary/?Width=134&Height=200';
	writeFile($poster, $path, 'poster_thumb.jpg', false);
	
	// // Get and store banner
	// echo "\tWriting banner...";
	// $banner = $serverURL . 'Items/' . $serie['Id'] . '/Images/Banner';
	// writeFile($banner, $path, 'banner.jpg', false);
	
	// Get and store landscape
	echo "\tWriting landscape...";
	$landscape = $serverURL . 'Items/' . $serie['Id'] . '/Images/Thumb';
	writeFile($landscape, $path, 'landscape.jpg', false);
	
	// // Get and store Backdrop
	// echo "\tWriting backdrop...";
	// $backdrop = $serverURL . 'Items/' . $serie['Id'] . '/Images/Backdrop';
	// $backdropfilename= $serie['Id'] . '.backdrop.jpg';
	// writeFile($backdrop, $path, 'backdrop.jpg', false);

    echo "\n\n";
    //if ($i > 1) {break;}
}
echo "Total series Processed: $i\n\n";


// Process seasons.
foreach ($seasons['Items'] as $season) {
    $i++;
	echo "Processing " . $season['Name'] . " (ID:" . $season['Id'] .").\n";
    $success = buildseasons($dbh, $season);
    $path = $imagePath . $season['Id'];
    $success = createDirectory($path);
    
	// Get and store poster.
	echo "\tWriting poster...";
	$poster = $serverURL . 'Items/' . $season['Id'] . '/Images/Primary';
	writeFile($poster, $path, 'poster.jpg', false);
	

    echo "\n\n";
    //if ($i > 1) {break;}
}
echo "Total seasons Processed: $i\n\n";

//Process Episodes
foreach ($episodes['Items'] as $episode) {
    $i++;
	echo "Processing " . $episode['Name'] . " (ID:" . $episode['Id'] .").\n";
    $success = buildEpisodes($dbh, $episode);


    echo "\n\n";
    //if ($i > 1) {break;}
}
echo "Total episodes Processed: $i\n\n";

$time_taken = microtime(true) - $start;
echo "Time taken: $time_taken\n\n";
?>
