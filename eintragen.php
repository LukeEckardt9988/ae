<?php
include "connect.php";

$username = $_POST["username"];
$passwort = $_POST["passwort"];
$passwort2 = $_POST["passwort2"];
$vorname = $_POST["vorname"];
$nachname = $_POST["nachname"];
$abt = $_POST["abt"];


if($passwort != $passwort2 OR $username == "" OR $passwort == "" OR $vorname == "" OR $nachname == "" OR $abt == "")
    {
    echo "Eingabefehler. Bitte alle Felder korekt ausfüllen. <a href=\"usercreate.php\">Benutzer anlegen</a>";
    exit;
    }
$passmd5 = md5($passwort);

$result = mysqli_query($conn, "SELECT id FROM adlogin WHERE username LIKE '$username'");
$menge = mysqli_num_rows($result);

if($menge == 0)
    {
    $eintrag = "INSERT INTO adlogin (username, passwort, abt, vorname, nachname) VALUES ('$username', '$passmd5', '$abt', '$vorname', '$nachname')";
    $eintragen = mysqli_query($conn, $eintrag);

    if($eintragen == true)
        {
		// echo $eintrag."<br>";
		// echo "PASSI: ".$passwort. " - > ".md5($passwort);
        echo "<label><div align='center'><big>Benutzer <b>$username</b> wurde erstellt...<a href=\"start.php\">Startseite</a></big></div></label><br>";
        }
    else
        {
        echo "Fehler beim Speichern des Benutzernames. <a href=\"usercreate.php\">Benutzer anlegen</a>";
        }


    }

else
    {
    echo "Benutzername schon vorhanden. <a href=\"usercreate.php\">Benutzer anlegen</a>";
    }
?> 