<?php
session_start(); // Session am Anfang starten
include "connect.php";
$session = session_id();

// Überprüfen, ob die Session-Variable gesetzt ist
if (isset($_SESSION["username"])) {
  echo "NAME: " . $_SESSION["username"];

 // Nur Jagger darf User zulassen
  if ($_SESSION["username"] <> "jagger") {
    header("Location: login-eingabe.php"); // Weiterleitung zur Login-Seite wenn nicht Jagger angemeldet ist. 
    exit;
  } 
} else {
  // Wenn der Benutzer nicht angemeldet ist, leite ihn zur Anmeldeseite weiter
  header("Location: login-eingabe.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
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
    if (mysqli_num_rows($sql_user))  # falls der Login von der Änderungsverwaltung kommt
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
      </nav>
    </header>
  </div>


  <div class="tabellen-container">
  <form action="eintragen.php" method="post">
    <div class="user-creation-form">
      <div class="creation-header">
        <h2>User erzeugen</h2>
      </div>
      <div class="creation-body">
        <div class="form-group">
          <label for="vorname">Vorname</label>
          <input class="input" type="text" id="vorname" name="vorname" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="nachname">Nachname</label>
          <input class="input" type="text" id="nachname" name="nachname" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="abt">Abteilung</label>
          <input class="input" type="text" id="abt" name="abt" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input class="input" type="text" id="username" name="username" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="passwort">Passwort</label>
          <input class="input" type="password" id="passwort" name="passwort" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="passwort2">Passwort wiederholen</label>
          <input class="input" type="password" id="passwort2" name="passwort2" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <input class="button" type="submit" value="Speichern">
        </div>
      </div>
    </div>
  </form>
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

</body>

</html>