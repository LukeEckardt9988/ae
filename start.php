<?php
session_start();
$session = session_id();
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<script type="text/javascript">
		$(document).ready(function() {
			$('#example').dataTable({
				"oLanguage": {
					"sLengthMenu": "Zeige _MENU_ Einträge pro Seite",
					"sZeroRecords": "Nichts gefunden - sorry",
					"sInfo": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
					"sInfoEmpty": "Zeige 0 to 0 of 0 Einträge(n)",
					"sSearch": "Volltextsuche:",
					"sInfoFiltered": "(gefiltert von _MAX_ Gesamteinträgen)",
					"oPaginate": {
						"sFirst": "Erster",
						"sPrevious": "Zurück",
						"sNext": "Nächster",
						"sLast": "Letzter"
					}
				},
				"sPaginationType": "full_numbers",
				"iDisplayLength": 10,
				"aLengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				"bJQueryUI": true
			});
		});
	</script>
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
	<script type="text/javascript">
		function FensterOeffnen(Adresse) {
			MeinFenster = window.open(Adresse, "Zweitfenster", "resizable=1,location=0,directories=0,status=0,menubar=1,scrollbars=1,toolbar=0,width=1024,height=768,left=0,top=0");
			MeinFenster.focus();
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
		<header>
			<img src="./bilder/headerLogo.png" alt="Firmenlogo">
			<h1>Übersicht & Suche in den Stücklistenänderungen</h1>
			<nav>

				<a href="start.php" aria-label="Startseite">
					<div><i class="fas fa-home"></i></div>
					Startseite
				</a>
				<a href="erzeugen.php" aria-label="Erzeugen">
					<div><i class="fas fa-plus-square"></i></div>
					Erzeugen
				</a>
				<a href="viewlist.php" aria-label="Übersicht">
					<div><i class="fas fa-list-alt"></i></div>
					Übersicht
				</a>

				<a href="usercreate.php" aria-label="Benutzer anlegen">
					<div><i class="fas fa-user-plus"></i></div>
					Benutzer anlegen
				</a>

				<a href="logout.php" aria-label="Logout   ">
					<div><i class="fas fa-sign-out-alt"></i></div>
					Logout
				</a>
				<a href="login-eingabe.php" aria-label="Login">
					<div><i class="fas fa-sign-in-alt"></i></div> Login
				</a>
				<div id="user-img">
					<?php echo isset($stat_bild) ? $stat_bild : ''; // Gibt $stat_bild aus, wenn es existiert 
					?>
				</div>
				<button id="themeToggleBtn" title="Helles Design aktivieren" style="background:none; border:none; color: white; cursor:pointer; font-size: 1.5em; margin-left:15px;">
					<i class="fas fa-sun"></i> </button>
			</nav>
		</header>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const themeToggleBtn = document.getElementById('themeToggleBtn');
			const body = document.body; // oder document.documentElement für das <html> Tag

			// Icons für den Button
			const sunIconClass = 'fa-sun'; // Light Mode aktiv (zeigt Sonne zum Wechsel auf Dark) - eigentlich andersrum
			const moonIconClass = 'fa-moon'; // Dark Mode aktiv (zeigt Mond zum Wechsel auf Light) - eigentlich andersrum

			// Korrigierte Logik für Icon-Anzeige:
			// Wenn aktuelles Theme Dark ist (Standard oder gewählt), zeige "Sonne"-Icon (um zu Light zu wechseln)
			// Wenn aktuelles Theme Light ist, zeige "Mond"-Icon (um zu Dark zu wechseln)

			const applyTheme = (theme) => {
				const iconElement = themeToggleBtn ? themeToggleBtn.querySelector('i') : null;
				if (theme === 'light') {
					body.classList.add('light-mode');
					if (iconElement) {
						iconElement.classList.remove(sunIconClass);
						iconElement.classList.add(moonIconClass);
						themeToggleBtn.title = 'Dunkles Design aktivieren';
					}
				} else { // 'dark' or default
					body.classList.remove('light-mode');
					if (iconElement) {
						iconElement.classList.remove(moonIconClass);
						iconElement.classList.add(sunIconClass);
						themeToggleBtn.title = 'Helles Design aktivieren';
					}
				}
			};

			// Beim Laden der Seite prüfen, ob ein Theme im localStorage gespeichert ist
			let currentTheme = localStorage.getItem('theme');

			if (!currentTheme) { // Wenn nichts gespeichert ist, Standard auf Dark
				currentTheme = 'dark'; // Dark Mode ist der Standard
				localStorage.setItem('theme', currentTheme); // Speichere Standard, falls nicht vorhanden
			}
			applyTheme(currentTheme);


			// Event Listener für den Button
			if (themeToggleBtn) {
				themeToggleBtn.addEventListener('click', function() {
					let newTheme;
					if (body.classList.contains('light-mode')) {
						newTheme = 'dark';
					} else {
						newTheme = 'light';
					}
					applyTheme(newTheme);
					localStorage.setItem('theme', newTheme);

					// Hier ggf. die Funktion für dynamische Tabellen-Infos aufrufen
					if (typeof updateDynamicTableStyles === 'function') {
						updateDynamicTableStyles(newTheme);
					}
				});
			}
		});
	</script>




	<?php
	// Prüfen ob der angemeldete USER offene Änderungen hat -> anzeigen
	// Weiterhin unterscheiden zu welcher Abteilung der User gehört!!
	// PF/PP darf freigeben, aber nicht erzeugen!!



	echo "<div class='tabellen-container'>";
	if (isset($_SESSION["username"])) {
		$user = $_SESSION["username"];
		$sql_user = mysqli_query($conn, "Select id, abt, nachname, vorname from adlogin where username = '$user'");
		if (mysqli_num_rows($sql_user)) # falls der Login von der Änderungsverwaltung kommt
		{
			$row = mysqli_fetch_assoc($sql_user);
			$user_id = $row['id'];
			$abt = $row['abt'];
			$nachname = $row['nachname'];
			$vorname = $row['vorname'];
		}
		// **********************************************************************************************************************************************************************************************************
		// jetzt abteilungsspezifisch entsprechende Listen anzeigen
		if ($abt == "PV") {
			// In data alle Änderungen holen die keine PV - Freigabe besitzen
			$offen_daten = mysqli_query($conn, "select
                                    z.user_id,
                                    z.dat_ez,
                                    d.tbsachnr,
                                    d.tbepsanr,
                                    d.tbbez,
                                    d.id
                                from zustand as z
                                INNER JOIN data AS d ON d.id = z.aend_id  -- KORREKTUR: INNER JOIN verwenden
                                where z.user_id = '$user_id' AND z.stat_pv ='0' ORDER BY d.tbsachnr ASC");  // KORREKTUR: Anführungszeichen korrigiert

			// VERBESSERUNG: Fehlerbehandlung
			if (!$offen_daten) {
				echo "<tr><td colspan='5'>Fehler in der Abfrage: " . mysqli_error($conn) . "</td></tr>"; // KORREKTUR: colspan angepasst
			} else {
				$num = mysqli_num_rows($offen_daten);
				if ($num == 0) {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen von $vorname $nachname ($abt) vorhanden!</big></div></label><br>";
				} else {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen von " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($nachname, ENT_QUOTES, 'UTF-8') . " (" . htmlspecialchars($abt, ENT_QUOTES, 'UTF-8') . ") vorhanden!</big></div></label><br>";
					echo "<table align='center' border='1'<tr class='tab_rot'><td align='center'>Auswahl</td><td align='center'>EPSa-Nr.</td><td align='center'>Sach-Nr.</td><td align='center'>Bezeichnung</td><td align='center'>Datum</td></tr>";
					// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
					for ($i = 0; $i < $num; $i++) {
						$row = mysqli_fetch_assoc($offen_daten);
						$userid = $row["user_id"]; // KORREKTUR: korrekte Spaltennamen verwenden
						$datum = $row["dat_ez"];
						$sachnr = $row["tbsachnr"];
						$epsanr = $row["tbepsanr"];
						$bez = $row["tbbez"];
						$id = $row["id"];
						$datum = date("d.m.Y H:i:s", strtotime($datum));
						// NEU (mit Font Awesome Icons):
						echo "<tr align='center'>
    <td style='white-space: nowrap;'>
        <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false' title='Drucken / Ansicht'><i class='fas fa-print action-icon'></i></a>
        <a href='edit.php?id=$id' onClick='FensterOeffnen(this.href); return false' title='Bearbeiten'><i class='fas fa-edit action-icon'></i></a>
        <a href='freigabe_all.php?id=$id' title='Freigabe PV'><i class='fas fa-check-circle action-icon icon-green'></i></a>
        <a href='kill.php?id=$id' title='Entfernen'><i class='fas fa-trash-alt action-icon icon-red'></i></a>
    </td>
    <td>" . htmlspecialchars($epsanr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($sachnr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($bez, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($datum, ENT_QUOTES, 'UTF-8') . "</td>
</tr>";
					}
					echo "</table>";
				}
			}
		}  // Ende if abt = PV!!!
		// **********************************************************************************************************************************************************************************************************       
		if ($abt == "PF") {
			// PF bekommt nur freigegebene PV und PF betreffende Änderungen zu Gesicht
			// 1. User ist PF
			$offen_daten = mysqli_query($conn, "select
                                    z.user_id,
                                    z.dat_ez,
                                    d.tbsachnr,
                                    d.tbepsanr,
                                    d.tbbez,
                                    d.id
                                from zustand as z
                                INNER JOIN data AS d ON d.id = z.aend_id  -- KORREKTUR: INNER JOIN verwenden
                                where z.stat_pv = '1' AND z.stat_pf ='0' and d.chpf = '1' ORDER BY d.tbsachnr ASC"); // KORREKTUR: Anführungszeichen korrigiert

			// VERBESSERUNG: Fehlerbehandlung
			if (!$offen_daten) {
				echo "<tr><td colspan='5'>Fehler in der Abfrage: " . mysqli_error($conn) . "</td></tr>"; // KORREKTUR: colspan angepasst
			} else {
				$num = mysqli_num_rows($offen_daten);
				if ($num == 0) {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PF vorhanden!</big></div></label><br>";
				} else {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PF</big></div></label><br>";
					echo "<table align='center' border='1' class='display' id='example'>
                        <thead><tr>
                            <th align='center'>Auswahl</th>
                            <th align='center'>EPSa-Nr.</th>
                            <th align='center'>Sach-Nr.</th>
                            <th align='center'>Bezeichnung</th>
                            <th align='center'>Datum</th>
                        </tr></thead><tbody>";
					// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
					for ($i = 0; $i < $num; $i++) {
						$row = mysqli_fetch_assoc($offen_daten);
						$userid = $row["user_id"]; // KORREKTUR: korrekte Spaltennamen verwenden
						$datum = $row["dat_ez"];
						$sachnr = $row["tbsachnr"];
						$epsanr = $row["tbepsanr"];
						$bez = $row["tbbez"];
						$id = $row["id"];
						$datum = date("d.m.Y H:i:s", strtotime($datum));
						// NEU (mit Font Awesome Icons):
						echo "<tr align='center'>
    <td style='white-space: nowrap;'>
        <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false' title='Drucken / Ansicht'><i class='fas fa-print action-icon'></i></a>
        <a href='freigabepf.php?id=$id' title='Freigabe PF'><i class='fas fa-check-circle action-icon icon-green'></i></a>
    </td>
    <td>" . htmlspecialchars($epsanr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($sachnr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($bez, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($datum, ENT_QUOTES, 'UTF-8') . "</td>
</tr>";
					}
					echo "</tbody></table>";
				}
			}
		}  // Ende if abt = PF!!!
		// **********************************************************************************************************************************************************************************************************       
		if ($abt == "PP") {
			// PP bekommt nur freigegebene PV,PF Änderungen und PP betreffende Änderungen zu Gesicht
			// 1. User ist PP
			$offen_daten = mysqli_query($conn, "select
                                    z.user_id,
                                    z.dat_ez,
                                    d.tbsachnr,
                                    d.tbepsanr,
                                    d.tbbez,
                                    d.id
                                from zustand as z
                                INNER JOIN data AS d ON d.id = z.aend_id -- KORREKTUR: INNER JOIN verwenden
                                where z.stat_pv = '1' AND z.stat_pf = '1' AND z.stat_pp ='0' and d.chpp = '1' ORDER BY d.tbsachnr ASC"); // KORREKTUR: Anführungszeichen korrigiert

			// VERBESSERUNG: Fehlerbehandlung
			if (!$offen_daten) {
				echo "<tr><td colspan='5'>Fehler in der Abfrage: " . mysqli_error($conn) . "</td></tr>"; // KORREKTUR: colspan angepasst
			} else {
				$num = mysqli_num_rows($offen_daten);
				if ($num == 0) {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PP vorhanden!</big></div></label><br>";
				} else {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PP</big></div></label><br>";
					echo "<table align='center' border='1' class='display' id='example'>
                        <thead><tr>
                            <th align='center'>Auswahl</th>
                            <th align='center'>EPSa-Nr.</th>
                            <th align='center'>Sach-Nr.</th>
                            <th align='center'>Bezeichnung</th>
                            <th align='center'>Datum</th>
                        </tr></thead><tbody>";
					// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
					for ($i = 0; $i < $num; $i++) {
						$row = mysqli_fetch_assoc($offen_daten);
						$userid = $row["user_id"]; // KORREKTUR: korrekte Spaltennamen verwenden
						$datum = $row["dat_ez"];
						$sachnr = $row["tbsachnr"];
						$epsanr = $row["tbepsanr"];
						$bez = $row["tbbez"];
						$id = $row["id"];
						$datum = date("d.m.Y H:i:s", strtotime($datum));
						// NEU (mit Font Awesome Icons):
						echo "<tr align='center'>
    <td style='white-space: nowrap;'>
        <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false' title='Drucken / Ansicht'><i class='fas fa-print action-icon'></i></a>
        <a href='freigabe_all.php?id=$id' title='Freigabe PP'><i class='fas fa-check-circle action-icon icon-green'></i></a>
    </td>
    <td>" . htmlspecialchars($epsanr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($sachnr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($bez, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($datum, ENT_QUOTES, 'UTF-8') . "</td>
</tr>";
					}
					echo "</tbody></table>";
				}
			}
		}  // Ende if abt = PP!!!
		// **********************************************************************************************************************************************************************************************************       
		if ($abt == "PG") {
			// PG bekommt nur freigegebene PV,PF Änderungen und PG betreffende Änderungen zu Gesicht
			// 1. User ist PG
			$offen_daten = mysqli_query($conn, "select
									z.user_id,
									z.dat_ez,
									d.tbsachnr,
									d.tbepsanr,
									d.tbbez,
									d.id
								from zustand as z
								INNER JOIN data AS d ON d.id = z.aend_id -- KORREKTUR: INNER JOIN verwenden
								where z.stat_pv = '1' AND z.stat_pf = '1' AND z.stat_pg ='0' and d.chpg = '1' ORDER BY d.tbsachnr ASC"); // KORREKTUR: Anführungszeichen korrigiert

			// VERBESSERUNG: Fehlerbehandlung
			if (!$offen_daten) {
				echo "<tr><td colspan='5'>Fehler in der Abfrage: " . mysqli_error($conn) . "</td></tr>"; // KORREKTUR: colspan angepasst
			} else {
				$num = mysqli_num_rows($offen_daten);
				if ($num == 0) {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PG vorhanden!</big></div></label><br>";
				} else {
					echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PG</big></div></label><br>";
					echo "<table align='center' border='1'<tr class='tab_rot'><td align='center'>Auswahl</td><td align='center'>EPSa-Nr.</td><td align='center'>Sach-Nr.</td><td align='center'>Bezeichnung</td><td align='center'>Datum</td></tr>";
					// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
					for ($i = 0; $i < $num; $i++) {
						$row = mysqli_fetch_assoc($offen_daten);
						$userid = $row["user_id"]; // KORREKTUR: korrekte Spaltennamen verwenden
						$datum = $row["dat_ez"];
						$sachnr = $row["tbsachnr"];
						$epsanr = $row["tbepsanr"];
						$bez = $row["tbbez"];
						$id = $row["id"];
						$datum = date("d.m.Y H:i:s", strtotime($datum));
						echo "<tr align='center'>
    <td style='white-space: nowrap;'>
        <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false' title='Drucken / Ansicht'><i class='fas fa-print action-icon'></i></a>
        <a href='freigabe_all.php?id=$id' title='Freigabe PG'><i class='fas fa-check-circle action-icon icon-green'></i></a>
    </td>
    <td>" . htmlspecialchars($epsanr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($sachnr, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($bez, ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($datum, ENT_QUOTES, 'UTF-8') . "</td>
</tr>";
					}
					echo "</table>";
				}
			}
		}  // Ende if abt = PG!!!      

	} // Ende isset(username)
	echo "</div>";
	?>

	<footer>
		<div class="user-info-content">
			<div class="info-item">
				<strong>Angemeldet:</strong> <?php echo isset($host) ? htmlspecialchars($host) : 'N/A'; ?>-<?php echo isset($ip) ? htmlspecialchars($ip) : 'N/A'; ?>
			</div>
			<div class="info-item">
				<strong>Benutzer:</strong> <?php echo isset($ma_aktiv) ? htmlspecialchars($ma_aktiv) : 'N/A'; ?>
			</div>
			<div class="info-item" id="Uhr">
				<?php echo isset($uhrzeit) ? $uhrzeit : '&nbsp;'; ?>
			</div>


			<p class="copyright"> &copy; <?php echo date('Y'); ?> EPSa
			</p>
		</div>
	</footer>

</body>

</html>