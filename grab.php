<?

		include("simple_html_dom.php");
		
		$ckfile = tempnam ("/tmp", "CURLCOOKIE");
		
		$query = $_GET['query'];


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://adlab.msn.com/Online-Commercial-Intention/default.aspx");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Connection: Keep-Alive',
		    'Keep-Alive: 150'
		));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		$txt = curl_exec($ch);
		curl_exec($ch);

		$html = str_get_html($txt);
		//$ret = $html->find('input[type="hidden"]');

		foreach ($html->find('input[type="hidden"]') as $k => $v) {
		
			if ($v->name == '__VIEWSTATE') {
				$viewstate = (string) $v->value;
			}
		
		}

// echo urlencode($viewstate)."<br>";

$viewstate = urlencode($viewstate);

		$params = array(
			'__EVENTTARGET' => "",
			'__EVENTARGUMENT' => "",
			'__LASTFOCUS' => "",
			'__VIEWSTATE' => "$viewstate",
			'MyMaster%3ADemoPageContent%3AtxtQuery' => "$query",
			'MyMaster%3ADemoPageContent%3Alan' => "QueryRadio",
			'MyMaster%3ADemoPageContent%3AgoButton.x' => "17",
			'MyMaster%3ADemoPageContent%3AgoButton.y' => "12",
			'MyMaster%3ADemoPageContent%3AidQuery' => "$query",
			'MyMaster%3AHiddenKeywordTextBox' => "",
			);


$headers = array('Connection: Keep-Alive', 'Keep-Alive: 150');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://adlab.msn.com/Online-Commercial-Intention/default.aspx');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_REFERER, 'http://adlab.msn.com/Online-Commercial-Intention/default.aspx');
		curl_setopt($ch, CURLOPT_POSTFIELDS, '$params');
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2");
		$pagetext = curl_exec($ch);
		curl_exec($ch);


		$html = str_get_html($pagetext);
		$ret = $html->find('.intentionLabel', 1)->plaintext;
		
		echo $ret."<br><br><br>";
		
		echo $pagetext;


?>