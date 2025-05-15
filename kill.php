<?php
session_start();
$session = session_id();
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$id = intval($id);
	$ds_id = $id;
} else {
	echo "<label><div align='center'><big>Fehler: Keine ID übergeben!</big></div></label><br>";
	exit;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aenderungsprotokoll</title>
	<link rel="stylesheet" href="style.css">
	<script type="text/javascript" src="scripts/dhtml.js"></script>
	<script type="text/javascript" src="media/js/jquery.js"></script>
	<script type="text/javascript" src="media/js/jquery.dataTables.js"></script>
	<script type="text/javascript">
		function ZeitAnzeigen() {
			const wochentagname = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
			const jetzt = new Date();
			const datum = jetzt.toLocaleDateString('de-DE', {
				weekday: 'long',
				year: 'numeric',
				month: '2-digit',
				day: '2-digit'
			});
			const uhrzeit = jetzt.toLocaleTimeString('de-DE');
			const gesamt = `${datum}, ${uhrzeit}`;

			setContent("id", "Uhr", null, NS4 ? `<span class="Uhr">${gesamt}</span>` : gesamt);
			window.setTimeout(ZeitAnzeigen, 1000);
		}
	</script>

</head>

<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">
	<?php
	include "connect.php";
	// echo "USER: ".$_POST["username"];;
	if (!isset($_SESSION["username"])) {
		$stat_bild = "<img src=bilder/user-offline.png>";
		$stat_schrift = "<font color='red'>Offline</font>";
	} else {
		$stat_bild = "<img src=bilder/user-online.png>";
		$stat_schrift = "<font color='green'>Online</font>";
	}
	// echo $stat_bild; 
	$ma_aktiv = "Gast";
	if (isset($_SESSION["username"])) {
		$user = $_SESSION["username"];
		$sql_user = mysqli_query($conn, "Select abt, nachname, vorname from adlogin where username = '$user'");
		if (mysqli_num_rows($sql_user)) # falls der Login von der Änderungsverwaltung kommt
		{
			$row = mysqli_fetch_assoc($sql_user);
			$abt = $row['abt'];
			$nachname = $row['nachname'];
			$vorname = $row['vorname'];
			$ma_aktiv = $abt . " / " . $vorname . " " . $nachname;
		}
	}
	?>

	<div class="container">
		<div class="header">
			<h1 class="Ueberschrift">EPSa Änderungsverwaltung</h1>
		</div>
		<div class="navigation">
			<div class="nav-item"><a href="start.php">Startseite</a></div>
			<div class="nav-item"><a href="login-eingabe.php">Login</a></div>
			<div class="nav-item"><a href="logout.php">Logout</a></div>
			<div class="nav-item"><a href="viewlist.php">Übersicht</a></div>
			<div class="nav-item"><a href="erzeugen.php">Erzeugen</a></div>
			<div class="nav-item"><a href="usercreate.php">Benutzer anlegen</a></div>
		</div>
	</div>

	<div class="user-info">

	</div>


	<?php
	// nicht angemeldet??
	if (!isset($_SESSION["username"])) {
		echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
		exit;
	}
	// kill.php direkt gestartet??
	if (!isset($id)) {
		echo "<label><div align='center'><big>Bitte dieses Formular nicht direkt aufrufen!<br>
			  Das gehört sich nicht!!  <a href=\"start.php\">Startseite</a></big></div></label><br>";
		exit;
	}

	?>
	<?php
	include "connect.php";
	// *******************************************************************************************************************
	// Nur der Besitzer der Änderung darf Löschen!! Er muss in PV sein und die Änderung darf weder von PP,PF oder PV freigegeben sein
	// Der Link wird über die ID geholt, da man das auch manuell machen kann muss geprüft werden, ob es auch der Owner ist
	// *******************************************************************************************************************

	// Username zur übermittelten ID holen und prüfen 
	$ds_id = $id;
	$log_user = $_SESSION["username"];
	// Username, Zustand und Abteilung zur übermittelten ID holen und prüfen 
	$sql_user = mysqli_query($conn, "select
							z.user_id,
							z.dat_ez,
							d.tbsachnr,
							d.tbepsanr,
							d.tbbez,
							d.id,
							a.username,
							a.abt
						from 
							zustand as z
							LEFT OUTER JOIN data AS d ON d.id = z.aend_id
							LEFT OUTER JOIN adlogin AS a ON a.id = z.user_id
						where
							z.stat_pv='0' AND z.stat_pf='0' AND z.stat_pp='0' AND z.stat_pg='0' and dat_ez is not null and username = '$log_user' and d.id ='$ds_id' and a.abt ='pv'");
	// Die SQL wirft entweder 1 Ergebnis aus oder gar keines!!							
	if (!$sql_user) {
		//Datenbankfehler
		die("Datenbankfehler: " . mysqli_errno($conn));
	} elseif (mysqli_num_rows($sql_user) == 0) {
		echo "<label><div align='center'><big>Dazu fehlt ihnen die Berechtigung... <a href=\"start.php\">Startseite</a></big></div></label><br>";
		exit;
	} else {
		$user_id = mysqli_fetch_assoc($sql_user)['user_id'];
		// hier sind wir richtig -> der User darf den DS löschen!!
		$eintrag = "DELETE from data where id = '$ds_id'";
		$eintragen = mysqli_query($conn, $eintrag);

		$meldungen = array();

		if ($eintragen == true) {
			$meldungen[] = "<h3>Änderung wurde gelöscht</h3>";
		} else {
			$meldungen[] = "<p>Fehler beim Löschen der Änderung!! M. Jäger informieren</p>";
		}

		$eintrag = "DELETE from zustand where aend_id = '$ds_id'";
		$eintragen = mysqli_query($conn, $eintrag);

		if ($eintragen == true) {
			$meldungen[] = "";
		} else {
			$meldungen[] = "<p>Fehler beim Löschen der Zustandsdaten!! M. Jäger informieren</p>";
		}

		// LOG erzeugen
		$eintrag = "INSERT INTO log (aend_id, user_id, vorgang) VALUES ('$ds_id', '$user_id', 'PV Aenderung geloescht')";
		$eintragen = mysqli_query($conn, $eintrag);

		if ($eintragen == true) {
			$meldungen[] = "<h4>Log wurde aktualisiert.</h4>";
		} else {
			$meldungen[] = "<p>Fehler bei, aktualisieren des LOG-Files!! M. Jäger informieren</p>";
		}

		echo "<div class=\"login-form\"><div class=\"login-header\"><h2>Zustandsdaten wurden gelöscht</h2></div>";
		
		foreach ($meldungen as $meldung) {
			echo $meldung;
		}
		echo "</div>";
	}


	?>



</body>
<script type='text/javascript'>
	setTimeout(function() {
		window.location.href = 'start.php'; // URL hier anpassen
	}, 2000);
</script>"

</html>