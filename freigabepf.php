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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Aenderung erzeugen</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <style>
    a {
      color: white;
    }
  </style>
  <script type="text/javascript" src="scripts/dhtml.js"></script>
  <script language="JavaScript">
    var laenge = 4680; //Hier die gewünschte Zeichenlänge angeben
    function countLetters(l) {
      document.form.zahl.value = laenge - l.value.length;
      if (l.value.length > laenge) {
        l = l.value.substring(0, parseInt(laenge));
        document.form.tbbslchange.value = l;
        document.form.zahl.value = 0;
      }
    }
  </script>
  <script type="text/javascript">
    function ZeitAnzeigen() {
      var Wochentagname = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch",
        "Donnerstag", "Freitag", "Samstag");
      var Jetzt = new Date();
      var Tag = Jetzt.getDate();
      var Monat = Jetzt.getMonth() + 1;
      var Jahr = Jetzt.getYear();
      if (Jahr < 999)
        Jahr += 1900;
      var Stunden = Jetzt.getHours();
      var Minuten = Jetzt.getMinutes();
      var Sekunden = Jetzt.getSeconds();
      var WoTag = Jetzt.getDay();
      var Vortag = (Tag < 10) ? "0" : "";
      var Vormon = (Monat < 10) ? ".0" : ".";
      var Vorstd = (Stunden < 10) ? "0" : "";
      var Vormin = (Minuten < 10) ? ":0" : ":";
      var Vorsek = (Sekunden < 10) ? ":0" : ":";
      var Datum = Vortag + Tag + Vormon + Monat + "." + Jahr;
      var Uhrzeit = Vorstd + Stunden + Vormin + Minuten + Vorsek + Sekunden;
      var Gesamt = Wochentagname[WoTag] + ", " + Datum + " - " + Uhrzeit;

      if (DHTML) {
        if (NS4) {
          setContent("id", "Uhr", null, '<span class="Uhr">' + Gesamt + "<\/span>");
        } else {
          setContent("id", "Uhr", null, Gesamt);
        }
        window.setTimeout("ZeitAnzeigen()", 1000);
      }
    }
  </script>
</head>

<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">
  <?php
  if (!isset($_SESSION["username"])) {
    echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
  }
  ?>
  <form action="freigabepf_speich.php" method="post" name="form">
    <?php
    include "connect.php";
    // *****************************************************************************************************************************
    // Freigabe durch PF!!
    // $id kommt aus dem Link
    // prüfen ob es eine gültige ID gibt (das das Formular nicht direkt gestartet wurde!!)
    // *****************************************************************************************************************************
    $ds_id = $id;
    $ma_aktiv = "Gast";
    if (isset($_SESSION["username"])) {
      $user = $_SESSION["username"];
      $sql_user = mysqli_query($conn, "Select abt, nachname, vorname from adlogin where username = '$user'");
      if (mysqli_num_rows($sql_user))  # falls der Login von der Änderungsverwaltung kommt
      {
        $row = mysqli_fetch_assoc($sql_user);
        $abt = $row["abt"];
        $nachname = $row["nachname"];
        $vorname = $row["vorname"];
        $ma_aktiv = $abt . " / " . $vorname . " " . $nachname;
      }
    }
    $log_user = $_SESSION["username"];
    // Username, Zustand und Abteilung zur übermittelten ID holen und prüfen 
    $sql_user = mysqli_query($conn, "Select  
								adlogin.username,
								adlogin.abt,
								adlogin.vorname,
								adlogin.nachname,
								zustand.stat_pv,
								zustand.stat_pf,
								zustand.dat_pv,
								zustand.dat_pf,
								data.tbsachnr,
								data.tbbez,
								data.tbbslchange
						from
								adlogin, data, zustand
						where
							   data.id = '$ds_id' AND zustand.aend_id = data.id AND adlogin.username = '$log_user' AND adlogin.abt = 'PF'
							   AND zustand.stat_pv = '1' AND zustand.stat_pf = '0'");

    if (mysqli_num_rows($sql_user) <> '1') {
      echo "<label><div align='center'><big>Sie haben keine Berechtigung diese Aenderung für PF auszuführen...<a href=\"start.php\">Startseite</a></big></div></label><br>";
      exit;
    }


    $row = mysqli_fetch_assoc($sql_user);
    $user_name = $row["username"];
    $vorname = $row["vorname"];
    $nachname = $row["nachname"];
    $user_abt = $row["abt"];
    $stat_pv = $row["stat_pv"];
    $stat_pf = $row["stat_pf"];
    $dat_pv = $row["dat_pv"];
    $dat_pf = $row["dat_pf"];
    $sachnr = $row["tbsachnr"];
    $bez = $row["tbbez"];
    $slchange = $row["tbbslchange"];
    $slchange = $row["tbbslchange"];

    $datzeit = date("d.m.Y H:i:s");


    $kommentar = "*************************************************************\r\nKommentar von: " . $abt . " / " . $vorname . " " . $nachname . " / " . $datzeit . "\r\n*************************************************************\r\n\r\n";
    ?>

    <table width="1000" border="0" align="center" class="tab_grau">
      <tr align="center" valign="middle">
        <td colspan="4" valign="top"><b class="Ueberschrift">
            <p id="whiteschattig">Freigabe: <?php echo $sachnr . " / " . $bez; ?> </p>
          </b></td>
      </tr>
      <tr>
        <td width="20%" align="left"><a href="start.php?session=$session">zur Startseite</a></td>
        <td width="60%" align="center" valign="top"><b>Noch <input type="Text" name="zahl" value="" size="6" maxlength="4" style="color:#060; text-align:center;"> Zeichen erlaubt!</b></td>
        <td width="20%" colspan="2" align="right"><input name="submit" type="submit" value="Speichern" /></td>
      </tr>
      <tr>
        <td align="center" colspan="3"><textarea onClick="countLetters(this)" onKeyUp="countLetters(this)" name="tbbslchange" id="tbbslchange" cols="78" rows="60"><?php echo $slchange . "\r\n\r\n" . $kommentar; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="3" align="center">&nbsp;</td>
      </tr>
    </table>

    <table width="1000" border="0" align="center">
      <tr>
        <td width="400" align="left"><strong>angemeldet:</strong> <?php echo $host; ?>-<?php echo $ip; ?></td>
        <td width="300" align="center"><strong>Benutzer:</strong> <?php echo $ma_aktiv; ?></td>
        <td width="300" align="right" id="Uhr" div>&nbsp;</div>
        </td>
      </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
  </form>
</Body>

</html>