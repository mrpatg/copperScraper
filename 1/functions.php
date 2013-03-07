<?

$dbuser = "orbitrne_mmseo";
$dbpass = "orbitrne_mmseo";
$dbname = "orbitrne_mmseo";
$dbsrvr = "localhost";

$link = mysql_connect($dbsrvr, $dbuser, $dbpass);
if (!$link) {
    die('Not connected : ' . mysql_error());}
if (! mysql_select_db($dbname) ) {
    die ('Can\'t use $dbname : ' . mysql_error());
}


function getData($agegroup){

		$agegroup = str_replace("~", "-", $agegroup);
	
		if($agegroup == "35-49"){ $agegroup = "35-44"; }
	
		if($agegroup == "50"){ $agegroup = "50+"; }

		$result = mysql_query("SELECT * FROM `forrester_xml` WHERE `age` = '$agegroup' AND `country` = 'US' AND `gender` = 'Not Specified' ") or trigger_error(mysql_error("Error")); 

		while($row = mysql_fetch_array($result)){ 

			foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
	
			$search = array($row['Creators'], $row['Critics'], $row['Collectors'], $row['Joiners'], $row['Spectators'], $row['Inactives']);
		
		}

	return $search;

}

?>