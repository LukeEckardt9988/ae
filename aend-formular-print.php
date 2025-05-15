<?php
session_start();
$session = session_id();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aenderungsprotokoll</title>
<style type="text/css">
@page { size:21.0cm 29.7cm; margin:5.7cm 2cm 1.4cm 1cm; }
.umbruch_vor
{
page-break-before: always
}
.tableBorder {
	border: 1px solid #000000;
	}
.spaltenbeschriftung {
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
#rundrum {
  border-width:2px;
  border-style:solid;
  border-color:blue;
  padding:1mm;
  text-align:justify; }
.Ueberschrift {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 36em;
}
.zeilenbeschriftung {
	font-family: Verdana, Geneva, sans-serif;
	font-style: italic;
	font-weight: bold;
	font-size: 20px;
}
.rubrikueberschrift {
	font-family: Verdana, Geneva, sans-serif;
	text-align: center;
	font-weight: bold;
	font-style: italic;
	text-decoration: underline;
}
#blauschattig { text-shadow:black 3px 2px 4px; font-size:32px; color:blue; }
</style>
</head>
<body>

<?php
if (!isset ($_SESSION["username"])) {
  echo "<label><div align='center'><big>Bitte erst einloggen... <a href=\"start.php\">Startseite</a></big></div></label><br>";
  exit;
}
?>
<form action="aend-speich.php" method="post">
<table width="100%" border="0">
	<tr>
    	<td width="30%"><a href="start.php">zur Startseite</a></td>
    	<td colspan="4" align="center"><b class="Ueberschrift"><p id="blauschattig">Änderungsprotokoll</p></b></td>
        <td width="30%">&nbsp;</td>
    </tr>

</table>
<table width="100%" border="0" id="rundrum">
  <tr>
    <td width="16%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
	<td align="center"><span class="rubrikueberschrift">betrifft:</span></td>
    <td width="16%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
	<td width="16%">&nbsp;</td>    
  </tr>
  <tr>
    <td class="zeilenbeschriftung">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="16%" align="center"><input type="checkbox" name="chpf" id="chpf" <?php echo isset($_POST['chpf']) ? ' checked="checked" ' : ''; ?> />
      PF</td>
    <td width="16%" align="center"><input type="checkbox" name="chpp" id="chpp" <?php echo isset($_POST['chpp']) ? ' checked="checked" ' : ''; ?> />
      PP</td>
    <td width="16%" align="center"><input type="checkbox" name="chpg" id="chpg" <?php echo isset($_POST['chpg']) ? ' checked="checked" ' : ''; ?> />
      PG</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table><br />
<table width="100%" border="0" id="rundrum">
  <tr>
    <td colspan="4" class="rubrikueberschrift">Grund der Änderung:</td>
    </tr>
  <tr>
    <td width="25%" align="right" onclick="document.getElementById('chversion').checked='checked';"> Version geändert:</td>
    <td width="25%"><input type="checkbox" name="chversion" id="chversion" <?php echo isset($_POST['chversion']) ? ' checked="checked" ' : ''; ?> /></td>
    <td width="25%" align="right" onclick="document.getElementById('chrev').checked='checked';">Revisionsstand geändert:</td>
    <td width="25%"><input type="checkbox" name="chrev" id="chrev" <?php echo isset($_POST['chrev']) ? ' checked="checked" ' : ''; ?> /></td>
  </tr>
  <tr>
    <td width="25%" align="right" onclick="document.getElementById('chepsa').checked='checked';"> Neue EPSa-Nummer:</td>
    <td width="25%" align="left"><input type="checkbox" name="chepsa" id="chepsa" <?php echo isset($_POST['chepsa']) ? ' checked="checked" ' : ''; ?> /></td>
    <td width="25%" align="right" onclick="document.getElementById('chstueli').checked='checked';"> Stücklistenänderung:</td>
    <td width="25%"><input type="checkbox" name="chstueli" id="chstueli" <?php echo isset($_POST['chstueli']) ? ' checked="checked" ' : ''; ?> /></td>
  </tr>
  <tr>
    <td width="25%" align="right" onclick="document.getElementById('chrohs').checked='checked';"> RoHS-Umstellung:</td>
    <td width="25%"><input type="checkbox" name="chrohs" id="chrohs" <?php echo isset($_POST['chrohs']) ? ' checked="checked" ' : ''; ?> /></td>
    <td width="25%" align="right" onclick="document.getElementById('chnutzen').checked='checked';">LP / Nutzen neu:</td>
    <td width="25%"><input type="checkbox" name="chnutzen" id="chnutzen" <?php echo isset($_POST['chnutzen']) ? ' checked="checked" ' : ''; ?> /></td>
  </tr>
