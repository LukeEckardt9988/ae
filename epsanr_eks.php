<?php
session_start();
session_status();
$session = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPSaNr</title>
</head>
<body>
<?php


if (!isset($_SESSION["username"])) {
    echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
}

//Platzhalter für erlaubt abteilungen
$erl_abt = "(abt = 'PV')";

include "connect.php";

// Prüfen, ob der User Mitarbeiter von PV ist
$user = $_SESSION["username"];
$sql_user = mysqli_query($conn, "SELECT id 
                                FROM adlogin 
                                WHERE username = '$user'
                                    AND $erl_abt");
if (!$sql_user) {
    //Datenbankfehler
    die("Datenbankfehler: " . mysqli_errno($conn));
} elseif (mysqli_num_rows($sql_user) == 0) {
    echo "<label><div align='center'><big>Dazu fehlt ihnen die Berechtigung... <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
} else {
    $user_id = mysqli_fetch_assoc($sql_user)['id'];

    // hier alle Daten zur EPSa Nr. auslesen
    if (isset($_POST["tbepsanr"])) {
        $tbepsanr = $_POST["tbepsanr"]; // Zuweisung der EPSa-Nr aus dem Formular
        $z = "http://gast:gast@192.168.11.9/cgi-bin/EKS/eks-web/prog=HTML.BSTL&p1=$tbepsanr&p3=0&p4=";
        $text = file_get_contents($z);
        $startpos = strpos($text, '<th>F-', 1);
        if (!$startpos === false) // sollte keine gültige EPSaNr. eingegeben wurden sein.
        {
            $stoppos = strpos($text, '</th>', $startpos);
            $tbsachnr = substr($text, $startpos + 6, $stoppos - $startpos - 6);  // Sachnummer
            $startpos = strpos($text, '<th>', $stoppos + 2);
            $stoppos = strpos($text, '</th>', $stoppos + 2);
            $tbbez = substr($text, $startpos + 4, $stoppos - $startpos - 4);  // Bezeichnung
            $startpos = strpos($text, '<th>', $stoppos + 2);
            $stoppos = strpos($text, '</th>', $stoppos + 2);
            $tbprog = substr($text, $startpos + 21, $stoppos - $startpos - 21);  // Programmnummer

			$_SESSION['tbbez'] = $tbbez; 
		
            include('aend-formular.php'); // Formular nur anzeigen, wenn EPSa-Nr gültig und User berechtigt
        } else {
            // keine gültige EPSaNr.::
            echo "<label><div align='center'><big>EPSaNr.: $tbepsanr wurde nicht im System gefunden...<br>
                Bitte bitte geben sie eine gültige EPSa-Nr. ein!! <a href=\"erzeugen.php\">Änderung erzeugen</a></big></div></label><br>";
        }
    } else {
        echo "<label><div align='center'><big>Sie haben keine EPSa-Nr. eingegeben!<br>
            Bitte bitte geben sie zuerst eine EPSa-Nr. an!! <a href=\"erzeugen.php\">Änderung erzeugen</a></big></div></label><br>";
    }
} // Ende des else-Blocks der Berechtigungsprüfung


?>
</body>
</html>

