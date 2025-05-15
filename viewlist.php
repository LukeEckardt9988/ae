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
            "iDisplayLength": 10,
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

<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">



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
        <div class="header">
            <h1 class="Ueberschrift">Übersicht & Suche in den Stücklistenänderungen</h1>
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
                            $status = "<img src='bilder/flag_red.png' alt='Nicht freigegeben'>";
                        } elseif ($betpf == $stpf && $betpp == $stpp && $betpg == $stpg) {
                            $status = "<img src='bilder/flag_green.png' alt='Freigegeben'>";
                        } else {
                            $status = "<img src='bilder/flag_yellow.png' alt='In Bearbeitung'>";
                        }

                        if ($stpv < $stpf || $stpv < $stpp || $stpv < $stpg || $betpf < $stpf || $betpp < $stpp || $betpg < $stpg) {

                            $status = "<img src='bilder/warning.png' height='32' alt='Fehler'>";
                        }

                        $pvfarbe = $stpv == 1 ?
                            'white' : 'red';
                        $pffarbe = ($betpf == 1 && !$stpf) ? 'red' : 'white';
                        $ppfarbe = ($betpp == 1 && !$stpp) ? 'red' : 'white';
                        $pgfarbe = ($betpg == 1 && !$stpg) ?
                            'red' : 'white';

                        echo "<tr>
                            <td nowrap align='center'>" . $status . "</td>
                            <td nowrap align='center'>
                             <a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false;'><img border='0' src='bilder/preview.png' alt='Ansicht / Drucken' title='Ansicht / Drucken'></a>
                            <a href='log.php?id=$id' onClick='FensterOeffnen(this.href); return false;'><img border='0' src='bilder/log.png' height='32' alt='Logfile' title='Logfile'></a>
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





    <div class="user-info">
        <div class="info-item">
            <strong>Angemeldet:</strong> <?php echo $host; ?>-<?php echo $ip; ?>
        </div>
        <div class="info-item">
            <strong>Benutzer:</strong> <?php echo $ma_aktiv; ?>
        </div>
        <div class="info-item" id="Uhr">
            &nbsp;
        </div>
        <div id="user-img">
            <?php echo $stat_bild; ?>
        </div>

    </div>