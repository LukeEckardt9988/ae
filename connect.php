<?php
// Datenbank-Zugangsdaten
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "pv_aend_sl";
/*
$servername = "ubserv01";
$username = "pv_aend_sl";
$password = "YS@RxNY25z9Cm5L.";
$dbname = "pv_aend_sl"; */

// Datenbankverbindung herstellen
try {
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
} catch (Exception $e) {
	// Fehler loggen und Skript beenden
	error_log("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
	die("Datenbankverbindung fehlgeschlagen");
}

if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$host = gethostbyaddr($ip);

?>