</table></br>
<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor ="#0000FF">
  <tr>
    <td class="zeilenbeschriftung" width="17%" align="center">Bestückung</td>
    <td width="16%" align="center" class="spaltenbeschriftung">Altes Programm bleibt.</td>
    <td width="16%" align="center" class="spaltenbeschriftung">Altes Programm löschen.</td>
    <td width="16%" align="center" class="spaltenbeschriftung">Änderung im aktuellen Programm.</td>
    <td width="16%" align="center" class="spaltenbeschriftung">Neues Programm.</td>
    <td width="16%" align="center" class="spaltenbeschriftung">Neues Programm auf Basis altes Programm.</td>
  </tr>
  <tr>
    <td width="17%" align="left" class="spaltenbeschriftung">Programm BS</td>
    <td align="center"><input type="text" size="10" maxlength="10" name="pbsapb" id="pbsapb" value="<?php echo $pbsapb; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="pbsapl" id="pbsapl" value="<?php echo $pbsapl; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="pbsaiap" id="pbsaiap" value="<?php echo $pbsaiap; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="pbsnp" id="pbsnp" value="<?php echo $pbsnp; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="pbsnpabap" id="pbsnpabap" value="<?php echo $pbsnpabap; ?>"/></td>
  </tr>
  <tr>
    <td width="17%" align="left" class="spaltenbeschriftung">Programm LS</td>
    <td align="center"><input type="text" size="10" maxlength="10" name="plsapb" id="plsapb" value="<?php echo $plsapb; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="plsapl" id="plsapl" value="<?php echo $plsapl; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="plsaiap" id="plsaiap" value="<?php echo $plsaiap; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="plsnp" id="plsnp" value="<?php echo $plsnp; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="plsnpabap" id="plsnpabap" value="<?php echo $plsnpabap; ?>"/></td>
  </tr>
</table>
</br>
<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor ="#0000FF">
  <tr>
    <td class="zeilenbeschriftung" width="20%" align="center">Bestückung</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Bisherige bleibt für alte Version.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Bisherige gilt weiter.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Bisherige wird verschrottet.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Neue bestellt.</td>
    </tr>
  <tr>
    <td width="20%" align="left" class="spaltenbeschriftung">Schablone BS</td>
    <td align="center"><input type="text" size="10" maxlength="10" name="sbsbbfav" id="sbsbbfav" value="<?php echo $sbsbbfav; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="sbsbgw" id="sbsbgw" value="<?php echo $sbsbgw; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="sbsbwv" id="sbsbwv" value="<?php echo $sbsbwv; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="sbsnpabap" id="sbsnpabap" value="<?php echo $sbsnpabap; ?>"/></td>
    </tr>
  <tr>
    <td width="20%" align="left" class="spaltenbeschriftung">Schablone LS</td>
    <td align="center"><input type="text" size="10" maxlength="10" name="slsbbfav" id="slsbbfav" value="<?php echo $slsbbfav; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="slsbgw" id="slsbgw" value="<?php echo $slsbgw; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="slsbwv" id="slsbwv" value="<?php echo $slsbwv; ?>"/></td>
    <td align="center"><input type="text" size="10" maxlength="10" name="slsnpabap" id="slsnpabap" value="<?php echo $slsnpabap; ?>"/></td>
    </tr>
