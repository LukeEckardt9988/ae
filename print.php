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
    <link rel="stylesheet" href="print.css" media="print">

</head>

<body>

    <?php
    include "connect.php";


    // 1. $id überprüfen und sicherstellen, dass es eine Zahl ist
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    // Oder $_POST['id'], je nachdem, woher die ID kommt

    if (!is_numeric($id)) {
        die("Ungültige ID."); // Oder eine andere Fehlerbehandlung
    }

    // 2. Prepared Statement verwenden
    $stmt = $conn->prepare("
    SELECT 
        data.*,
        CONCAT(pf.vorname, ' ', pf.nachname) AS namepf,
        CONCAT(pv.vorname, ' ', pv.nachname) AS namepv,
        CONCAT(pp.vorname, ' ', pp.nachname) AS namepp,
        CONCAT(pg.vorname, ' ', pg.nachname) AS namepg,
        DATE_FORMAT(z.dat_pf, '%d.%m.%Y %H:%i:%s') AS datpf,
        DATE_FORMAT(z.dat_pv, '%d.%m.%Y %H:%i:%s') AS datpv,
        DATE_FORMAT(z.dat_pp, '%d.%m.%Y %H:%i:%s') AS datpp,
        DATE_FORMAT(z.dat_pg, '%d.%m.%Y %H:%i:%s') AS datpg
    FROM data
    LEFT JOIN adlogin pf ON data.tbpf = pf.id
    LEFT JOIN adlogin pv ON data.tbpv = pv.id
    LEFT JOIN adlogin pp ON data.tbpp = pp.id
    LEFT JOIN adlogin pg ON data.tbpg = pg.id
    LEFT JOIN zustand z ON data.id = z.aend_id
    WHERE data.id = ?
");

    if ($stmt === false) {
        die("Fehler beim Vorbereiten des Statements: " . $conn->error);
    }

    $stmt->bind_param("i", $id); // "i" steht für Integer

    // 3. Statement ausführen und Fehler behandeln
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // 4. Daten sicher verwenden (mit htmlspecialchars)
            $chpf = isset($row["chpf"]) ? htmlspecialchars($row["chpf"]) : "";
            $chpp = isset($row["chpp"]) ? htmlspecialchars($row["chpp"]) : "";
            $chpg = isset($row["chpg"]) ? htmlspecialchars($row["chpg"]) : "";
            $chversion = isset($row["chversion"]) ? htmlspecialchars($row["chversion"]) : "";
            $chepsa = isset($row["chepsa"]) ? htmlspecialchars($row["chepsa"]) : "";
            $chrohs = isset($row["chrohs"]) ? htmlspecialchars($row["chrohs"]) : "";
            $chrev = isset($row["chrev"]) ? htmlspecialchars($row["chrev"]) : "";
            $chstueli = isset($row["chstueli"]) ? htmlspecialchars($row["chstueli"]) : "";
            $chnutzen = isset($row["chnutzen"]) ? htmlspecialchars($row["chnutzen"]) : "";
            $pbsapb = isset($row["pbsapb"]) ? htmlspecialchars($row["pbsapb"]) : "";
            $pbsapl = isset($row["pbsapl"]) ? htmlspecialchars($row["pbsapl"]) : "";
            $pbsaiap = isset($row["pbsaiap"]) ? htmlspecialchars($row["pbsaiap"]) : "";
            $pbsnp = isset($row["pbsnp"]) ? htmlspecialchars($row["pbsnp"]) : "";
            $pbsnpabap = isset($row["pbsnpabap"]) ? htmlspecialchars($row["pbsnpabap"]) : "";
            $plsapb = isset($row["plsapb"]) ? htmlspecialchars($row["plsapb"]) : "";
            $plsapl = isset($row["plsapl"]) ? htmlspecialchars($row["plsapl"]) : "";
            $plsaiap = isset($row["plsaiap"]) ? htmlspecialchars($row["plsaiap"]) : "";
            $plsnp = isset($row["plsnp"]) ? htmlspecialchars($row["plsnp"]) : "";
            $plsnpabap = isset($row["plsnpabap"]) ? htmlspecialchars($row["plsnpabap"]) : "";
            $sbsbbfav = isset($row["sbsbbfav"]) ? htmlspecialchars($row["sbsbbfav"]) : "";
            $sbsbgw = isset($row["sbsbgw"]) ? htmlspecialchars($row["sbsbgw"]) : "";
            $sbsbwv = isset($row["sbsbwv"]) ? htmlspecialchars($row["sbsbwv"]) : "";
            $sbsnpabap = isset($row["sbsnpabap"]) ? htmlspecialchars($row["sbsnpabap"]) : "";
            $slsbbfav = isset($row["slsbbfav"]) ? htmlspecialchars($row["slsbbfav"]) : "";
            $slsbgw = isset($row["slsbgw"]) ? htmlspecialchars($row["slsbgw"]) : "";
            $slsbwv = isset($row["slsbwv"]) ? htmlspecialchars($row["slsbwv"]) : "";
            $slsnpabap = isset($row["slsnpabap"]) ? htmlspecialchars($row["slsnpabap"]) : "";
            $sosbbfav = isset($row["sosbbfav"]) ? htmlspecialchars($row["sosbbfav"]) : "";
            $sosbgw = isset($row["sosbgw"]) ? htmlspecialchars($row["sosbgw"]) : "";
            $sosae = isset($row["sosae"]) ? htmlspecialchars($row["sosae"]) : "";
            $sosnv = isset($row["sosnv"]) ? htmlspecialchars($row["sosnv"]) : "";
            $spsbbfav = isset($row["spsbbfav"]) ? htmlspecialchars($row["spsbbfav"]) : "";
            $spsbgw = isset($row["spsbgw"]) ? htmlspecialchars($row["spsbgw"]) : "";
            $spsae = isset($row["spsae"]) ? htmlspecialchars($row["spsae"]) : "";
            $spsnv = isset($row["spsnv"]) ? htmlspecialchars($row["spsnv"]) : "";
            $tbpv = isset($row["tbpv"]) ? htmlspecialchars($row["tbpv"]) : "";
            $tbpf = isset($row["tbpf"]) ? htmlspecialchars($row["tbpf"]) : "";
            $tbpp = isset($row["tbpp"]) ? htmlspecialchars($row["tbpp"]) : "";
            $tbpg = isset($row["tbpg"]) ? htmlspecialchars($row["tbpg"]) : "";
            $tbhinweis = isset($row["tbhinweis"]) ? htmlspecialchars($row["tbhinweis"]) : "";
            $tbtechno = isset($row["tbtechno"]) ? htmlspecialchars($row["tbtechno"]) : "";
            $tbbez = isset($row["tbbez"]) ? htmlspecialchars($row["tbbez"]) : "";
            $tbprog = isset($row["tbprog"]) ? htmlspecialchars($row["tbprog"]) : "";
            $tbsachnr = isset($row["tbsachnr"]) ? htmlspecialchars($row["tbsachnr"]) : "";
            $tbepsanr = isset($row["tbepsanr"]) ? htmlspecialchars($row["tbepsanr"]) : "";
            $tbbslchange = isset($row["tbbslchange"]) ? htmlspecialchars($row["tbbslchange"]) : "";

            // Unterschriftsfelder
            $namepf = isset($row["namepf"]) ? htmlspecialchars($row["namepf"]) : "";
            $namepv = isset($row["namepv"]) ? htmlspecialchars($row["namepv"]) : "";
            $namepp = isset($row["namepp"]) ? htmlspecialchars($row["namepp"]) : "";
            $namepg = isset($row["namepg"]) ? htmlspecialchars($row["namepg"]) : "";
            $datpf = isset($row["datpf"]) ? htmlspecialchars($row["datpf"]) : "";
            $datpv = isset($row["datpv"]) ? htmlspecialchars($row["datpv"]) : "";
            $datpp = isset($row["datpp"]) ? htmlspecialchars($row["datpp"]) : "";
            $datpg = isset($row["datpg"]) ? htmlspecialchars($row["datpg"]) : "";

            // Zusammengesetzte Variablen für die Unterschriftsfelder (wie in deinem ursprünglichen Skript)
            $tbpf = $namepf . "\n" . $datpf;
            $tbpv = $namepv . "\n" . $datpv;
            $tbpp = $namepp . "\n" . $datpp;
            $tbpg = $namepg . "\n" . $datpg;

            // Hier kannst du mit den Daten weiterarbeiten, z.B. sie ausgeben:
            // echo "<div>" . $chpf . "</div>";

        } else {
            echo "Keine Daten für die angegebene ID gefunden.";
        }

        $stmt->close();
    } else {
        die("Fehler beim Ausführen des Statements: " . $stmt->error);
    }

    $conn->close();
    ?>
    <form action="aend-speich.php" method="post">
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
                        <input name="chpf" onclick="return (false);" type="checkbox" id="chpf" <?php echo ($chpf == '1') ? ' checked="checked" ' : ''; ?> />
                        PF
                    </div>
                    <div class="col-content text-center">
                        <input type="checkbox" name="chpp" onclick="return (false);" id="chpp" <?php echo ($chpp == '1') ? ' checked="checked" ' : ''; ?> />
                        PP
                    </div>
                    <div class="col-content text-center">
                        <input type="checkbox" name="chpg" onclick="return (false);" id="chpg" <?php echo ($chpg == '1') ? ' checked="checked" ' : ''; ?> />
                        PG
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
                            <input type="checkbox" name="chversion" onclick="return (false);" id="chversion" <?php echo ($chversion == '1') ? ' checked="checked" ' : ''; ?> />
                            Version geändert
                        </label>
                    </div>
                    <div class="checkbox-pair">
                        <label for="chrev">
                            <input type="checkbox" name="chrev" onclick="return (false);" id="chrev" <?php echo ($chrev == '1') ? ' checked="checked" ' : ''; ?> />
                            Revisionsstand geändert
                        </label>
                    </div>
                    <div class="checkbox-pair">
                        <label for="chepsa">
                            <input type="checkbox" name="chepsa" onclick="return (false);" id="chepsa" <?php echo ($chepsa == '1') ? ' checked="checked" ' : ''; ?> />
                            Neue EPSa-Nummer
                        </label>
                    </div>
                    <div class="checkbox-pair">
                        <label for="chstueli">
                            <input type="checkbox" name="chstueli" onclick="return (false);" id="chstueli" <?php echo ($chstueli == '1') ? ' checked="checked" ' : ''; ?> />
                            Stücklistenänderung
                        </label>
                    </div>
                    <div class="checkbox-pair">
                        <label for="chrohs">
                            <input type="checkbox" name="chrohs" onclick="return (false);" id="chrohs" <?php echo ($chrohs == '1') ? ' checked="checked" ' : ''; ?> />
                            RoHS-Umstellung
                        </label>
                    </div>
                    <div class="checkbox-pair">
                        <label for="chnutzen">
                            <input type="checkbox" name="chnutzen" onclick="return (false);" id="chnutzen" <?php echo ($chnutzen == '1') ? ' checked="checked" ' : ''; ?> />
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
                        <input type="text" size="10" readonly="readonly" maxlength="20" name="pbsapb" id="pbsapb" value="<?php echo $pbsapb; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="pbsapl" id="pbsapl" value="<?php echo $pbsapl; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="pbsaiap" id="pbsaiap" value="<?php echo $pbsaiap; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="pbsnp" id="pbsnp" value="<?php echo $pbsnp; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="pbsnpabap" id="pbsnpabap" value="<?php echo $pbsnpabap; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">Programm LS</div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="plsapb" id="plsapb" value="<?php echo $plsapb; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="plsapl" id="plsapl" value="<?php echo $plsapl; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="plsaiap" id="plsaiap" value="<?php echo $plsaiap; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="plsnp" id="plsnp" value="<?php echo $plsnp; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="plsnpabap" id="plsnpabap" value="<?php echo $plsnpabap; ?>" />
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
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sbsbbfav" id="sbsbbfav" value="<?php echo $sbsbbfav; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sbsbgw" id="sbsbgw" value="<?php echo $sbsbgw; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sbsbwv" id="sbsbwv" value="<?php echo $sbsbwv; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sbsnpabap" id="sbsnpabap" value="<?php echo $sbsnpabap; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">Schablone LS</div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="slsbbfav" id="slsbbfav" value="<?php echo $slsbbfav; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="slsbgw" id="slsbgw" value="<?php echo $slsbgw; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="slsbwv" id="slsbwv" value="<?php echo $slsbwv; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="slsnpabap" id="slsnpabap" value="<?php echo $slsnpabap; ?>" />
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
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sosbbfav" id="sosbbfav" value="<?php echo $sosbbfav; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sosbgw" id="sosbgw" value="<?php echo $sosbgw; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sosae" id="sosae" value="<?php echo $sosae; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="sosnv" id="sosnv" value="<?php echo $sosnv; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-label">Spezifikation</div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="spsbbfav" id="spsbbfav" value="<?php echo $spsbbfav; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="spsbgw" id="spsbgw" value="<?php echo $spsbgw; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="spsae" id="spsae" value="<?php echo $spsae; ?>" />
                    </div>
                    <div class="col-content text-center">
                        <input type="text" size="10" readonly="readonly" maxlength="10" name="spsnv" id="spsnv" value="<?php echo $spsnv; ?>" />
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
                        <textarea name="tbpv" readonly="readonly" cols="30" rows="2" id="tbpv" style="text-align:center"><?php echo $tbpv; ?></textarea>
                    </div>
                    <div class="col-content text-center">
                        PF:<br>
                        <textarea name="tbpf" readonly="readonly" cols="30" rows="2" id="tbpf" style="text-align:center"><?php echo $tbpf; ?></textarea>
                    </div>
                    <div class="col-content text-center">
                        PP:<br>
                        <textarea name="tbpp" readonly="readonly" cols="30" rows="2" id="tbpp" style="text-align:center"><?php echo $tbpp; ?></textarea>
                    </div>
                    <div class="col-content text-center">
                        PG:<br>
                        <textarea name="tbpg" readonly="readonly" cols="30" rows="2" id="tbpg" style="text-align:center"><?php echo $tbpg; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="section zusatzinfos-section table-section">
                <div class="row">
                    <div class="col-full text-right">
                        <label for="tbhinweis">Hinweis:
                            <input type="text" name="tbhinweis" id="tbhinweis" size="40" readonly="readonly" maxlength="40" value="<?php echo $tbhinweis; ?>" />
                        </label>
                    </div>
                    <div class="col-full text-right">
                        <label for="tbprog">Prog-Nr.:
                            <input type="text" name="tbprog" id="tbprog" size="40" readonly="readonly" maxlength="40" value="<?php echo $tbprog; ?>" />
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-full text-right">
                        <label for="tbtechno">Technologe:
                            <input type="text" name="tbtechno" id="tbtechno" size="40" readonly="readonly" maxlength="40" value="<?php echo $tbtechno; ?>" />
                        </label>
                    </div>
                    <div class="col-full text-right">
                        <label for="tbsachnr">Sach.-Nr.:
                            <input type="text" name="tbsachnr" id="tbsachnr" size="40" readonly="readonly" maxlength="40" value="<?php echo $tbsachnr; ?>" />
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-full text-right">
                        <label for="tbbez">Bezeich.:
                            <input type="text" name="tbbez" id="tbbez" size="40" maxlength="40" readonly="readonly" value="<?php echo $tbbez; ?>" />
                        </label>
                    </div>
                    <div class="col-full text-right">
                        <label for="tbepsanr">EPSa-Nr.:
                            <input type="text" name="tbepsanr" id="tbepsanr" size="40" maxlength="40" readonly="readonly" value="<?php echo $tbepsanr; ?>" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="section stuecklistenaenderungen-section umbruch_vor">
                <div class="row">
                    <div class="col-full text-center rubrikueberschrift">Stücklistenänderungen:</div>
                </div>
                <div class="row">
                    <div class="col-full">
                        <textarea name="tbbslchange" class="full-width-textarea" id="stuecklistenaenderungen-textarea" readonly="readonly"><?php echo $tbbslchange; ?></textarea>
                    </div>
                    <input type="hidden" name="ds_id" value="<?php echo $id; ?>">
                </div>
            </div>
        </div>
    </form>
</body>

</html>