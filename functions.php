<?php
//Convert RunTimeTicks in Minutes
function convertRunTimeTicks($runtime_ticks){
     $runtime_min = $runtime_ticks/600000000;

     return (int)$runtime_min;
}
////////////////////////////////


//Convert Bytes to Gigabytes
function convertBytes($bytes){
     $gigabytes = round(($bytes/1073741824),2);

     return $gigabytes;
}
////////////////////////////////


//Convert Bitrate in kbps
function convertBitrate($bitrate){
     $kbps = $bitrate/1000;

     return (int)$kbps;
}
////////////////////////////////


//Explode People into Arrays for Movie Details
function explodePeople($imploded){
	$exploded1 = explode("::::",$imploded);
	$count = count($exploded1);
	$exploded2 = array();
	$j = 0;
	while ($j < $count){
	$exploded2[$j] = explode("|",$exploded1[$j]);
	$j++;
	}
	
	return $exploded2;
}
//////////////////////////////


//Explode Studios into Array for Movie Details
function explodeStudios($imploded){
	$exploded = explode("::::",$imploded);
	
	return $exploded;
}
//////////////////////////////


//Explode Media Names into Array for Movie Details
function explodeMediaName($imploded){
	$exploded = explode ("/",$imploded);
	
	return $exploded;
}
//////////////////////////////
?>