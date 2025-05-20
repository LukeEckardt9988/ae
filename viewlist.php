<?php
session_start();
session_status();
$session = session_id();

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Epsa Änderungsübersicht</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link href="media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="media/js/jquery.js"></script>
    <script type="text/javascript" src="media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="scripts/dhtml.js"></script>
    <script type="text/javascript" src="scripts/lightbox.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


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
    <script>
        $(document).ready(function() {
            $("#ladebalken").show(); // Zeige den Ladebalken an
            $("#tabellen-container").hide(); // Verstecke den Tabellen-Container

            // Initialisiere die DataTables
            $('#example').dataTable({
                bDestroy: true,
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
                "iDisplayLength": 12,
                "aLengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "bJQueryUI": true
            });

            // Zeige die Tabelle und verstecke den Ladebalken nach 3 Sekunden
            setTimeout(function() {
                $("#ladebalken").hide();
                $("#tabellen-container").fadeIn(500);
            }, 3000);
        });
    </script>
    <script type="text/javascript">
        function FensterOeffnen(Adresse) {
            MeinFenster = window.open(Adresse, "Zweitfenster",
                "resizable=1,location=0,directories=0,status=0,menubar=1,scrollbars=1,toolbar=0,width=1024,height=768,left=0,top=0"
            );
            MeinFenster.focus();
        }
    </script>

</head>

<html onload="window.setTimeout('ZeitAnzeigen()', 1000);">



<?php
// Bei der Suche nach Änderungen ist Gast erlaubt -> keine Sessionabfrage notwendig
// Nach Eingabe einer EPSa Nummer schicken wir Ihn zu Viewlist mit Referenz
include "connect.php";
header('Content-Type: text/html; charset=UTF-8');
$ma_aktiv = "Gast";
if (isset($_SESSION["username"])) {
    $user = $_SESSION["username"];
    $sql_user = mysqli_query($conn, "Select abt, nachname, vorname from adlogin where username = '$user'");
    if (mysqli_num_rows($sql_user)) {
        $row = mysqli_fetch_assoc($sql_user);
        $abt = $row["abt"];
        $nachname = $row["nachname"];
        $vorname = $row["vorname"];
        $ma_aktiv = $abt . " / " . $vorname . " " . $nachname;
    }
}
?>
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

<div class="loader"></div>

<script>
    const loader = document.querySelector('.loader');

    setTimeout(() => {
        loader.style.display = 'none'; // Ladebalken ausblenden
    }, 3500); // 3000 Millisekunden = 3 Sekunden
</script>


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

