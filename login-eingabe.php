<?php
session_start(); // Session am Anfang starten

include "connect.php";


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



  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="login-form">
      <div class="login-header">
        <h2>Bitte geben Sie Ihre Zugangsdaten ein</h2>
      </div>
      <div class="login-body">
        <div class="form-group">
          <label for="username">Username</label>
          <input class="input" type="text" id="username" name="username" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <label for="passlog">Passwort</label>
          <input class="input" type="password" id="passlog" name="passlog" size="24" maxlength="50">
        </div>
        <div class="form-group">
          <input class="button" type="submit" value="Login">
        </div>
      </div>
      <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $username = $_POST["username"];
          $passwort = md5($_POST["passlog"]);
          /* $passwort = $_POST["passlog"]; Ich hatte testweise das Hashen ausgeschaltet um meine Eigenen loggins zu prüfen */
        
          $ergebnis = mysqli_query($conn, "SELECT username, passwort, id, abt FROM adlogin WHERE username = '$username'");
        
          // Überprüfen, ob ein Ergebnis gefunden wurde
          if ($row = mysqli_fetch_row($ergebnis)) {
            $db_user = $row[0];
            $db_pass = $row[1];
            $db_id = $row[2];
            $db_abt = $row[3];
        
            if ($db_pass == $passwort) {
              $_SESSION["username"] = $username;
              $_SESSION["user_id_log"] = $db_id;
              $_SESSION["user_abt_log"] = $db_abt;
        
              // Weiterleitung zur Startseite nach erfolgreichem Login
              header("Location: start.php");
              exit;
            } else {
              echo "<div align='center'><big>Benutzername bzw. Passwort waren falsch...</div></big><br>";
            }
          } else {
            echo "<div align='center'><big>Benutzername nicht gefunden.</div></big><br>";
          }
        }
        ?>
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