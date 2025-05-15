<?php

$session = session_id();
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aenderungsprotokoll</title>
  <link rel="stylesheet" href="sPrint.css">
</head>
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
</head>

<body>

  <?php

  $tbbez = isset($_SESSION['tbbez']) ? $_SESSION['tbbez'] : '';



  if (!isset($_SESSION["username"])) {
    echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
    exit;
  }
  ?>
  <form action="aend-formular-speich.php" method="post" name="form">

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
            <input type="checkbox" name="chpf" id="chpf" /> PF
          </div>
          <div class="col-content text-center">
            <input type="checkbox" name="chpp" id="chpp" /> PP
          </div>
          <div class="col-content text-center">
            <input type="checkbox" name="chpg" id="chpg" /> PG
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
              <input type="checkbox" name="chversion" id="chversion" />
              Version geändert
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chrev">
              <input type="checkbox" name="chrev" id="chrev" />
              Revisionsstand geändert
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chepsa">
              <input type="checkbox" name="chepsa" id="chepsa" />
              Neue EPSa-Nummer
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chstueli">
              <input type="checkbox" name="chstueli" id="chstueli" />
              Stücklistenänderung
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chrohs">
              <input type="checkbox" name="chrohs" id="chrohs" />
              RoHS-Umstellung
            </label>
          </div>
          <div class="checkbox-pair">
            <label for="chnutzen">
              <input type="checkbox" name="chnutzen" id="chnutzen" />
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
            <input type="text" size="10" maxlength="10" name="pbsapb" id="pbsapb" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsapl" id="pbsapl" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsaiap" id="pbsaiap" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsnp" id="pbsnp" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="pbsnpabap" id="pbsnpabap" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Programm LS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsapb" id="plsapb" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsapl" id="plsapl" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsaiap" id="plsaiap" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsnp" id="plsnp" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="plsnpabap" id="plsnpabap" />
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
            <input type="text" size="10" maxlength="10" name="sbsbbfav" id="sbsbbfav" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsbgw" id="sbsbgw" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsbwv" id="sbsbwv" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sbsnpabap" id="sbsnpabap" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Schablone LS</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbbfav" id="slsbbfav" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbgw" id="slsbgw" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsbwv" id="slsbwv" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="slsnpabap" id="slsnpabap" />
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
            <input type="text" size="10" maxlength="10" name="sosbbfav" id="sosbbfav" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosbgw" id="sosbgw" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosae" id="sosae" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="sosnv" id="sosnv" />
          </div>
        </div>
        <div class="row">
          <div class="col-label">Spezifikation</div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsbbfav" id="spsbbfav" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsbgw" id="spsbgw" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsae" id="spsae" />
          </div>
          <div class="col-content text-center">
            <input type="text" size="10" maxlength="10" name="spsnv" id="spsnv" />
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
            <textarea name="tbpv" readonly cols="30" rows="2" id="tbpv" style="text-align:center"></textarea>
          </div>
          <div class="col-content text-center">
            PF:<br>
            <textarea name="tbpf" readonly cols="30" rows="2" id="tbpf" style="text-align:center"></textarea>
          </div>
          <div class="col-content text-center">
            PP:<br>
            <textarea name="tbpp" readonly cols="30" rows="2" id="tbpp" style="text-align:center"></textarea>
          </div>
          <div class="col-content text-center">
            PG:<br>
            <textarea name="tbpg" readonly cols="30" rows="2" id="tbpg" style="text-align:center"></textarea>
          </div>
        </div>
      </div>

      <div class="section zusatzinfos-section table-section">
        <div class="row">
          <div class="col-full text-right">
            <label for="tbhinweis">Hinweis:
              <input type="text" name="tbhinweis" id="tbhinweis" size="40" maxlength="40" />
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
              <input type="text" name="tbtechno" id="tbtechno" size="40" maxlength="40" />
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
              <input type="text" name="tbbez" id="tbbez" size="40" maxlength="40" readonly value="<?php echo $tbbez; ?>" />
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
            <textarea name="tbbslchange" class="full-width-textarea" id="stuecklistenaenderungen-textarea" onkeyup="countLetters(this)"></textarea>
          </div>
        </div>
        <input type="hidden" name="ds_id" value="<?php echo $id; ?>" />
      </div>
    </div>
  </form>



</body>

</html>