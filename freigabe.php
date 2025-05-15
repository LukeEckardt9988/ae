<?php
session_start();
$session = session_id();
?>
<script type="text/javascript">
	<!-- 
	setTimeout("self.location.href='start.php'", 2000);
	//
	-->
</script>
<?php
if (!isset($_SESSION["username"])) {
	echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}
?>
<?php
include "connect.php";
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
	die("Connection failed: " . $connection->connect_error);
}
// *****************************************************************************************************************************
// Änderung soll von PV freigegeben werden!!
// $id kommt aus dem Link
$sql_user = $connection->query("SELECT adlogin.username
								FROM adlogin, data
								WHERE data.id = $id AND data.user_id = adlogin.id");
$user_name = $sql_user->fetch_assoc()['username'];
$user_name = $sql_user->fetch_assoc()['username'];


if ($_SESSION["username"] <> $user_name) {
	echo "<label><div align='center'><big>Sie haben keine Berechtigung diese Änderung zu bearbeiten... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}



$sql_user = $connection->query("SELECT id FROM adlogin WHERE username = '$user'");
$user_id = $sql_user->fetch_assoc()['id'];
$user_id = $sql_user->fetch_assoc()['id'];


// DateTime erzeuschen und zustand updaten
$datzeit = date("Y-m-d H:i:s");
$eintr_zustand = $connection->query($zustand);
$eintr_zustand = $connection->query($zustand);

// LOG erzeugen
$eintr_log = $connection->query($log_ins);
$eintr_log = $connection->query($log_ins);

if ($eintr_zustand) echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='green'>Freigabe wird gespeichert...</font></strong></b></td></tr></table>";
// echo $eintrag."<br>";
// echo mysql_errno() . ": " . mysql_error() . "\n";
// hier die Druckvariante aufrufen
// include ('aend-formular-print.php');
?>