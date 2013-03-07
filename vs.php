<?

		include("simple_html_dom.php");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://adlab.msn.com/Online-Commercial-Intention/default.aspx");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		$txt = curl_exec($ch);
		curl_exec($ch);

		$html = str_get_html($txt);
		//$ret = $html->find('input[type="hidden"]');

		foreach ($html->find('input[type="hidden"]') as $k => $v) {
		
			if ($v->name == '__VIEWSTATE') {
				echo $v->value;
			}
		
		}



?>