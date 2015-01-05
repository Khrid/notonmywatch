<?php
	include 'inc.all.php';
	
	switch($_POST['values'][0]) {
		case 'addSerie':
				$conn = connectDb();
				$database = mysql_select_db($db['db'], $conn);
				$sql = 'INSERT INTO `db_series`.`tbl_series` (`PKNoSerie`, `SeTvdbid`, `SeNom`)  values (NULL, '.$_POST['values'][1].', "'.$_POST['values'][2].'")';
				//echo $sql;
				//INSERT INTO `db_series`.`tbl_series` (`PKNoSerie`, `SeTvdbid`, `SeNom`) VALUES (NULL, '12', 'b');
				$res = mysql_query($sql) or die(mysql_error());
				mysql_close($conn);
			break;
		case 'addFilm':
				$conn = connectDb();
				$database = mysql_select_db($db['db'], $conn);
				$sql = 'INSERT INTO `db_series`.`tbl_films` (`PKNoFilm`, `FiMoviedbid`, `FiNom`)  values (NULL, '.$_POST['values'][1].', "'.$_POST['values'][2].'")';
				//echo $sql;
				//INSERT INTO `db_series`.`tbl_series` (`PKNoSerie`, `SeTvdbid`, `SeNom`) VALUES (NULL, '12', 'b');
				$res = mysql_query($sql) or die(mysql_error());
				mysql_close($conn);
			break;
		default:
			echo 'beh';
			break;
	}

?>