<?php
session_start();
$session = session_id();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aenderungsprotokoll</title>
  <link rel="stylesheet" href="sPrint.css">
  

  <script language="JavaScript">
    function countLetters(textarea) {
      var laenge = 4680; // Hier die gewünschte Zeichenlänge angeben
      var zeichenErlaubt = document.getElementById('zeichen-erlaubt'); // Paragraph auswählen

      var verbleibendeZeichen = laenge - textarea.value.length;
      zeichenErlaubt.innerHTML = '<b>Noch ' + verbleibendeZeichen + ' Zeichen erlaubt!</b>';

      if (textarea.value.length > laenge) {
        textarea.value = textarea.value.substring(0, parseInt(laenge));
        zeichenErlaubt.innerHTML = '<b>Noch 0 Zeichen erlaubt!</b>';
      }
    }
  </script>
</head>

<body>

  <?php
  if (!isset($_SESSION["username"])) {
    echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
  }
  ?>
  <?php
  include "connect.php";
  // *******************************************************************************************************************
  // Änderungen dürfen nur von Technologen bearbeitet werden und auch nur dann, wenn keine Freigabe erfolgt ist
  // Der Link wird über die ID geholt, da man das auch manuell machen kann muss geprüft werden, ob es auch der Owner ist
  // ******************************************************************************************************************

  // ID aus der URL oder dem Formular holen (sicherstellen, dass $id definiert ist!)
  // Beispiel für GET-Parameter:
  $id = isset($_GET['id']) ? $_GET['id'] : null;

  // Sicherheit: Prüfen, ob $id gesetzt und numerisch ist
  if (!is_numeric($id)) {
    echo "<label><div align='center'><big>Ungültige ID übergeben. <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
  }

  // Prepared Statement mit explizitem JOIN und Aliasen
  $sql = "SELECT
            a.username,
            z.stat_pv
        FROM
            data d
        INNER JOIN adlogin a ON d.user_id = a.id
        INNER JOIN zustand z ON z.aend_id = d.id
        WHERE
            d.id = ?";

  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id); // "i" für Integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    if ($row) { // Prüfen, ob überhaupt Daten gefunden wurden
      $user_name = $row['username'];
      $zstand = $row['stat_pv'];

      if ($_SESSION["username"] != $user_name || $zstand == '1') {
        echo "<label><div align='center'><big>Sie haben keine Berechtigung diese Änderung zu bearbeiten... <a href=\"start.php\">Startseite</a></big></div></label><br>";
        exit;
      }
    } else {
      // Behandle den Fall, dass keine Daten gefunden wurden
      echo "<label><div align='center'><big>Datensatz nicht gefunden oder keine Berechtigung.<a href=\"start.php\">Startseite</a></big></div></label><br>";
      exit;
    }

    mysqli_stmt_close($stmt);
  } else {
    // Fehlerbehandlung, falls das Prepared Statement nicht erstellt werden konnte
    echo "<label><div align='center'><big>Fehler bei der Datenbankabfrage: " . mysqli_error($conn) . "<a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
  }


  ?>
  <?php

  // Angenommen, $conn ist dein mysqli Verbindungsobjekt.
  // $id sollte idealerweise aus einer vertrauenswürdigen Quelle stammen und validiert sein.

  $sql = "SELECT * FROM data WHERE id = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Fehler beim Vorbereiten des Statements: " . $conn->error);
  }

  $stmt->bind_param("i", $id); // "i" steht für Integer, passe es an den Datentyp von $id an

  if ($stmt->execute() === false) {
    die("Fehler beim Ausführen des Statements: " . $stmt->error);
  }

  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row) {
    $chpf = $row["chpf"];
    $chpp = $row["chpp"];
    $chpg = $row["chpg"];
    $chversion = $row["chversion"];
    $chepsa = $row["chepsa"];
    $chrohs = $row["chrohs"];
    $chrev = $row["chrev"];
    $chstueli = $row["chstueli"];
    $chnutzen = $row["chnutzen"];
    $pbsapb = $row["pbsapb"];
    $pbsapl = $row["pbsapl"];
    $pbsaiap = $row["pbsaiap"];
    $pbsnp = $row["pbsnp"];
    $pbsnpabap = $row["pbsnpabap"];
    $plsapb = $row["plsapb"];
    $plsapl = $row["plsapl"];
    $plsaiap = $row["plsaiap"];
    $plsnp = $row["plsnp"];
    $plsnpabap = $row["plsnpabap"];
    $sbsbbfav = $row["sbsbbfav"];
    $sbsbgw = $row["sbsbgw"];
    $sbsbwv = $row["sbsbwv"];
    $sbsnpabap = $row["sbsnpabap"];
    $slsbbfav = $row["slsbbfav"];
    $slsbgw = $row["slsbgw"];
    $slsbwv = $row["slsbwv"];
    $slsnpabap = $row["slsnpabap"];
    $sosbbfav = $row["sosbbfav"];
    $sosbgw = $row["sosbgw"];
    $sosae = $row["sosae"];
    $sosnv = $row["sosnv"];
    $spsbbfav = $row["spsbbfav"];
    $spsbgw = $row["spsbgw"];
    $spsae = $row["spsae"];
    $spsnv = $row["spsnv"];
    $tbpv = $row["tbpv"];
    $tbpf = $row["tbpf"];
    $tbpp = $row["tbpp"];
    $tbpg = $row["tbpg"];
    $tbhinweis = $row["tbhinweis"];
    $tbtechno = $row["tbtechno"];
    $tbbez = $row["tbbez"];
    $tbprog = $row["tbprog"];
    $tbsachnr = $row["tbsachnr"];
    $tbepsanr = $row["tbepsanr"];
    $tbbslchange = $row["tbbslchange"];
  } else {
    // Behandle den Fall, dass keine Daten gefunden wurden
    echo "Keine Daten für die ID gefunden.";
  }

  $stmt->close();

  ?>
  <form action="aend-speich.php" method="post" name="form">
    <div class="container">
      <div class="print-header">
        <div class="header-left">
          <h2>Änderungsprotokoll</h2>
        </div>
        <div class="header-right">
          <p class="print-date"><?php echo date("d.m.Y"); ?></p>
        </div>
      </div>

      <div class="section betrifft-section">
        <div class="row">
          <div class="col-full text-center">
            <span class="rubrikueberschrift">betrifft:</span>
          </div>
        </div>
        <div class="row">
          <div class="col-content text-center">
            <input type="checkbox" name="chpf" id="chpf" <?php echo ($chpf == '1') ? ' checked="checked" ' : ''; ?> /> PF
          </div>
          <div class="col-content text-center">
            <input type="checkbox" name="chpp" id="chpp" <?php echo ($chpp == '1') ? ' checked="checked" ' : ''; ?> /> PP
          </div>
          <div class="col-content text-center">
            <input type="checkbox" name="chpg" id="chpg" <?php echo ($chpg == '1') ? ' checked="checked" ' : ''; ?> /> PG
          </div>
        </div>
      </div>

      <div class="section grund-aenderung-section">
        <div class="row">
          <div class="col-full rubrikueberschrift">Grund der Änderung:</div>
        </div>
        <div class="row checkbox-row">
          <div class="checkbox-pair">
            <label for="chversion">
              <input type="checkbox" name="chversion" id="chversion" <?php echo ($chversion == '1') ? ' checked="checked" ' : ''; ?> />
              Version geändert
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chrev">
              <input type="checkbox" name="chrev" id="chrev" <?php echo ($chrev == '1') ? ' checked="checked" ' : ''; ?> />
              Revisionsstand geändert
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chepsa">
              <input type="checkbox" name="chepsa" id="chepsa" <?php echo ($chepsa == '1') ? ' checked="checked" ' : ''; ?> />
              Neue EPSa-Nummer
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chstueli">
              <input type="checkbox" name="chstueli" id="chstueli" <?php echo ($chstueli == '1') ? ' checked="checked" ' : ''; ?> />
              Stücklistenänderung
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chrohs">
              <input type="checkbox" name="chrohs" id="chrohs" <?php echo ($chrohs == '1') ? ' checked="checked" ' : ''; ?> />
              RoHS-Umstellung
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chnutzen">
              <input type="checkbox" name="chnutzen" id="chnutzen" <?php echo ($chnutzen == '1') ? ' checked="checked" ' : ''; ?> />
              LP / Nutzen neu
            </label>
          </div>
        </div>
      </div>

      <div class="section programm-bestueckung-section table-section">
        <div class="row">
          <div class="col-label text-center">Bestückung</div>
          <div class="col-content text-center">Altes Programm bleibt.</div>
          <div class="col-content text-center">Altes Programm löschen.</div>
          <div class="col-content text-center">Änderung im aktuellen Programm.</div>
          <div class="col-content text-center">Neues Programm.</div>
          <div class="col-content text-center">Neues Programm auf Basis altes Programm.</div>
        </div>
        <div class="row">
          <div class="col-label">Programm BS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsapb" id="pbsapb" value="<?php echo $pbsapb; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsapl" id="pbsapl" value="<?php echo $pbsapl; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsaiap" id="pbsaiap" value="<?php echo $pbsaiap; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsnp" id="pbsnp" value="<?php echo $pbsnp; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsnpabap" id="pbsnpabap" value="<?php echo $pbsnpabap; ?>" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Programm LS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsapb" id="plsapb" value="<?php echo $plsapb; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsapl" id="plsapl" value="<?php echo $plsapl; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsaiap" id="plsaiap" value="<?php echo $plsaiap; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsnp" id="plsnp" value="<?php echo $plsnp; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsnpabap" id="plsnpabap" value="<?php echo $plsnpabap; ?>" />
          </div>
        </div>
      </div>

      <div class="section schablone-bestueckung-section table-section">
        <div class="row">
          <div class="col-label text-center">Bestückung</div>
          <div class="col-content text-center">Bisherige bleibt für alte Version.</div>
          <div class="col-content text-center">Bisherige gilt weiter.</div>
          <div class="col-content text-center">Bisherige wird verschrottet.</div>
          <div class="col-content text-center">Neue bestellt.</div>
        </div>
        <div class="row">
          <div class="col-label">Schablone BS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsbbfav" id="sbsbbfav" value="<?php echo $sbsbbfav; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsbgw" id="sbsbgw" value="<?php echo $sbsbgw; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsbwv" id="sbsbwv" value="<?php echo $sbsbwv; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsnpabap" id="sbsnpabap" value="<?php echo $sbsnpabap; ?>" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Schablone LS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbbfav" id="slsbbfav" value="<?php echo $slsbbfav; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbgw" id="slsbgw" value="<?php echo $slsbgw; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbwv" id="slsbwv" value="<?php echo $slsbwv; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsnpabap" id="slsnpabap" value="<?php echo $slsnpabap; ?>" />
          </div>
        </div>
      </div>

      <div class="section pruefung-section table-section">
        <div class="row">
          <div class="col-label text-center">Prüfung</div>
          <div class="col-content text-center">Bisherige bleibt für alte Version.</div>
          <div class="col-content text-center">Bisherige gilt weiter.</div>
          <div class="col-content text-center">Änderung enthalten.</div>
          <div class="col-content text-center">Neue Version.</div>
        </div>
        <div class="row">
          <div class="col-label">Software</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosbbfav" id="sosbbfav" value="<?php echo $sosbbfav; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosbgw" id="sosbgw" value="<?php echo $sosbgw; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosae" id="sosae" value="<?php echo $sosae; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosnv" id="sosnv" value="<?php echo $sosnv; ?>" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Spezifikation</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsbbfav" id="spsbbfav" value="<?php echo $spsbbfav; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsbgw" id="spsbgw" value="<?php echo $spsbgw; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsae" id="spsae" value="<?php echo $spsae; ?>" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsnv" id="spsnv" value="<?php echo $spsnv; ?>" />
          </div>
        </div>
      </div>

      <div class="section aenderungsnachweis-section">
        <div class="row">
          <div class="col-full rubrikueberschrift">Änderungsnachweis:</div>
        </div>
        <div class="row">
          <div class="col-content text-center">
            PV:<br>
            <textarea name="tbpv" readonly cols="30" rows="2" id="tbpv" style="text-align:center"><?php echo $tbpv; ?></textarea>
          </div>
          <div class="col-content text-center">
            PF:<br>
            <textarea name="tbpf" readonly cols="30" rows="2" id="tbpf" style="text-align:center"><?php echo $tbpf; ?></textarea>
          </div>
          <div class="col-content text-center">
            PP:<br>
            <textarea name="tbpp" readonly cols="30" rows="2" id="tbpp" style="text-align:center"><?php echo $tbpp; ?></textarea>
          </div>
          <div class="col-content text-center">
            PG:<br>
            <textarea name="tbpg" readonly cols="30" rows="2" id="tbpg" style="text-align:center"><?php echo $tbpg; ?></textarea>
          </div>
        </div>
      </div>

      <div class="section zusatzinfos-section table-section">
        <div class="row">
          <div class="col-full text-right">
            <label for="tbhinweis">Hinweis:
              <input type="text" name="tbhinweis" id="tbhinweis" size="40" maxlength="40" value="<?php echo $tbhinweis; ?>" />
            </label>
          </div>
          <div class="col-full text-right">
            <label for="tbprog">Prog-Nr.:
              <input type="text" name="tbprog" id="tbprog" size="40" maxlength="40" value="<?php echo $tbprog; ?>" />
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-full text-right">
            <label for="tbtechno">Technologe:
              <input type="text" name="tbtechno" id="tbtechno" size="40" maxlength="40" value="<?php echo $tbtechno; ?>" />
            </label>
          </div>
          <div class="col-full text-right">
            <label for="tbsachnr">Sach.-Nr.:
              <input type="text" name="tbsachnr" id="tbsachnr" size="40" maxlength="40" value="<?php echo $tbsachnr; ?>" />
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-full text-right">
            <label for="tbbez">Bezeich.:
              <input type="text" name="tbbez" id="tbbez" size="40" maxlength="40" value="<?php echo $tbbez; ?>" />
            </label>
          </div>
          <div class="col-full text-right">
            <label for="tbepsanr">EPSa-Nr.:
              <input type="text" name="tbepsanr" id="tbepsanr" size="40" maxlength="40" readonly value="<?php echo $tbepsanr; ?>" />
            </label>
          </div>
        </div>
      </div>

      <div class="section stuecklistenaenderungen-section umbruch_vor">
        <div class="row">
          <div class="col-full text-center rubrikueberschrift">Stücklistenänderungen:</div>
        </div>
        <div class="row">
          <div class="col-half text-left">
            <p id="zeichen-erlaubt">
              <b>Noch 4680 Zeichen erlaubt!</b>
            </p>
          </div>
          <div class="col-half text-right">
            <input class="button" type="submit" name="speichern" id="speichern" value="Änderung speichern" />
          </div>
        </div>
        <div class="row">
          <div class="col-full">
            <textarea name="tbbslchange" class="full-width-textarea" id="stuecklistenaenderungen-textarea" onkeyup="countLetters(this)"><?php echo $tbbslchange; ?></textarea>
          </div>
        </div>
        <input type="hidden" name="ds_id" value="<?php echo $id; ?>" />
      </div>
    </div>
  </form>
</body>

</html>