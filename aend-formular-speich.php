<?php

session_start();
$session = session_id();

if (!isset($_SESSION["username"])) {
	echo "Bitte erst <a href=\"login.html\">einloggen</a>";
	exit;
}

require("connect.php"); // Nutzt jetzt mysqli in connect.php

$user = $_SESSION["username"];

// Neue Abfrage mit Prepared Statement
$stmt = $conn->prepare("SELECT id FROM adlogin WHERE username =?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$user_row = $result->fetch_assoc();
$user_id = $user_row['id'];

$datzeit = date("Y-m-d H:i:s");

// Variablen aus dem Formular übernehmen
$pbsapb = isset($_POST['pbsapb']) && !empty($_POST['pbsapb']) ? $_POST['pbsapb'] : null;
$pbsapl = isset($_POST['pbsapl']) ? $_POST['pbsapl'] : '';
$pbsaiap = isset($_POST['pbsaiap']) ? $_POST['pbsaiap'] : '';
$pbsnp = isset($_POST['pbsnp']) ? $_POST['pbsnp'] : '';
$pbsnpabap = isset($_POST['pbsnpabap']) ? $_POST['pbsnpabap'] : '';
$plsapb = isset($_POST['plsapb']) ? $_POST['plsapb'] : '';
$plsapl = isset($_POST['plsapl']) ? $_POST['plsapl'] : '';
$plsaiap = isset($_POST['plsaiap']) ? $_POST['plsaiap'] : '';
$plsnp = isset($_POST['plsnp']) ? $_POST['plsnp'] : '';
$plsnpabap = isset($_POST['plsnpabap']) ? $_POST['plsnpabap'] : '';
$sbsbbfav = isset($_POST['sbsbbfav']) ? $_POST['sbsbbfav'] : '';
$sbsbgw = isset($_POST['sbsbgw']) ? $_POST['sbsbgw'] : '';
$sbsbwv = isset($_POST['sbsbwv']) ? $_POST['sbsbwv'] : '';
$sbsnpabap = isset($_POST['sbsnpabap']) ? $_POST['sbsnpabap'] : '';
$slsbbfav = isset($_POST['slsbbfav']) ? $_POST['slsbbfav'] : '';
$slsbgw = isset($_POST['slsbgw']) ? $_POST['slsbgw'] : '';
$slsbwv = isset($_POST['slsbwv']) ? $_POST['slsbwv'] : '';
$slsnpabap = isset($_POST['slsnpabap']) ? $_POST['slsnpabap'] : '';
$sosbbfav = isset($_POST['sosbbfav']) ? $_POST['sosbbfav'] : '';
$sosbgw = isset($_POST['sosbgw']) ? $_POST['sosbgw'] : '';
$sosae = isset($_POST['sosae']) ? $_POST['sosae'] : '';
$sosnv = isset($_POST['sosnv']) ? $_POST['sosnv'] : '';
$spsbbfav = isset($_POST['spsbbfav']) ? $_POST['spsbbfav'] : '';
$spsbgw = isset($_POST['spsbgw']) ? $_POST['spsbgw'] : '';
$spsae = isset($_POST['spsae']) ? $_POST['spsae'] : '';
$spsnv = isset($_POST['spsnv']) ? $_POST['spsnv'] : '';
$tbhinweis = isset($_POST['tbhinweis']) ? $_POST['tbhinweis'] : '';
$tbtechno = isset($_POST['tbtechno']) ? $_POST['tbtechno'] : '';
$tbprog = isset($_POST['tbprog']) ? $_POST['tbprog'] : '';
$tbsachnr = isset($_POST['tbsachnr']) ? $_POST['tbsachnr'] : '';

$tbbslchange = isset($_POST['tbbslchange']) ? $_POST['tbbslchange'] : '';

// Standardwerte für readonly-Felder, wenn nicht im $_POST
$tbpv = isset($_POST['tbpv']) ? $_POST['tbpv'] : '';
$tbpf = isset($_POST['tbpf']) ? $_POST['tbpf'] : '';
$tbpp = isset($_POST['tbpp']) ? $_POST['tbpp'] : '';
$tbpg = isset($_POST['tbpg']) ? $_POST['tbpg'] : '';
$tbbez = isset($_POST['tbbez']) ? $_POST['tbbez'] : '';
$tbepsanr = isset($_POST['tbepsanr']) ? $_POST['tbepsanr'] : '';

// chpv initialisieren, da es nicht im Formular gesendet wird
$chpv = 0;

// Checkboxen
$chpf = isset($_POST['chpf']) ? 1 : 0;
$chpp = isset($_POST['chpp']) ? 1 : 0;
$chpg = isset($_POST['chpg']) ? 1 : 0;
$chversion = isset($_POST['chversion']) ? 1 : 0;
$chrev = isset($_POST['chrev']) ? 1 : 0;
$chepsa = isset($_POST['chepsa']) ? 1 : 0;
$chstueli = isset($_POST['chstueli']) ? 1 : 0;
$chrohs = isset($_POST['chrohs']) ? 1 : 0;
$chnutzen = isset($_POST['chnutzen']) ? 1 : 0;

// Prepared Statement für INSERT
$stmt = $conn->prepare("INSERT INTO data (user_id, chpv, chpf, chpp, chpg, chversion, chrev, chepsa, chstueli, chrohs, chnutzen, pbsapb, pbsapl, pbsaiap, pbsnp, pbsnpabap, plsapb, plsapl, plsaiap, plsnp, plsnpabap, sbsbbfav, sbsbgw, sbsbwv, sbsnpabap, slsbbfav, slsbgw, slsbwv, slsnpabap, sosbbfav, sosbgw, sosae, sosnv, spsbbfav, spsbgw, spsae, spsnv, tbpv, tbpf, tbpp, tbpg, tbhinweis, tbtechno, tbbez, tbprog, tbsachnr, tbepsanr, tbbslchange) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->bind_param(
	"iiiiiiiiiissssssssssssssssssssssssssssssssssssss",
	$user_id,
	$chpv,
	$chpf,
	$chpp,
	$chpg,
	$chversion,
	$chrev,
	$chepsa,
	$chstueli,
	$chrohs,
	$chnutzen,
	$pbsapb,
	$pbsapl,
	$pbsaiap,
	$pbsnp,
	$pbsnpabap,
	$plsapb,
	$plsapl,
	$plsaiap,
	$plsnp,
	$plsnpabap,
	$sbsbbfav,
	$sbsbgw,
	$sbsbwv,
	$sbsnpabap,
	$slsbbfav,
	$slsbgw,
	$slsbwv,
	$slsnpabap,
	$sosbbfav,
	$sosbgw,
	$sosae,
	$sosnv,
	$spsbbfav,
	$spsbgw,
	$spsae,
	$spsnv,
	$tbpv,
	$tbpf,
	$tbpp,
	$tbpg,
	$tbhinweis,
	$tbtechno,
	$tbbez,
	$tbprog,
	$tbsachnr,
	$tbepsanr,
	$tbbslchange
);

if ($stmt->execute()) {
	// Erfolgsmeldung
	echo "<div id='text'><h2> Eintrag wird gespeichert </h2> </div>";
?>

	<script>
		setTimeout(function() {
			if (window.opener) {
				window.opener.postMessage('speichernErfolgreich', '*');
			}
		}, 500);
	</script>
<?php
	// ID des zuletzt eingefügten Datensatzes abrufen
	$aend_id = $conn->insert_id;

	// Zur Fehlersuche  // var_dump($aend_id, $user_id, $datzeit);

	// Prepared Statement für INSERT in zustand
	$stmt_zustand = $conn->prepare("INSERT INTO zustand (aend_id, user_id, dat_ez) VALUES (?,?,?)"); // Objekt erstellen

	$stmt_zustand->bind_param("iis", $aend_id, $user_id, $datzeit); // Originale Variablen verwenden
	$stmt_zustand->execute();

	// Prepared Statement für INSERT in log
	$stmt_log = $conn->prepare("INSERT INTO log (aend_id, user_id, vorgang) VALUES (?,?,?)");
	$vorgang = 'PV Aenderung erzeugt'; // Variable für den String erstellen
	$stmt_log->bind_param("iis", $aend_id, $user_id, $vorgang); // Variable übergeben
	$stmt_log->execute();
} else {
	// Fehlermeldung
	echo "Fehler beim Speichern des Eintrags: " . $stmt->error;
}

$stmt->close();
$conn->close(); ?>

<script type="text/javascript">
	setTimeout(function() {
		window.close();
	}, 1200);
</script>

<style>
	body {
		background-color: white;
		color: black;
		text-align: center;
		padding: 40px;
	}

	#text {
		display: flex;
		justify-content: center;
		font-family: Arial, Helvetica, sans-serif;
		margin-top: 20px;
	}
</style>

<body class="meldung">
</body>