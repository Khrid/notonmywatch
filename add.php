<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/func.js"></script>
		<title>Add</title>
	</head>
<body>
<div id="container">
<?php include 'inc/inc.menu.php'; ?>
<div id="content">
<?php
	include 'inc/inc.all.php';
	
	
	if(isset($_POST['what']) || isset($_GET['id'])) {
		$w = $_GET['w'];
		$p_w = $_POST['w'];
		$params['language'] = $_LANG;
		if(PROXY) {
			$cxContext = defineProxy('crittind', 'badibu2', 'groupemutuel.ch', 'tcp://proxy.groupemutuel.ch', 80);
		} else {
			$cxContext = null;
		}
		if(isset($_GET['id'])) {
			if($w == 'f') {
				$mirror = 'http://api.rottentomatoes.com/';		
				$url['film'] = $mirror.'/api/public/v1.0/movies/'.$_GET['id'].'.json?apikey='.$_API['MOVIES'];
				$data = file_get_contents($url['film'], false, $cxContext);
				$search_results = json_decode($data, true);
				if ($search_results === NULL) die('Error parsing json');
				$movie = $search_results;
				//pre($movie);
				echo '<img src="'.str_replace('tmb', 'det', $movie['posters']['thumbnail']).'"/><br/>';
				echo $movie['title'];?>
				<center><span onClick="addFilm('<?=$_GET['id'];?>','<?=addslashes($movie['title']);?>')">Ajouter</span></center>
				<?php
			} else if($w == 's') {
				$mirror = 'http://thetvdb.com';		
				$serie['id'] = $_GET['id'];
				$url['serie'] = $mirror.'/api/'.$_API['SERIES'].'/series/'.$serie['id'].'/all/'.$params['language'].'.zip';
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
				  // extract it to the path we determined above
				  $_zip->extractTo($path);
				  $_zip->close();
				} 

				$fr = simplexml_load_file('./data/extracted/'.$params['language'].'.xml');
				
				echo '<u>'.$fr->Series->SeriesName.'</u><br/>';
				echo $fr->Series->Overview.'<br/><br/>';
				?><center><span onClick="addSerie('<?=$serie['id'];?>','<?=addslashes($fr->Series->SeriesName);?>')">Ajouter</span></center>
				<?php
			}
		} else {
			if($_POST['what'] == 'film') {
				$mirror = 'http://api.rottentomatoes.com/';		
				$url['film'] = $mirror.'/api/public/v1.0/movies.json?page_limit=10&page=1&apikey='.$_API['MOVIES'].'&q=';
				$data = file_get_contents($url['film'].urlencode($_POST['film']), false, $cxContext);
				$search_results = json_decode($data);
				if ($search_results === NULL) die('Error parsing json');
				$movies = $search_results->movies;
				foreach ($movies as $movie) {
				echo '<a href="?w=f&id='.$movie->id.'">' . $movie->title . " (" . $movie->year . ")</a><br/>";
				}
			} else if($_POST['what'] == 'serie') {
				$mirror = 'http://thetvdb.com';		
				$url['server_time'] = $mirror.'/api/Updates.php?type=none';
				$url['series'] = $mirror.'/api/GetSeries.php?seriesname=';
				$feed = file_get_contents($url['series'].urlencode($_POST['serie']), false, $cxContext);
				$xmlSeries = simplexml_load_string($feed);
				echo utf8_encode('Séries trouvées :<br/>');
				foreach($xmlSeries->Series as $serie) {
					echo '<a href="?w=s&id='.$serie->seriesid.'">'.$serie->SeriesName.' ('.$serie->FirstAired.')</a><br/>';
				}	
			}
		}
	} else {
		$w = $_GET['w'];
		switch($w) {
			case 'f':
				echo utf8_encode('<form action="add.php?w=f" method="post">
					<label for="film">Titre</label><br/><input type="text" name="film"/><br/><br/>
					<input type="hidden" name="what" value="film"/>
					<input type="submit" value="Rechercher">
					</form>');
				break;
			case 's':
				echo utf8_encode('<form action="add.php?w=s" method="post">
					<label for="serie">Série</label><br/><input type="text" name="serie"/><br/><br/>
					<input type="hidden" name="what" value="serie"/>
					<input type="submit" value="Rechercher">
					</form>');
				break;
			default:
				break;
		}
	}
	
?>
</div>
</body>
</html>