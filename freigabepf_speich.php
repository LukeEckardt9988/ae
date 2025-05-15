<?php
session_start();
$session = session_id();
// FALSCH: echo "USER: ".$_SESSION["username"]." / ID: ".$_SESSION["user_id_log"]."<br>";
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$id = intval($id);
	$ds_id = $id;
} else {
	echo "<label><div align='center'><big>Fehler: Keine ID übergeben!</big></div></label><br>";
	exit;
}
?>
<script type="text/javascript">
	<!-- 
	setTimeout("self.location.href='start.php'", 2000);
	//
	-->
</script>
<?php
// Gäste werden direkt abgeschossen
if (!isset($_SESSION["username"])) {
	echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}
?>
<?php
include "connect.php";
// *****************************************************************************************************************************
// Freigabe durch PF!!
// $id kommt aus dem Link
// prüfen ob es eine gültige ID gibt (das das Formular nicht direkt gestartet wurde!!)
// *****************************************************************************************************************************
$ds_id = $id;
$tbbslchange = $_POST['tbbslchange'];
$log_user = $_SESSION["username"];
$suser_id = $_SESSION["user_id_log"];
$suser_abt = strtolower($_SESSION["user_abt_log"]);
//echo "USER: ".$log_user." / ID: ".$_SESSION["user_id_log"]." / ABT: ".$_SESSION["user_abt_log"];
// Username, Zustand und Abteilung zur übermittelten ID holen und prüfen 
$sql_data = mysqli_query($conn, "SELECT data.id as data_id
						FROM 
							data LEFT OUTER JOIN zustand ON zustand.aend_id = data.id
						WHERE
						   data.id = '$ds_id' AND zustand.stat_$suser_abt='0' AND ((data.user_id = '$suser_id') OR (data.ch$suser_abt = '1' AND zustand.stat_pv = '1'))
							   ");
if (!$sql_data) {
	echo "<label><div align='center'><big>Datenbankfehler: " . mysqli_error($conn) . " <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
} else if (mysqli_num_rows($sql_data) == 0) {
	echo "<label><div align='center'><big>Diese Aktion ist nicht erlaubt... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
} elseif (mysqli_num_rows($sql_data) > 1) {
	echo "<label><div align='center'><big>Der Datensatz mit der ID: $ds_id trat mehrfach auf!<br>Bitte informieren Sie M.Jäger (216)!! <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}

$data_id = mysqli_fetch_assoc($sql_data)['data_id'];

$user = $_SESSION["username"];
$big_abt = strtoupper($suser_abt);

// DateTime erzeuschen und zustand updaten

$datzeit = date("Y-m-d H:i:s");
$zustand = "Update zustand SET stat_$suser_abt = '1', dat_$suser_abt = '$datzeit' where aend_id = '$ds_id'";
$eintr_zustand = mysqli_query($conn, $zustand);

if (!$eintr_zustand) {
	echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Speichern!<br>
								Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id
								</font></strong></b></td></tr></table>";
	exit;
}
// User ID in den Datensatz schreiben zwecks erledigt
// Hier muss auch der Kommentar ausgetauscht werden!!!
$dataset = "Update data SET tb$suser_abt = '$suser_id', tbbslchange = '$tbbslchange' where id = '$ds_id'";
$eintr_dataset = mysqli_query($conn, $dataset);

if (!$eintr_dataset) {
	echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Speichern des Namens!<br>
								Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id
								</font></strong></b></td></tr></table>";
	exit;
}
// LOG erzeugen
$log_ins = "INSERT INTO log (aend_id, user_id, vorgang) VALUES ('$ds_id', '$suser_id', '$big_abt Aenderung freigegeben')";
$eintr_log = mysqli_query($conn, $log_ins);

if (!$eintr_log) {
	echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim LOG!<br>
								Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id
								</font></strong></b></td></tr></table>";
	exit;
}

echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='green'>Datensatz wurde gespeichert</font></strong></b></td></tr></table>";
?>