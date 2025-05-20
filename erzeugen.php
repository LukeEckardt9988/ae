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

  <script type="text/javascript">
    function FensterOeffnen(Adresse, targetWindow) { // Übergabe des target-Fensternamens
      MeinFenster = window.open(Adresse, targetWindow, "resizable=1,location=0,directories=0,status=0,menubar=1,scrollbars=1,toolbar=0,width=1024,height=768,left=0,top=0");
      MeinFenster.focus();
    }
  </script>
</head>

<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">


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
      </nav>
    </header>
  </div>




  <div class="tabellen-container">
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
            <input class="button" name="submit" type="submit" value="Speichern" onclick="FensterOeffnen('epsanr_eks.php', 'bearbeitungsfenster'); /*location.href='start.php';*/" />
          </div>
        </div>
      </div>
    </form>
  </div>


  <script>
    // Event Listener für Nachrichten vom neuen Fenster
    window.addEventListener('message', function(event) {
      if (event.data === 'speichernErfolgreich') {
        location.href = 'start.php';
      }
    });
  </script>


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