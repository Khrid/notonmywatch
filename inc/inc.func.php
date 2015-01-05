<?php
	function connectDb() {
		include 'inc.db.php';
		return mysql_connect($db['host'], $db['user'], $db['pass']) ;
	}
	
	function clearData() {	
		include 'inc.param.php';
		array_map('unlink', glob('./data/temp.zip'));
		array_map('unlink', glob('./data/extracted/actors.xml'));
		array_map('unlink', glob('./data/extracted/banners.xml'));
		array_map('unlink', glob('./data/extracted/'.$_LANG.'.xml'));
	}
	
	function defineProxy($user, $domain, $password, $proxyUrl, $proxyPort) {
		$auth = base64_encode($user.'@'.$domain.':'.$password);
		$aContext = array(
			'http' => array(
				'proxy' => $proxyUrl.':'.$proxyPort,
				'request_fulluri' => true,
				'header' => "Proxy-Authorization: Basic $auth",
			),
		);
		return stream_context_create($aContext);
	}
	
	function checkRemoteFile($url) {
		$ch = curl_init();
		if(PROXY) {
			$proxy = 'proxy.groupemutuel.ch:80';
			$proxyauth = 'crittind@groupemutuel.ch:badibu2';
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
		}
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(curl_exec($ch)!==FALSE) {
			return true;
		} else {
			return false;
		}
	}
	
	function imageFile($t) {
		$url=getimagesize($t);
		if(!is_array($url)) {
			return false;
		} else {
			return true;
		}
	}
	
	function pre($t) {
		echo '<pre>';
		print_r($t);
		echo '</pre>';
	}
	
	function getMovieThumb($id, $cx) {
		$mirror = 'http://api.rottentomatoes.com/';		
		$url['film'] = $mirror.'/api/public/v1.0/movies/'.$id.'.json?apikey=bdpj7y49s6c88bgg4vunxdbq';
		//$url['film'] = $mirror.'/api/public/v1.0/movies/'.$id.'.json?apikey='.$_API['MOVIES'];
		$data = file_get_contents($url['film'], false, $cx);
		$search_results = json_decode($data, true);
		$movie = $search_results;
		return str_replace('tmb', 'det', $movie['posters']['thumbnail']);
	}
	
	function searchSerie($serieName) {
		return $serieName;
	}
?>