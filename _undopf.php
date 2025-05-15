<?php
session_start();
$session = session_id();
?>
<script type="text/javascript">
<!-- 
setTimeout("self.location.href='start.php'",2000);
//-->
</script>
<?php
if (!isset ($_SESSION["username"])) {
  echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
  exit;
}
?>
<?php
include "connect.php";
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}
// *****************************************************************************************************************************
// Freigabe von PV soll von PF aufgehoben werden!!
// $id kommt aus dem Link
$sql_user = $mysqli->query("SELECT  
								adlogin.username,
								adlogin.abt,
								zustand.stat_pv,
								zustand.stat_pf,
								zustand.dat_pv,
								zustand.dat_pf
						FROM
								adlogin, data, zustand
						WHERE
							   data.id = '$ds_id' AND zustand.aend_id = data.id AND adlogin.username = '$log_user'");
							   
$row = $sql_user->fetch_assoc();
$user_name = $row["username"];	
$user_abt = $row["abt"];
$stat_pv = $row["stat_pv"];
$stat_pf = $row["stat_pf"];
$dat_pv = $row["dat_pv"];
$dat_pf = $row["dat_pf"];
$user_abt = $row["abt"];
$stat_pv = $row["stat_pv"];
$stat_pf = $row["stat_pf"];
$dat_pv = $row["dat_pv"];
$dat_pf = $row["dat_pf"];

if ($user_abt<>'PF' OR isset($stat_pf) or is_null($stat_pv))
	 {
		// echo "SQL: ".$sql_user."<br> DS Id: ".$ds_id."<br> USER: ".$log_user."<br> Abteilung: ".$user_abt."<br> Status PF: ".$stat_pf."<br> Status PV: ".$stat_pv."<br>";
	
		echo "<label><div align='center'><big>Sie haben keine Berechtigung diese Aenderung für PV freizugeben... <a href=\"start.php\">Startseite</a></big></div></label><br>"; 
		exit;
	 }
$user = $_SESSION["username"];
$sql_user = $mysqli->query("SELECT id FROM adlogin WHERE username = '$user'");
$row = $sql_user->fetch_assoc();
$user_id = $row["id"];



$user = $_SESSION["username"];
	$eintr_zustand = $mysqli->query($zustand);
$user_id = $row["id"];


	$eintr_log = $mysqli->query($log_ins);
	$datzeit=date("Y-m-d H:i:s");
	$zustand = "Update zustand SET stat_pv = NULL, dat_pv = NULL where aend_id = '$ds_id'";
	$eintr_zustand = $mysqli->query($zustand);

	// LOG erzeugen
	$log_ins = "INSERT INTO log (aend_id, user_id, vorgang) VALUES ('$ds_id', '$user_id', 'PF Freigabe von PV aufgehoben')";	
	$eintr_log = $mysqli->query($log_ins);

if ($eintr_zustand) echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='green'>PV Freigabe wird aufgehoben...</font></strong></b></td></tr></table>"; 
// echo $eintrag."<br>";
// echo mysql_errno() . ": " . mysql_error() . "\n";
// hier die Druckvariante aufrufen
// include ('aend-formular-print.php');
?>