<div class="tabellen-container" id="tabellen-container">
    <table cellpadding="0" cellspacing="0" border="1" class="display" id="example">
        <thead>
            <tr align=center>
                <th class="status-col">Status</th>
                <th class="view-log-col">View / Log</th>
                <th class="bearbeiter-col">Bearbeiter</th>
                <th class="epsanr-col">EPSa-Nr.</th>
                <th class="sachnr-col">Sachnummer</th>
                <th class="bezeichnung-col">Bezeichnung</th>
                <th class="datum-col">Datum</th>
                <th class="pv-col">PV</th>
                <th class="pf-col">PF</th>
                <th class="pp-col">PP</th>
                <th class="pg-col">PG</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = "";
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $search = mysqli_real_escape_string($conn, $search);
            }

            $sql = "SELECT 
                            data.tbepsanr as epsanr,
                            data.tbsachnr as sachnr,
                            data.chpf as betpf,
                            data.id as id,
                            data.chpp as betpp,
                            data.chpg as betpg,
                            data.tbbez as bezeichnung,
                            data.tbtechno as technologe,
                            zustand.user_id as user_id,
                            zustand.stat_pv as stpv,
                            zustand.stat_pf as stpf,
                            zustand.stat_pp as stpp,
                            zustand.stat_pg as stpg,
                            zustand.dat_ez as dez,
                            zustand.dat_pv as dpv,
                            zustand.dat_pf as dpf,
                            zustand.dat_pp as dpp,
                            zustand.dat_pg as dpg,
                            adlogin.nachname as name,
                            adlogin.vorname as vorname
                        FROM data, zustand, adlogin
                        WHERE data.id = zustand.aend_id 
                        AND zustand.user_id = adlogin.id";

            if (!empty($search)) {
                $sql .= " AND (
                                data.tbepsanr LIKE '%$search%' OR
                                data.tbsachnr LIKE '%$search%' OR
                                data.tbbez LIKE '%$search%' OR
                                data.tbtechno LIKE '%$search%' OR
                                data.tbbslchange LIKE '%$search%' OR
                                adlogin.nachname LIKE '%$search%' OR
                                adlogin.vorname LIKE '%$search%'
                                )";
            }

            $sql .= " ORDER BY zustand.dat_ez DESC";
            $aend_daten = mysqli_query($conn, $sql);

            $num = mysqli_num_rows($aend_daten);
            if (!$aend_daten || $num === 0) {
                echo "<tr><td colspan='11'>Keine Daten vorhanden oder keine Ergebnisse für Ihre Suchanfrage gefunden.</td></tr>";
            } else {
                while ($row = mysqli_fetch_assoc($aend_daten)) {
                    $epsanr = $row["epsanr"];
                    $sachnr = $row["sachnr"];
                    $betpf = $row["betpf"];
                    $betpp = $row["betpp"];
                    $betpg = $row["betpg"];
                    $bezeichnung = $row["bezeichnung"];
                    $technologe = $row["technologe"];
                    $user_id = $row["user_id"];
                    $stpv = $row["stpv"];
                    $stpf = $row["stpf"];
                    $stpp = $row["stpp"];
                    $stpg = $row["stpg"];
                    $dez = $row["dez"];
                    $dpv = $stpv == 1 ?
                        date("d.m.Y", strtotime($row["dpv"])) : "Bedingung";
                    $dpf = $stpf == 1 ? date("d.m.Y", strtotime($row["dpf"])) : "Bedingung";
                    $dpp = $stpp == 1 ?
                        date("d.m.Y", strtotime($row["dpp"])) : "Bedingung";
                    $dpg = $stpg == 1 ? date("d.m.Y", strtotime($row["dpg"])) : "Bedingung";
                    $name = $row["name"];
                    $vorname = $row["vorname"];
                    $id = $row["id"];

                    if (!$stpv) {
                        // Rotes Icon, z.B. ein rotes Schild oder X
                        $status = "<i class='fas fa-times-circle' style='color: red;'></i>";
                    } elseif ($betpf == $stpf && $betpp == $stpp && $betpg == $stpg) {
                        // Grünes Icon, z.B. ein grüner Haken
                        $status = "<i class='fas fa-check-circle' style='color: green;'></i>";
                    } else {
                        // Gelbes Icon, z.B. ein gelbes Ausrufezeichen
                        $status = "<i class='fas fa-exclamation-triangle' style='color: orange;'></i>";
                    }

                    if ($stpv < $stpf || $stpv < $stpp || $stpv < $stpg || $betpf < $stpf || $betpp < $stpp || $betpg < $stpg) {
                        // Fehler-Icon
                        $status = "<i class='fas fa-exclamation-circle' style='color: darkred;'></i>";
                    }

                    $pvfarbe = $stpv == 1 ?
                        'black' : 'red';
                    $pffarbe = ($betpf == 1 && !$stpf) ? 'red' : 'black';
                    $ppfarbe = ($betpp == 1 && !$stpp) ? 'red' : 'black';
                    $pgfarbe = ($betpg == 1 && !$stpg) ?
                        'red' : 'black';

                    echo "<tr>
                            <td nowrap align='center'>" . $status . "</td>
                            <td nowrap align='center'>
                                <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false;' title='Ansicht / Drucken'><i class='fas fa-eye'></i></a>
                                <a href='log.php?id=$id' onClick='FensterOeffnen(this.href); return false;' title='Logfile'><i class='fas fa-history'></i></a>
                            </td>
                            <td nowrap align='center'>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ", " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . "</td>
                            <td nowrap align='center'>" . htmlspecialchars($epsanr, ENT_QUOTES, 'UTF-8') . "</td>
                            <td nowrap align='center'>" . htmlspecialchars($sachnr, ENT_QUOTES, 'UTF-8') . "</td>
                            <td nowrap align='center'>" . htmlspecialchars($bezeichnung, ENT_QUOTES, 'UTF-8') . "</td>
                            <td nowrap align='center'>" . htmlspecialchars($dez, ENT_QUOTES, 'UTF-8') . "</td>
                            <td nowrap align='center'><font color='$pvfarbe'>" . htmlspecialchars($dpv, ENT_QUOTES, 'UTF-8') . "</font></td>
                            <td nowrap align='center'><font color='$pffarbe'>" . htmlspecialchars($dpf, ENT_QUOTES, 'UTF-8') . "</font></td>
                            <td nowrap align='center'><font color='$ppfarbe'>" . htmlspecialchars($dpp, ENT_QUOTES, 'UTF-8') . "</font></td>
                            <td nowrap align='center'><font color='$pgfarbe'>" . htmlspecialchars($dpg, ENT_QUOTES, 'UTF-8') . "</font></td>
                            </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>




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

</html>

</body>