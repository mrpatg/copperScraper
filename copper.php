<?
include("simple_html_dom.php");

	$dbuser = "orbitrne_pat";
	$dbpass = "orbitrne_pat";
	$dbname = "orbitrne_pat";
	$dbsrvr = "localhost";
	
	$link = mysql_connect($dbsrvr, $dbuser, $dbpass);
	if (!$link) {
	    die('Not connected : ' . mysql_error());}
	if (! mysql_select_db($dbname) ) {
	    die ('Can\'t use $dbname : ' . mysql_error());
	}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://futuresource.quote.com/quotes/custom.jsp?us=HG");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Connection: Keep-Alive',
		    'Keep-Alive: 150'
		));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		$txt = curl_exec($ch);

		$html = str_get_html($txt);


$i = 0;
$celldataodd = array();
$celldataeven = array();


foreach($html->find('tr.fs_quote_odd') as $rowodd) {

	$c = 0;

	foreach($rowodd->find('td') as $celldataodd) {
	
	    $odd[$i][$c] = $celldataodd->plaintext;
	
		if($c == "0"){
			array_pop($odd[$i]);	
		}
	
		if($c == "7"){
			array_pop($odd[$i]);	
		}
	
		if($c == "5"){
			array_pop($odd[$i]);	
		}
	
		$c++;

	}

	$i++;

}


$i = 0;

foreach($html->find('tr.fs_quote_even') as $roweven) {

	$c = 0;

	foreach($roweven->find('td') as $celldataeven) {
	
	    $even[$i][$c] = $celldataeven->plaintext;
	
		if($c == "0"){
			array_pop($even[$i]);	
		}
	
		if($c == "7"){
			array_pop($even[$i]);	
		}
	
		if($c == "5"){
			array_pop($even[$i]);	
		}
	
		$c++;

	}

	$i++;


}

$results = array_merge($even, $odd);


// Key Definitions

// 1 - Symbol
// 2 - Contract
// 3 - Month
// 4 - Time

// 6 - Last CURRENT PRICE

// 8 - Change
// 9 - Open
// 10 - High
// 11 - Low











foreach($odd AS $key=>$value) {

	if($key == "0"){

	$value[3] = str_replace('&nbsp;', '', $value[3]); 
	$value[3] = str_replace("'", "20", $value[3]); 

	$month = $value[3];
	$time = $value[4];
	$price = $value[6];

	$setprice = mysql_query("INSERT INTO `copper` (`month`, `time`, `price`) VALUES ('$month', '$time', '$price')") or die(mysql_error());
	
	
	
	$getyesterday = mysql_fetch_array ( mysql_query("SELECT  *,DATE_FORMAT(datetime, '%m/%d/%Y') FROM copper WHERE DATE(datetime) = DATE(DATE_ADD(NOW(), INTERVAL -1 DAY)) ORDER BY ID DESC"));
		$day1 = $getyesterday['price'];

	$getlastweek = mysql_fetch_array ( mysql_query("SELECT  *,DATE_FORMAT(datetime, '%m/%d/%Y') FROM copper WHERE DATE(datetime) = DATE(DATE_ADD(NOW(), INTERVAL -7 DAY)) ORDER BY ID DESC"));
		$day7 = $getlastweek['price'];

	$getmonth = mysql_fetch_array ( mysql_query("SELECT  *,DATE_FORMAT(datetime, '%m/%d/%Y') FROM copper WHERE DATE(datetime) = DATE(DATE_ADD(NOW(), INTERVAL -30 DAY)) ORDER BY ID DESC"));
		$day30 = $getlastmonth['price'];


if(empty($day1)){ $day1 = ""; }
if(empty($day7)){ $day7 = ""; }
if(empty($day30)){ $day30 = ""; }


if($price < $day1){ $day1chg = "up";}
if($price > $day1){ $day1chg = "down";}
if($price == $day1){ $day1chg = "steady";}

if($price < $day7){ $day7chg = "up";}
if($price > $day7){ $day7chg = "down";}
if($price == $day7){ $day7chg = "steady";}

if($price < $day30){ $day30chg = "up";}
if($price > $day30){ $day30chg = "down";}
if($price == $day30){ $day30chg = "steady";}

if(empty($day1)){ $day1 = ""; $day1chg = "na"; }
if(empty($day7)){ $day7 = ""; $day7chg = "na";}
if(empty($day30)){ $day30 = ""; $day30chg = "na";}

	echo "
	<div class=\"copper\">
		<ul>
			<li name=\"month\">$value[3]</li>
			<li name=\"time\">$value[4]</li>
			<li name=\"current\">$value[6]</li>
			<li name=\"yesterday\"><span class=\"$day1chg\"></span>$day1</li>
			<li name=\"lastweek\"><span class=\"$day7chg\"></span>$day7</li>
			<li name=\"lastmonth\"><span class=\"$day30chg\"></span>$day30</li>
		</ul>
	</div>";

		}

}

?>