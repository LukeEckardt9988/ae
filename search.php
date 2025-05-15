<?php
session_start();
$session = session_id();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Aenderung - Volltextsuche</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="scripts/dhtml.js"></script>
  <script type="text/javascript" src="scripts/lightbox.js"></script>
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
  // echo "USER: ".$_POST["username"];;
	if (!isset($_SESSION["username"])) {
		$stat_bild = "<img src=bilder/user-offline.png>";
		$stat_schrift = "<font color='red'>Offline</font>";
	} else {
		$stat_bild = "<img src=bilder/user-online.png>";
		$stat_schrift = "<font color='green'>Online</font>";
	}
	// echo $stat_bild;	
  
  // Bei der Suche nach Änderungen ist Gast erlaubt -> keine Sessionabfrage notwendig
  // Nach Eingabe einer EPSa Nummer schicken wir Ihn zu Viewlist mit Referenz
  include "connect.php";
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

  ?>

  <div class="container">
    <div class="header">
      <h1 class="Ueberschrift">EPSa Änderungsverwaltung</h1>
    </div>
    <div class="navigation">
      <div class="nav-item"><a href="login-eingabe.php">Login</a></div>
      <div class="nav-item"><a href="logout.php">Logout</a></div>
      <div class="nav-item"><a href="viewlist.php">Übersicht</a></div>
      <div class="nav-item"><a href="erzeugen.php">Erzeugen</a></div>
      <div class="nav-item"><a href="usercreate.php">Benutzer anlegen</a></div>
      <div class="nav-item"><a href="search.php">Suche</a></div>
    </div>
  </div>

  <form action="viewlist.php" method="post" name="form">
    <div class="search-form-s">
      <div class="search-header">
        <h2>Volltextsuche in den Stücklistenänderungen</h2>
      </div>
      <div class="search-body">
        <div class="form-group">
          <label for="search">Bitte Suchbegriff(e) eingeben:</label>
          <input class="input" name="search" id="search" type="text" maxlength="70" size="70" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>" />
        </div>
        <div class="form-group">
          <input class="button" name="submit" type="submit" value="Suche" />
        </div>
      </div>
    </div>

    <div class="user-info">
      <div class="info-item">
        <strong>angemeldet:</strong> <?php echo $host; ?>-<?php echo $ip; ?>
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
  </form>
</body>

</html>