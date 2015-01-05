<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/func.js"></script>
		<title>Index</title>
	</head>
	<body>
		<div id="container">
			<?php include 'inc/inc.menu.php'; ?>
			<div id="content">
				<?php
					include 'inc/inc.all.php';
					$mirror = 'http://thetvdb.com';	
					$conn = connectDb();
					$database = mysql_select_db($db['db'], $conn);
					
					// SERIES
					$sql = 'select SeNom, SeTvdbid from tbl_series order by SeNom';
					$res = mysql_query($sql);
					echo 'Séries<hr/>';
					echo '<table><tr>';
					$rows = 5;
					$i = 0;
					while($tmp = mysql_fetch_array($res)) {
						$img = $tmp['SeNom'];
						if($i == $rows) { 
							echo '</tr><tr>';
							$i= 0;
						}
						//if(checkRemoteFile($mirror.'/banners/graphical/'.$tmp['SeTvdbid'].'-g.jpg'))
							$img = '<img src="'.$mirror.'/banners/graphical/'.$tmp['SeTvdbid'].'-g.jpg" width="350" height="auto"/>';
						echo '<td><a href="consult.php?serieId='.$tmp['SeTvdbid'].'">'.$img.'</a></td>';
						$i++;
					}
					echo '</tr></table><br/><br/>';
					
					// FILMS
					$sql = 'select FiNom, FiMoviedbid from tbl_films order by FiNom';
					$res = mysql_query($sql);
					echo 'Films<hr/>';
					echo '<table><tr>';
					$rows = 8;
					$i = 0;
					while($tmp = mysql_fetch_array($res)) {
						$img = $tmp['FiNom'];
						if($i == $rows) { 
							echo '</tr><tr>';
							$i= 0;
						}
						$thumb = getMovieThumb($tmp['FiMoviedbid'], null);
						//if(checkRemoteFile($thumb))
							$img = '<img src="'.$thumb.'" width="auto" height="350"/>';
						echo '<td><a href="consult.php?serieId='.$tmp['FiMoviedbid'].'">'.$img.'</a></td>';
						$i++;
					}
					echo '</tr></table><br/><br/>';
					
					mysql_close($conn);
					
				?>
			</div>
		</div>
		<div id="footer"> <?php include 'footer.php'?> </div>
	</body>
</html>