<?php
session_start();
$session = session_id();
$ds_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($ds_id) || !is_numeric($ds_id)) {
	echo "<div class='login-form'><div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><h1>Fehler</h1><p>Ungültige Datensatz-ID.</p></div></div></div>";
	exit;
}
$suser_abt = $_SESSION['user_abt_log'];
$suser_id = $_SESSION['user_id_log']; ?>
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aenderungsprotokoll</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	
	<div class="login-form">
		<?php
		// Gäste werden direkt abgeschossen
		if (!isset($_SESSION["username"])) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><h1>Fehler</h1><p>Bitte erst einloggen...</p></div></div>";
			exit;
		}
		?>
		<?php
		include "connect.php";
		$connection = new mysqli($servername, $username, $password, $dbname);
		if ($connection->connect_error) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><h1>Fehler</h1><p>Datenbankverbindung fehlgeschlagen: " . $connection->connect_error . "</p></div></div>";
			exit;
		}
		// *****************************************************************************************************************************
		// Freigabe durch PF!!
		// $id kommt aus dem Link
		// prüfen ob es eine gültige ID gibt (das das Formular nicht direkt gestartet wurde!!)
		// *****************************************************************************************************************************
		//$ds_id = $id;
		$id = $ds_id;
		$log_user = $_SESSION["username"];
		$sql_data = $connection->query("SELECT data.id as data_id
                                        FROM 
                                            data LEFT OUTER JOIN zustand ON zustand.aend_id = data.id
                                        WHERE
                                           data.id = '$ds_id' AND zustand.stat_$suser_abt='0' AND ((data.user_id = '$suser_id') OR (data.ch$suser_abt = '1' AND zustand.stat_pv = '1'))
                                               ");
		if (!$sql_data) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><p>Datenbankfehler: " . $connection->error . "</p></div></div>";
			exit;
		} else if ($sql_data->num_rows == 0) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'>><p>Diese Aktion ist nicht erlaubt...</p></div></div>";
			exit;
		} elseif ($sql_data->num_rows > 1) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'>><p>Der Datensatz mit der ID: $ds_id trat mehrfach auf!<br>Bitte informieren Sie M.Jäger (216)!!</p></div></div>";
			exit;
		}

		$data_id = $sql_data->fetch_assoc()["data_id"];

		$user = $_SESSION["username"];
		$big_abt = strtoupper($suser_abt);
		$zustand = "UPDATE zustand SET stat_$suser_abt = 1, dat_$suser_abt = NOW() WHERE aend_id = '$ds_id'";

		$eintr_zustand = $connection->query($zustand);

		if (!$eintr_zustand) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><p>Fehler beim Speichern!<br>Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id</p></div></div>";
			exit;
		}
		// User ID in den Datensatz schreiben zwecks erledigt
		$dataset = "Update data SET tb$suser_abt = '$suser_id' where id = '$ds_id'";
		$eintr_dataset = $connection->query($dataset);

		if (!$eintr_dataset) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><p>Fehler beim Speichern des Namens!<br>Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id</p></div></div>";
			exit;
		}
		// LOG erzeugen
		$log_ins = "INSERT INTO log (aend_id, user_id, vorgang) VALUES ('$ds_id', '$suser_id', '$big_abt Aenderung freigegeben')";
		$eintr_log = $connection->query($log_ins);

		if (!$eintr_log) {
			echo "<div class='login-header'><h2>Fehler</h2></div><div class='login-body'><div class='meldung'><p>Fehler beim LOG!<br>Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id</p></div></div>";
			exit;
		}

		echo "<div class='login-header'><h2>Erfolgreich Freigegeben</h2></div><div class='login-body'><div class='meldung'><h3>Datensatz wurde gespeichert</h3></div></div>";
		?>
	</div>
	<script type='text/javascript'>
		setTimeout(function() {
			window.location.href = 'start.php'; // URL hier anpassen
		}, 2000);
	</script>
</body>

</html>