</table>
</br>
<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor ="#0000FF">
  <tr>
    <td class="zeilenbeschriftung" width="20%" align="center">Prüfung</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Bisherige bleibt für alte Version.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Bisherige gilt weiter.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Änderung enthalten.</td>
    <td width="20%" align="center" class="spaltenbeschriftung">Neue Version.</td>
    </tr>
  <tr>
    <td width="20%" align="left" class="spaltenbeschriftung">Software</td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="sosbbfav" id="sosbbfav" value="<?php echo $sosbbfav; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="sosbgw" id="sosbgw" value="<?php echo $sosbgw; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="sosae" id="sosae" value="<?php echo $sosae; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="sosnv" id="sosnv" value="<?php echo $sosnv; ?>"/></td>
    </tr>
  <tr>
    <td width="20%" align="left" class="spaltenbeschriftung">Spezifikation</td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="spsbbfav" id="spsbbfav" value="<?php echo $spsbbfav; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="spsbgw" id="spsbgw" value="<?php echo $spsbgw; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="spsae" id="spsae" value="<?php echo $spsae; ?>"/></td>
    <td width="20%" align="center"><input type="text" size="10" maxlength="10" name="spsnv" id="spsnv" value="<?php echo $spsnv; ?>"/></td>
    </tr>
</table>
</br>
<table width="100%" border="0">
  <tr>
    <td colspan="4" class="rubrikueberschrift">Änderungsnachweis:</td>
    </tr>
  <tr>
    <td width="25%" align="center" class="spaltenbeschriftung">PV:<input type="text" name="tbpv" id="tbpv" size="30" maxlength="30" value="<?php echo $tbpv; ?>"/></td>
    <td width="25%" align="center" class="spaltenbeschriftung">PF:<input type="text" name="tbpf" id="tbpf" size="30" maxlength="30" value="<?php echo $tbpf; ?>"/></td>
    <td width="25%" align="center" class="spaltenbeschriftung">PP:<input type="text" name="tbpp" id="tbpp" size="30" maxlength="30" value="<?php echo $tbpp; ?>"/></td>
    <td width="25%" align="center" class="spaltenbeschriftung">PG:<input type="text" name="tbpg" id="tbpg" size="30" maxlength="30" value="<?php echo $tbpg; ?>"/></td>    
  </tr>
  </table></br>
  <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor ="#0000FF">
  <tr>
    <td colspan="2" align="right" class="spaltenbeschriftung">Hinweis: <input type="text" name="tbhinweis" id="tbhinweis" size="40" maxlength="40" value="<?php echo $tbhinweis; ?>"/></td>
    <td width="50%" align="right" class="spaltenbeschriftung">Prog-Nr.: <input type="text" name="tbprog" id="tbprog" size="40" maxlength="40" value="<?php echo $tbprog; ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="spaltenbeschriftung">Technologe: <input type="text" name="tbtechno" id="tbtechno" size="40" maxlength="40" value="<?php echo $tbtechno; ?>"/></td>
    <td width="50%" align="right" class="spaltenbeschriftung">Sach.-Nr.: <input type="text" name="tbsachnr" id="tbsachnr" size="40" maxlength="40" value="<?php echo $tbsachnr; ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="spaltenbeschriftung">Bezeich.: <input type="text" name="tbbez" id="tbbez" size="40" maxlength="40" value="<?php echo $tbbez; ?>"/></td>
    <td width="50%" align="right" class="spaltenbeschriftung">EPSa-Nr.: <input type="text" name="tbepsanr" id="tbepsanr" size="40" maxlength="40" value="<?php echo $tbepsanr; ?>"/></td>
  </tr>
</table></br>
<table width="100%" border="0" class="umbruch_vor">
  <tr>
  <td class="rubrikueberschrift" align="center">Stücklistenänderungen:</td>
  </tr>
  <tr>
  <td align="center"><textarea name="tbbslchange" id="tbbslchange" cols="78" rows="70" ><?php echo $tbbslchange; ?></textarea></td>
  </tr>
</table>




</form>
</body>
</html>