<?php
ini_set('max_execution_time', 3000);
error_reporting(E_ALL ^  E_NOTICE); 
/* Wayneflix.com Data Creation Script
 * 
 * This script reads from the MediaBrowser Server to create data structures that will be used in the WayneFlix Website.
 * 
 * Changelog ------
 *  May 11, 2014
 *      Changed image storage path to store images based on media ID instead of naming images based on media ID.
 *      Changed INSERT queries to REPLACE INTO queries in order to prevent collision and allow for easy data updates.
 *      Added Genre Mapping for Movies.
 *  May 10, 2014: 
 *      Updated changed from JSON data storage to MySQL data storage.
 *      Added Checks for images so they are only written if they don't already exist.
 *      Changed from print to echo for console output and speed improvements.
 *
 *  March 01, 2014:
 *      Initial Version.
 *      Store movie structures in multiple JSON objects.
 *      Store Images in directories on website for proper caching.
 *
 * ToDo -----------
 *      Move image copying to function for easier expandability and code reuse.
 *      Add Better Error Checking
 *      Clean up file writing
 *      Add switch to clear and rebuild database tables.
 */

ini_set('display_errors',1);

require_once('settings.php');

$serverURL = 'http://' . $serverIP . ':' . $serverPort . '/mediabrowser/'; // Constructed Server URL.
$imagePath  = '../images/persons/';  // Image Storage Path.


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






function buildPeople($dbh, $people) {
/* 
 * Inserts the data for the 'people' table overall.
 * Table: people
 * Field: id
 * Field: name
 * Field: birthdate
 * Field: deathdate
 * Field: overview
 * Field: birth_place
 * Field: imdb_url
 * Field: tmdb_url
 * Field: movie_count
 * Field: series_count
 */
   try {
            echo "\tCreating People Database Record for " . $people['Name'] . "\n";

            $values = array($people['Id'], $people['Name'], $people['PremiereDate'], $people['EndDate'], $people['Overview'], $people['ProductionLocations'][0], $people['ExternalUrls'][0]['Url'], $people['ExternalUrls'][1]['Url'], $people['MovieCount'], $people['SeriesCount']);

            $query = $dbh->prepare("REPLACE INTO people (id, name, birthdate, deathdate, overview, birth_place, imdb_url, tmdb_url, movie_count, series_count) VALUES (?, ?, STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), STR_TO_DATE(?,'%Y-%m-%dT%H:%i:%s'), ?, ?, ?, ?, ?, ?)");

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



// Build People
echo "Retrieving list of people from server...\n";
echo "If you got a lot of people, this may take a while! \n";
$persons = mb3getdata($serverURL . 'Persons?UserId=' . $userHash . '&IncludeItemTypes=Movie&Fields=EndDate,Overview,ExternalUrls,ProductionLocations,MovieCount,SeriesCount&format=json', $resp);
$persons = json_decode($persons,true);
echo "Found " . $persons['TotalRecordCount'] . " people.\n";
echo "Updating Database...\n";
foreach ($persons['Items'] as $person) {
   $success = buildPeople($dbh, $person);
}


sleep(1);

$i = 0; // Count Processed Records.
// Process People
foreach ($persons['Items'] as $person) {
    $i++;
	echo "Processing " . $person['Name'] . " (ID:" . $person['Id'] .").\n";




    $path = $imagePath . $person['Id'];
    $success = createDirectory($path);
    
	// Get and store poster.
	echo "\tWriting poster...";
	$poster = $serverURL . 'Persons/' . str_replace(" ","%20",$person['Name']) . '/Images/Primary/?Width=200&Height=300';
	writeFile($poster, $path, 'poster.jpg', false);
	


    echo "\n\n";
    //if ($i > 1) {break;}
}
echo "Total People Processed: $i\n\n";
?>
