<?php
session_start();
$session = session_id();

// Weiterleitung auf start.php nach 2 Sekunden
echo '<script type="text/javascript">
</script>';

if (!isset($_SESSION["username"])) {
	echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}

// Sicherheit: Prüfen ob $_POST['ds_id'] gesetzt und numerisch ist
if (!isset($_POST['ds_id']) || !is_numeric($_POST['ds_id'])) {
	echo "<label><div align='center'><big>Ungültige Datensatz-ID. <a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}

$ds_id = $_POST['ds_id'];

require("connect.php");

// Hole den Datensatz und prüfe die Berechtigung
$user = $_SESSION["username"];

// Prepared Statement mit explizitem JOIN
$sql_user = "SELECT
                z.user_id,
                z.dat_ez,
                d.tbsachnr,
                d.tbepsanr,
                d.tbbez,
                d.id,
                a.username,
                a.abt
            FROM
                zustand AS z
            LEFT OUTER JOIN data AS d ON d.id = z.aend_id
            LEFT OUTER JOIN adlogin AS a ON a.id = z.user_id
            WHERE
                z.stat_pv='0' AND z.stat_pf='0' AND z.stat_pp='0' AND z.stat_pg='0' AND z.dat_ez IS NOT NULL AND a.username = ? AND d.id = ? AND a.abt = 'pv'";

if ($stmt_user = mysqli_prepare($conn, $sql_user)) {
	mysqli_stmt_bind_param($stmt_user, "si", $user, $ds_id);
	mysqli_stmt_execute($stmt_user);
	$result_user = mysqli_stmt_get_result($stmt_user);

	if (!$result_user) {
		echo "<label><div align='center'><big>Fehler bei der Datenbankabfrage: " . mysqli_error($conn) . "<br><a href=\"start.php\">Startseite</a></big></div></label><br>";
		exit;
	}

	$user_data = mysqli_fetch_assoc($result_user);
	mysqli_stmt_close($stmt_user);

	if (!$user_data) {
		echo "<label><div align='center'><big>Sie haben keine Berechtigung an diesem Datensatz Änderungen vorzunehmen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
		exit;
	}

	$user_id = $user_data['user_id'];
	$user_abt = $user_data['abt'];
} else {
	echo "<label><div align='center'><big>Fehler bei der Datenbankabfrage: " . mysqli_error($conn) . "<br><a href=\"start.php\">Startseite</a></big></div></label><br>";
	exit;
}

// Daten aus dem Formular holen und für das Update vorbereiten
// 1. Checkboxen (mit ternärem Operator)
$chpf = isset($_POST['chpf']) ? 1 : 0; // PF
$chpp = isset($_POST['chpp']) ? 1 : 0; // PP
$chpg = isset($_POST['chpg']) ? 1 : 0; // PG
$chversion = isset($_POST['chversion']) ? 1 : 0; // Version
$chrev = isset($_POST['chrev']) ? 1 : 0; // Revision
$chepsa = isset($_POST['chepsa']) ? 1 : 0; // neue EPSanummer
$chstueli = isset($_POST['chstueli']) ? 1 : 0; // Stücklistenänderung
$chrohs = isset($_POST['chrohs']) ? 1 : 0; // RoHS
$chnutzen = isset($_POST['chnutzen']) ? 1 : 0; // Nutzen

// 2. Update-Query mit Prepared Statement
$sql_update = "UPDATE data SET 
                chpf = ?,
                chpp = ?,
                chpg = ?,
                chversion = ?,
                chrev = ?,
                chepsa = ?,
                chstueli = ?,
                chrohs = ?,
                chnutzen = ?,
                pbsapb = ?,
                pbsapl = ?,
                pbsaiap = ?,
                pbsnp = ?,
                pbsnpabap = ?,
                plsapb = ?,
                plsapl = ?,
                plsaiap = ?,
                plsnp = ?, 
                plsnpabap = ?,
                sbsbbfav = ?, 
                sbsbgw = ?,
                sbsbwv = ?, 
                sbsnpabap = ?,
                slsbbfav = ?, 
                slsbgw = ?,
                slsbwv = ?, 
                slsnpabap = ?,
                sosbbfav = ?, 
                sosbgw = ?,
                sosae = ?, 
                sosnv = ?,
                spsbbfav = ?, 
                spsbgw = ?,
                spsae = ?, 
                spsnv = ?,
                tbpv = ?, 
                tbpf = ?,
                tbpp = ?, 
				tbpg = ?,
                tbhinweis = ?,
                tbtechno = ?, 
                tbbez = ?,
                tbprog = ?, 
                tbsachnr = ?,
                tbepsanr = ?, 
                tbbslchange = ?
            WHERE
                id = ?";

if ($stmt_update = mysqli_prepare($conn, $sql_update)) {

	mysqli_stmt_bind_param(
		$stmt_update,
		"iiiiiiiiisssssssssssssssssssssssssssssssssssssi", 
		$chpf,
		$chpp,
		$chpg,
		$chversion,
		$chrev,
		$chepsa,
		$chstueli,
		$chrohs,
		$chnutzen,
		$_POST['pbsapb'],
		$_POST['pbsapl'],
		$_POST['pbsaiap'],
		$_POST['pbsnp'],
		$_POST['pbsnpabap'],
		$_POST['plsapb'],
		$_POST['plsapl'],
		$_POST['plsaiap'],
		$_POST['plsnp'],
		$_POST['plsnpabap'],
		$_POST['sbsbbfav'],
		$_POST['sbsbgw'],
		$_POST['sbsbwv'],
		$_POST['sbsnpabap'],
		$_POST['slsbbfav'],
		$_POST['slsbgw'],
		$_POST['slsbwv'],
		$_POST['slsnpabap'],
		$_POST['sosbbfav'],
		$_POST['sosbgw'],
		$_POST['sosae'],
		$_POST['sosnv'],
		$_POST['spsbbfav'],
		$_POST['spsbgw'],
		$_POST['spsae'],
		$_POST['spsnv'],
		$_POST['tbpv'],
		$_POST['tbpf'],
		$_POST['tbpp'],
		$_POST['tbpg'],
		$_POST['tbhinweis'],
		$_POST['tbtechno'],
		$_POST['tbbez'],
		$_POST['tbprog'],
		$_POST['tbsachnr'],
		$_POST['tbepsanr'],
		$_POST['tbbslchange'],
		$ds_id
	);

	if (mysqli_stmt_execute($stmt_update)) {
		echo "<div id='text'><h2> Änderungsantrag erfolgreich gespeichert! </h2> </div>";
	} else {
		echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Update!<br>
            Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id<br>" . mysqli_error($conn) . "</font></strong></b></td></tr></table>";
	}

	mysqli_stmt_close($stmt_update);
} else {
	echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Vorbereiten des Updates!<br>
        Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id<br>" . mysqli_error($conn) . "</font></strong></b></td></tr></table>";
}


$log_ins = "INSERT INTO log (user_id, vorgang, aend_id) VALUES (?, 'Update', ?)";

if ($stmt_log = mysqli_prepare($conn, $log_ins)) {
	mysqli_stmt_bind_param($stmt_log, "ii", $user_id, $ds_id);
	if (!mysqli_stmt_execute($stmt_log)) {
		echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Schreiben des Logs!<br>
		Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id<br>" . mysqli_error($conn) . "</font></strong></b></td></tr></table>";
	}

	mysqli_stmt_close($stmt_log);
} else {
	echo "<table width='100%'><tr><td align ='center'><br><br><b><strong><font color='red'>Fehler beim Vorbereiten des Log-Eintrags!<br>
	Bitte informieren Sie M.Jäger (216) -> Datensatz: $ds_id<br>" . mysqli_error($conn) . "</font></strong></b></td></tr></table>";
}

mysqli_close($conn); ?>

<script type="text/javascript">
    setTimeout(function() { window.close(); }, 1200);
</script>

<style>
	body {
	background-color: #212c4d;
	color: white;
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