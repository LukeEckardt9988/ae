<?php
session_start();
$session = session_id();

if (!isset($_SESSION["username"])) {
  header("Location: login-eingabe.php"); // Weiterleitung zur Login-Seite
  exit;
}

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
  if (!mysqli_num_rows($sql_user)) {
    echo "<div align='center'><big>Dazu reicht Ihre Berechtigung nicht aus... <a href=\"start.php\">Startseite</a></big></div><br>";
    exit;
  }
  $user_data = mysqli_fetch_assoc($sql_user);
  $abt = $user_data['abt'];
  $nachname = $user_data['nachname'];
  $vorname = $user_data['vorname'];
  $ma_aktiv = $abt . " / " . $vorname . " " . $nachname;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Aenderung erzeugen</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
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

  <script type="text/javascript">
    function FensterOeffnen(Adresse, targetWindow) { // Übergabe des target-Fensternamens
      MeinFenster = window.open(Adresse, targetWindow, "resizable=1,location=0,directories=0,status=0,menubar=1,scrollbars=1,toolbar=0,width=1024,height=768,left=0,top=0");
      MeinFenster.focus();
    }
  </script>
</head>

<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">


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


  <form action="epsanr_eks.php" method="post" name="form" target="bearbeitungsfenster">
    <div class="creation-form">
      <div class="creation-header">
        <h2>Änderung erzeugen</h2>
      </div>
      <div class="creation-body">
        <div class="form-group">
          <label for="tbepsanr">Bitte EPSa-Nummer eingeben</label>
          <input class="input" name="tbepsanr" type="text" maxlength="50" size="24" />
        </div>
        <div class="form-group">
          <input class="button" name="submit" type="submit" value="Speichern"  onclick="FensterOeffnen('epsanr_eks.php', 'bearbeitungsfenster'); /*location.href='start.php';*/" /> 
         </div>
      </div>
    </div>
  </form>


  <script>
  // Event Listener für Nachrichten vom neuen Fenster
  window.addEventListener('message', function(event) {
    if (event.data === 'speichernErfolgreich') {
      location.href = 'start.php';
    }
  });
</script>



</body>

</html>