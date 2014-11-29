<?php
ini_set('max_execution_time', 3000);
/* Wayneflix.com Data Creation Script
 */

error_reporting(E_ALL ^  E_NOTICE); 

require_once('settings.php');

$start = microtime(true);

$serverURL = 'http://' . $serverIP . ':' . $serverPort . '/mediabrowser/'; // Constructed Server URL.
$imagePath  = '../images/movies/';  // Image Storage Path.


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

function buildMovies($dbh, $movie) {
/* 
 * Inserts the data for the 'movies' table overall.
 * Table: movies
 * Field: id
 * Field: date_added
 * Field: name
 * Field: sort_name
 * Field: release_name
 * Field: mpaa_rating
 * Field: overview
 * Field: virtual
 * Field: hd
 * Field: critic_rating
 * Field: community_rating
 * Field: metascore
 * Field: award_summary
 * Field: runtime_ticks
 * Field: imdb_url
 * Field: critic_rating_summary
 * Field: offline_path
 * Field: tmdb_url
 * Field: size
 * Field: container
 * Field: remote_trailer
 * Field: taglines
 * Field: homepage_url
 * Field: production_location
 * Field: people
 * Field: studios
 * Field: media_streams
 * Field: media_name
 */

    try {
            echo "\tUpdating Database Record...\n";
			
			//Implode People into one Table
			$i = 0;
			$people = array();
			$result_people = count($movie['People']);
			while ($i < $result_people)
			{
				$people[$i] = implode("|",$movie['People'][$i]);
				$i++;
			}
			$imploded_people = implode("::::", $people);
			
			//Implode Studios into one Table
			$j = 0;
			$studios = array();
			$result_studios = count($movie['Studios']);
			while ($j < $result_studios)
			{
				$studios[$j] = implode("::::",$movie['Studios'][$j]);

				$j++;
			}
			$imploded_studios = implode("::::",$studios);
			
			//Serialize MediaStreams into one Table
			$serilialized_media = serialize($movie['MediaStreams']);
			
            $added= explode(".", $movie['DateCreated']);
			//Hier Hinzufügen: JSON Befehl
            $values = array($movie['Id'], $added[0], $movie['Name'], $movie['SortName'], $movie['ProductionYear'], $movie['OfficialRating'], $movie['Overview'], $movie['IsPlaceHolder'], $movie['IsHD'], $movie['CriticRating'], $movie['CommunityRating'], $movie['Metascore'], $movie['AwardSummary'], $movie['RunTimeTicks'], $movie['ExternalUrls'][0]['Url'], $movie['CriticRatingSummary'], $movie['Path'], $movie['ExternalUrls'][1]['Url'], $movie['MediaSources'][0]['Size'], $movie['MediaSources']['0']['Container'], $movie['RemoteTrailers'][0]['Url'], $movie['Taglines'][0], $movie['HomePageUrl'], implode(":: ",$movie['ProductionLocations']), $imploded_people, $imploded_studios, $serilialized_media, $movie['MediaSources'][0]['Name']);
            //Hier Hinzufügen: SQL Zeile und ?
            $query = $dbh->prepare("REPLACE INTO movies (id, date_added, name, sort_name, release_year, mpaa_rating, overview, virtual, hd, critic_rating, community_rating, metascore, award_summary, runtime_ticks, imdb_url, critic_rating_summary, offline_path, tmdb_url, size, container, remote_trailer, taglines, homepage_url, production_location, people, studios, media_streams, media_name) VALUES (?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

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



function updateSimilarTitles($dbh, $movieID, $similar_array, $limit) {
/* 
 * Updates the movies.similar field for each movie record.
 * Table: movies
 * Field: similar
 */
   try {
            echo "\tUpdating Similar Movies...\n";
            foreach ($similar_array['Items'] as $similar) {
                $addSimilar = $dbh->prepare("REPLACE INTO movies_similar (movieid, similarid) VALUES (?, ?)");
                $addSimilar->execute(array($movieID, $similar['Id']));
            }
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
$genres = mb3getdata($serverURL . 'Genres?UserId=' . $userHash . '&IncludeItemTypes=Movie&format=json', $resp);
$genres = json_decode($genres,true);
echo "Found " . $genres['TotalRecordCount'] . " genres.\n";
echo "Updating Database...\n";
foreach ($genres['Items'] as $genre) {
   $success = buildGenres($dbh, $genre);
}

// Build Movies
echo "\n\n";
echo "Retrieving list of movies from server...\n";
//Hier Hinzufügen: JSON
$movies = mb3getdata($serverURL . '/Users/' . $userHash . '/Items?Recursive=true&IncludeItemTypes=Movie&SortBy=SortName&Fields=People,Studios,Budget,CriticRatingSummary,CriticRating,CommunityRating,Metarating,AwardSummary,DateCreated,Genres,HomePageUrl,IndexOptions,MediaStreams,Overview,ProviderIds,Revenue,SortName,TrailerUrl,RunTimeTicks,ExternalUrls,CriticRatingSummary,Path,MediaSources,MediaStreams,RemoteTrailers,Taglines,HomePageUrl,ProductionLocations&format=json', $resp);
$movies = json_decode($movies, true);
echo "Found " . $movies['TotalRecordCount'] . " movies.\n\n";
sleep(1);

$i = 0; // Count Processed Records.
// Process Movies.
foreach ($movies['Items'] as $movie) {
    $i++;
	echo "Processing " . $movie['Name'] . " (ID:" . $movie['Id'] .").\n";
    $success = buildMovies($dbh, $movie);
    
    $success = updateGenres($dbh, $movie);
    $similar = mb3getdata( $serverURL . 'Movies/' . $movie['Id'] . '/Similar?IncludeTrailers=false&UserId=' . $userHash . '&Limit=' . $limit . '&format=json', $resp);
    $similar = json_decode($similar,true);
    $success = updateSimilarTitles($dbh, $movie['Id'], $similar, $limit);


    $path = $imagePath . $movie['Id'];
    $success = createDirectory($path);
    
	// Get and store poster.
	echo "\tWriting poster...";
	$poster = $serverURL . 'Items/' . $movie['Id'] . '/Images/Primary';
	writeFile($poster, $path, 'poster.jpg', false);
	
	// Get and store poster thumbnail
	echo "\tWriting poster thumbnail...";
	$poster = $serverURL . 'Items/' . $movie['Id'] . '/Images/Primary/?Width=134&Height=200';
	writeFile($poster, $path, 'poster_thumb.jpg', false);
	
	// // Get and store Backdrop
	// echo "\tWriting backdrop...";
	// $backdrop = $serverURL . 'Items/' . $movie['Id'] . '/Images/Backdrop';
	// $backdropfilename= $movie['Id'] . '.backdrop.jpg';
	// writeFile($backdrop, $path, 'backdrop.jpg', false);

    echo "\n\n";
    //if ($i > 1) {break;}
}
echo "Total Movies Processed: $i\n\n";
$time_taken = microtime(true) - $start;
echo "Time taken: $time_taken\n\n";
?>
