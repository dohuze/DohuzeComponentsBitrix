<?
	$time_start = microtime(true);

	if(empty($_GET["id_component"])) {
		echo 'ERROR $_GET["id_component"]';
		die();
	}
		
	function poisk_lev_prav__lev($lev, $prav, $stroka) {
		$razbien_array = explode($lev, $stroka);
		//print_r($razbien_array);
		$razbien_array_1 = explode($prav, $razbien_array[1]);
		$iskom_stroka = trim($razbien_array_1[0]);
		return $iskom_stroka;
	}

	function download_image($image_url, $path) {
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $image_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
		$headers = array();
		$headers[] = 'Authority: scontent-arn2-1.cdninstagram.com';
		$headers[] = 'Sec-Ch-Ua: \" Not;A Brand\";v=\"99\", \"Google Chrome\";v=\"97\", \"Chromium\";v=\"97\"';
		$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
		$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
		$headers[] = 'Upgrade-Insecure-Requests: 1';
		$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36';
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
		$headers[] = 'Sec-Fetch-Site: none';
		$headers[] = 'Sec-Fetch-Mode: navigate';
		$headers[] = 'Sec-Fetch-User: ?1';
		$headers[] = 'Sec-Fetch-Dest: document';
		$headers[] = 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$data = curl_exec($ch);
		if(curl_errno($ch)) {
			echo 'Error Curl_PHP:' . curl_error($ch);
			die();
		} else {
			//echo file_put_contents($path, $data) . '(B) : ' . $image_url . "                    ";
			echo file_put_contents($path, $data) . '(B) ';
		}
		curl_close($ch);
	}

	if(isset($_POST["file_name"]) && isset($_POST["str"])) {
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/include/dohuze/instagram_' . $_GET["id_component"] . '/' . $_POST["file_name"], $_POST["str"]);
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/include/dohuze/instagram_' . $_GET["id_component"] . '/time_cache.txt', time());
	}
	
	$data_arr_post = explode('^dohuze^', $_POST["str"]);
	if(count(explode('type_get_instagram=', $data_arr_post[0])) > 1) {
		list(,$type_get_instagram) = explode('=', $data_arr_post[0]);
	}

	if($type_get_instagram == 'token') {
		for($i = 1; $i < count($data_arr_post); $i++) {
			if(count(explode('media_url=', $data_arr_post[$i])) > 1)
				$media_url_arr[] = poisk_lev_prav__lev('media_url=', '^dohuze^', $data_arr_post[$i]);
		}
		for($i = 0; $i < count($media_url_arr); $i++) {
			download_image($media_url_arr[$i], $_SERVER["DOCUMENT_ROOT"] . '/include/dohuze/instagram_' . $_GET["id_component"] . '/foto_po_tokenu/' . $i);
		}
	}

	$time_end = microtime(true);
	$time = $time_end - $time_start;

	echo 'handler_ajax.php: handler worked ' .$time. ' seconds';