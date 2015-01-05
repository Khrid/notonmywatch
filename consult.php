<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<title>Consult</title>
	</head>
<body>
<div id="container">
<?php include 'inc/inc.menu.php'; ?>
<div id="content">
<?php
	include 'inc/inc.all.php';
	if(PROXY) {
		$cxContext = defineProxy('crittind', 'badibu2', 'groupemutuel.ch', 'tcp://proxy.groupemutuel.ch', 80);
	} else {
		$cxContext = null;
	}
	
	$params['language'] = $_LANG;
	$params['api_key'] = '0839DA601181D4B1';
	
	// Set mirror
	$mirror = 'http://thetvdb.com';		
	$url['server_time'] = $mirror.'/api/Updates.php?type=none';
	$url['series'] = $mirror.'/api/GetSeries.php?seriesname=';
	
	
	// Get server time
	$feed = file_get_contents($url['server_time'], false, $cxContext);
	$server_time = simplexml_load_string($feed);
	
	$serie['id'] = $_GET['serieId'];
	$url['serie'] = $mirror.'/api/'.$params['api_key'].'/series/'.$serie['id'].'/all/'.$params['language'].'.zip';
	$feed = file_get_contents($url['serie'], false, $cxContext);
	//copy($url['serie'], './data/temp.zip');
	$zip['location'] = './data/temp.zip';
	$file = fopen($zip['location'], 'w');
	fwrite($file, $feed);
	fclose($file);

	$path = './data/extracted/';
	$_zip = new ZipArchive;
	$res = $_zip->open($zip['location']);
	if ($res === TRUE) {
	  // extract it to the path we determined above ->banner
	  $_zip->extractTo($path);
	  $_zip->close();
	} 

	$fr = simplexml_load_file('./data/extracted/'.$params['language'].'.xml');
	/*echo '<pre>';
	print_r($fr);
	echo '</pre>';*/
	
	if(isset($_GET['serieId']) && !isset($_GET['episodeId'])) {
		// http://thetvdb.com/banners/graphical/274431-g6.jpg
		echo '<center><img src="'.$mirror.'/banners/'.$fr->Series->banner.'"/></center><br/>';
		echo '<div class="episodesListDiv"><table class="episodesList">';
		//echo '<center><img src=" http://thetvdb.com/banners/graphical/274431-g6.jpg"/></center>';
		$first = 1;
		$currentSeason = 1;
		$i = 0;
		foreach($fr->Episode as $ep) {
			$i++;
			if($first) {
				echo '<tr class="episodesListSeason"><th colspan=3>Saison '.$ep->SeasonNumber.'</th></tr>';
				echo utf8_encode('<tr><th>N°</th><th>Episode</th><th>Titre</th></tr>');
				$previous = $ep->SeasonNumber;
				$first = !$first;
			}
			
			if(strcmp($ep->SeasonNumber, $previous) != '') {
				echo '</table></div><div class="episodesListDiv">';
				echo utf8_encode('<table class="episodesList"><tr class="episodesListSeason"><th colspan=3>Saison '.$ep->SeasonNumber.'</th></tr><tr><th>N°</th><th>Episode</th><th class="thLeft">Titre</th></tr>');
			}
			
			$dateEp = strtotime($ep->FirstAired);
			if($dateEp < $server_time->Time) {
			echo '<tr>';
				echo '<td class="episodeListNumber">'.$ep->absolute_number.'</td>';
				echo '<td class="episodeListEpisode"><a href="?serieId='.$serie['id'].'&episodeId='.$ep->id.'">S'.(($ep->SeasonNumber < 10) ? '0'.$ep->SeasonNumber : $ep->SeasonNumber)
							.'E'.(($ep->EpisodeNumber < 10) ? '0'.$ep->EpisodeNumber : $ep->EpisodeNumber).'</a></td>';
				echo '<td class="episodeListName">'.$ep->EpisodeName.' ('.$ep->FirstAired.')</td>';
				//echo $ep->Overview.'<br/><br/>';
			echo '</tr>';
			}
			$previous = $ep->SeasonNumber;	
		}
		echo '</table></div>';
		clearData();
	} else if(isset($_GET['serieId']) && isset($_GET['episodeId'])) {
		echo utf8_encode('<a href="?serieId='.$serie['id'].'">Retour à la série</a>');
		foreach($fr->Episode as $ep) {
			if($ep->id == $_GET['episodeId']) {
				echo '<center><img src="'.$mirror.'/banners/'.$ep->filename.'"/></center><br/>';
				echo $ep->Overview;
			}
		}
	} else {
		echo 'Something went wrong...';
	}
?>
</div>
</div>
<div id="footer"> <?php include 'footer.php'?> </div>
</body>
</html>