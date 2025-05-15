<?php
session_start(); // Session am Anfang starten
include "connect.php";
$session = session_id();
/*
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
}*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  
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


</body>

</html>