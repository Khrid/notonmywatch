<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/func.js"></script>
		<title>Admin</title>
	</head>
<body>
<div id="container">
<?php include 'inc/inc.menu.php'; ?>
<div id="content">
<?php
	include 'inc/inc.all.php';
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		if(isset($_GET['do'])) {
			$do = $_GET['do'];
			switch($do) {
				case 'deleteSerie':
					echo 'delete this serie';
					$conn = connectDb();
					$database = mysql_select_db($db['db'], $conn);
					$sql = 'delete from tbl_series where PKNoSerie = '.$id;
					$res = mysql_query($sql);	
					mysql_close($conn);
					header('Location: admin.php');   
					break;
				case 'deleteFilm':
					echo 'delete this film';
					$conn = connectDb();
					$database = mysql_select_db($db['db'], $conn);
					$sql = 'delete from tbl_films where PKNoFilm = '.$id;
					$res = mysql_query($sql);	
					mysql_close($conn);
					header('Location: admin.php');   
					break;
			}
		}		
	} else {
		$conn = connectDb();
		$database = mysql_select_db($db['db'], $conn);
		$sql = 'select PKNoSerie, SeNom, SeTvdbid from tbl_series order by SeNom';
		$res = mysql_query($sql);
		echo 'Séries : ';
		echo '<table>';
		while($tmp = mysql_fetch_array($res)) {
			echo '<tr><td>'.$tmp['SeNom'].'</td><td><a href="admin.php?id='.$tmp['PKNoSerie'].'&do=deleteSerie"><img src="css/img/delete.png"/></a></td></tr>';
		}
		echo '</table><br/>';
		echo 'Films : ';
		$sql = 'select PKNoFilm, FiNom, FiMoviedbid from tbl_films order by FiNom';
		$res = mysql_query($sql);
		echo '<table>';
		while($tmp = mysql_fetch_array($res)) {
			echo '<tr><td>'.$tmp['FiNom'].'</td><td><a href="admin.php?id='.$tmp['PKNoFilm'].'&do=deleteFilm"><img src="css/img/delete.png"/></a></td></tr>';
		}
		echo '</table>';
		mysql_close($conn);
	}
?>
</div>
</body>
</html>