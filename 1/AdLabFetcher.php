<?php

class AdLabFetcher
{
	var $_ch = null;

	var $results = array();
	var $verbose = true;
	
	var $timeout = 5;
		
	function AdLabFetcher(){
		$this->reset();	
		return;
	}
		
	function reset(){
//		echo "resetting!";
		// create a new curl resource
		$this->_ch = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->_ch, CURLOPT_MAXREDIRS, 2);
//		curl_setopt($this->_ch, CURLOPT_AUTOREFERER, 1);
//		curl_setopt($this->_ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3');
		curl_setopt($this->_ch, CURLOPT_FILETIME, 1);
//		curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->timeout);    
		curl_setopt($this->_ch, CURLOPT_POST, 1);
		curl_setopt($this->_ch, CURLOPT_FORBID_REUSE, 1);



		return;
	}	

//	function fetch_stats($items, $type)
//	{	
//		foreach($items AS $item)
//		{
////			$query = 'ctl00_MyMaster_DemoPageContent_rdQuery'."=$item";
////			$query .= '&ctl00_MyMaster_DemoPageContent_rdURL=rdURL';
////			$query .= '&ctl00_MyMaster_DemoPageContent_rdURL=rdURL';
//			$pdata = array();
//			$pdata['ctl00$MyMaster$DemoPageContent$Query'] = urlencode($item);
//			$pdata['ctl00$MyMaster$DemoPageContent$lan'] = "rdURL";
//		    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $pdata);
//			curl_setopt($this->_ch, CURLOPT_URL, "http://adlab.microsoft.com/DPUI/DPUI.aspx");
//			$pagetext = curl_exec($this->_ch);
//			echo $pagetext."<hr>";
//		}
//		print_r ($items);
//		return $items;
//	}
	
	function get_commercial_intent($results)
	{
		$sort = array();
	    curl_setopt($this->_ch, CURLOPT_POST, 1);
	 	foreach($results AS $word => $arr)
	 	{
//	 		$word = trim($word);
//	 		if($word == "")
//	 			continue;
	 		$query = urlencode($word);
			curl_setopt($this->_ch, CURLOPT_URL, "http://adlab.msn.com/Online-Commercial-Intention/oci.aspx");
		    $postvars = "&K_QUERY=$query&K_TYPE=QueryRadio";
		    curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $postvars);
			$pagetext = curl_exec($this->_ch);
			preg_match("/<font color=\"Firebrick\"> (.*)<br><br>Probability&nbsp;for&nbsp;Commercial&nbsp;Query:<br><br>(\d\.\d+)/i", $pagetext, $m);
			$results[$word]['ci'] = $m[2];
	 	}   
	    return $results;
		curl_close($this->_ch);
		
	}

		
	function get_demographics($results)
	{
		$sort = array();
	    curl_setopt($this->_ch, CURLOPT_POST, 1);
	 	foreach($results AS $word => $arr)
	 	{
//	 		$word = trim($word);
//	 		if($word == "")
//	 			continue;
	 		$query = urlencode($word);
			curl_setopt($this->_ch, CURLOPT_URL, "http://adlab.msn.com/Demographics-Prediction/DPUI.aspx");
		    $postvars = "&query=$query&IsUrl=rdQuery";
		    curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $postvars);
			$pagetext = curl_exec($this->_ch);
//			echo $pagetext;
//			echo $word."<br />";
			preg_match("/_lbMale\"><b><font face=\"Arial\" size=\"4\">:0.(\d+)<\/font>/i", $pagetext, $m);
//			echo "Male: {$m[1]}";
			if(isset($m[1]))
			{
				$results[$word]['male'] = $m[1]."%";
				preg_match("/_lbFemale\"><b><font face=\"Arial\" size=\"4\">:0.(\d+)<\/font>/i", $pagetext, $m);
	//			echo "Female: {$m[1]}";
				$results[$word]['female'] = $m[1]."%";
				preg_match("/color=#37578A>(\d+.\d?\d?\d?)<\/font>/i", $pagetext, $m);
	//			echo "Ages: {$m[1]}";
				$results[$word]['ages'] = $m[1];
			}
			else
			{
				$results[$word]['male'] = $results[$word]['female'] = $results[$word]['ages'] = null;
			}
	 	}
   
	    return $results;
		curl_close($this->_ch);
		
	}
	
}


?>
