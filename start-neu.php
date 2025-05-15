<?php
session_start();
$session = session_id();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aenderungsprotokoll</title>
<link href="aend.css" rel="stylesheet" type="text/css" />
<style type="text/css" title="currentStyle">
	/*@import "media/css/demo_page.css";*/
	/*@import "media/css/demo_table.css";*/
	@import "media/css/demo_table_jui.css";
	@import "media/css/themes/ui-darkness/jquery-ui-1.8.15.custom.css";
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
#blauschattig { text-shadow:black 3px 2px 4px; font-size:32px; color:silver; }	
</style>
<script type="text/javascript" src="scripts/dhtml.js"></script>
<script type="text/javascript" src="media/js/jquery.js"></script>
<script type="text/javascript" src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#example').dataTable( {
		"oLanguage": {
			"sLengthMenu": "Zeige _MENU_ Einträge pro Seite",
			"sZeroRecords": "Nichts gefunden - sorry",
			"sInfo": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
			"sInfoEmpty": "Zeige 0 to 0 of 0 Einträge(n)",
			"sSearch": "Volltextsuche:",
			"sInfoFiltered": "(gefiltert von _MAX_ Gesamteinträgen)"
		},
	 	/*"sScrollY": 800,*/
		"sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"bJQueryUI": true
	} );
} );
</script>
<script type="text/javascript">
function ZeitAnzeigen () {
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

<script type="text/javascript">
function FensterOeffnen (Adresse) {
  MeinFenster = window.open(Adresse, "Zweitfenster", "resizable=1,location=0,directories=0,status=0,menubar=1,scrollbars=1,toolbar=0,width=1024,height=768,left=0,top=0");
  MeinFenster.focus();
}
</script>
</head>
<body onload="window.setTimeout('ZeitAnzeigen()', 1000);">
<?php
include "connect.php";
// echo "USER: ".$_POST["username"];;
if (!isset ($_SESSION["username"])) 
	{
  	$stat_bild = "<img src=bilder/user-offline.png>";
	$stat_schrift = "<font color='red'>Offline</font>";
	}
	else
	{
	$stat_bild = "<img src=bilder/user-online.png>";
	$stat_schrift = "<font color='green'>Online</font>";
	}
// echo $stat_bild;	
$ma_aktiv ="Gast";
if (isset ($_SESSION["username"])) 
	{
		$user = $_SESSION["username"];
		$sql_user=mysql_query("Select abt, nachname, vorname from adlogin where username = '$user'");
		if (mysql_num_rows($sql_user))	# falls der Login von der Änderungsverwaltung kommt
		{
		$abt= mysql_result($sql_user, 0, "abt") ;
		$nachname= mysql_result($sql_user, 0, "nachname") ;
		$vorname= mysql_result($sql_user, 0, "vorname") ;
		$ma_aktiv = $abt." / ".$vorname." ".$nachname;
		}
	}
?>   

<table width="1000" border="0" align="center" class="tab_grau" >
  <tr align="center" valign="middle">
    <td colspan="7" valign="top"><b class="Ueberschrift"><p id="whiteschattig">EPSa Änderungsverwaltung</p></b></td>
  </tr>
  <tr>
    <td width="25%" align="center" height="20%"></td>
    <td width="27" rowspan="6"><hr width="1" size="110"> &nbsp;</td>
    <td width="25%" align="center" height="20%">&nbsp;</td>
    <td width="27" rowspan="6"><hr width="1" size="110"> &nbsp;</td>
    <td width="25%" align="center" height="20%">&nbsp;</td>
    <td width="27" rowspan="6"><hr width="1" size="110"> &nbsp;</td>
    <td width="25%" align="center" height="20%">&nbsp;</td>
  </tr>
  <tr>
    <td width="25%" align="center" height="20%"><a href="login-eingabe.php">Login</a></td>
    <td width="25%" align="center" height="20%"><a href="erzeugen.php">Erzeugen</a></td>
    <td align="center"><a href="viewlist.php">Übersicht</a></td>
    <td align="center"><?php echo $stat_schrift; ?></td>
  </tr>
  <tr>
    <td width="25%" align="center" height="20%"><a href="logout.php">Logout</a></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td rowspan="4" align="center"><?php echo $stat_bild; ?></td>
  </tr>
  <tr>
    <td width="25%" align="center" height="20%"><a href="usercreate.php">Benutzer anlegen</a></td>
    <td align="center" height="20%">&nbsp;</td>
    <td width="25%" align="center" height="20%"><a href="search.php">Suche</a></td>
  </tr>
  <tr>
    <td align="center" height="20%">&nbsp;</td>
    <td align="center" height="20%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="25%" align="center" height="20%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="1000" border="0" align="center" >
          <tr> 
            <td width="400" align="left"><strong>angemeldet:</strong> <?php echo $host; ?>-<?php echo $ip; ?></td>
            <td width="300" align="center"><strong>Benutzer:</strong> <?php echo $ma_aktiv; ?></td>
            <td width="300" align="right" id="Uhr" div>&nbsp;</div></td>
          </tr>

</table><br /></br>

<?php
// Prüfen ob der angemeldete USER offene Änderungen hat -> anzeigen
// Weiterhin unterscheiden zu welcher Abteilung der User gehört!!
// PF/PP darf freigeben, aber nicht erzeugen!!

if (isset ($_SESSION["username"])) 
	{
		$user = $_SESSION["username"];
		$sql_user=mysql_query("Select id,abt, nachname, vorname from adlogin where username = '$user'");
		if (mysql_num_rows($sql_user))	# falls der Login von der Änderungsverwaltung kommt
		{
		$user_id= mysql_result($sql_user, 0, "id") ;
		$abt= mysql_result($sql_user, 0, "abt") ;
		$nachname= mysql_result($sql_user, 0, "nachname") ;
		$vorname= mysql_result($sql_user, 0, "vorname") ;
		}
// **********************************************************************************************************************************************************************************************************
// jetzt abteilungsspezifisch entsprechende Listen anzeigen
		if ($abt == "PV")
		{
		// In data alle Änderungen holen die keine PV - Freigabe besitzen
		$offen_daten=mysql_query("select
											z.user_id,
											z.dat_ez,
											d.tbsachnr,
											d.tbepsanr,
											d.tbbez,
											d.id
									from zustand as z
									LEFT OUTER JOIN data AS d ON d.id = z.aend_id
									where z.user_id = '$user_id' AND z.stat_pv ='0' ORDER BY d.tbsachnr ASC");
		$num = mysql_num_rows($offen_daten);
		if ($num ==0) { echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen von $vorname $nachname ($abt) vorhanden!</big></div></label><br>";}
		else
			{
			echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von $vorname $nachname ($abt)</big></div></label><br>";
			echo "<table align='center' border='1'<tr class='tab_rot'><td align='center'>Auswahl</td><td align='center'>EPSa-Nr.</td><td align='center'>Sach-Nr.</td><td align='center'>Bezeichnung</td><td align='center'>Datum</td></tr>";			
			// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
			for ($i=0; $i<$num; $i++)		
			{
			$userid= mysql_result($offen_daten, $i, "z.user_id") ;	
			$datum= mysql_result($offen_daten, $i, "z.dat_ez") ;
			$sachnr= mysql_result($offen_daten, $i, "d.tbsachnr") ;
			$epsanr= mysql_result($offen_daten, $i, "d.tbepsanr") ;
			$bez= mysql_result($offen_daten, $i, "d.tbbez") ;
			$id= mysql_result($offen_daten, $i, "d.id") ;				
			$datum = date("d.m.Y H:i:s",strtotime($datum));
			echo "<tr align='center'>
					<td><a href=print.php?id=$id onClick='FensterOeffnen(this.href); return false'><img border=0 src=bilder/print.png alt=Drucken title=Drucken></a><a href=edit.php?id=$id><img border=0 src=bilder/edit.png alt=Bearbeiten title=Bearbeiten></a><a href=freigabe_all.php?id=$id><img border=0 src=bilder/share.png alt='Freigabe PV' title='Freigabe PV'></a><a href=kill.php?id=$id><img border=0 src=bilder/kill.png alt=Entfernen title=Entfernen></a></td>
					<td>$epsanr</td>
					<td>$sachnr</td>
					<td>$bez</td>
					<td>$datum</td>
				</tr>";
			}
			echo "</table>";
			}
		}  // Ende if abt = PV!!!
// **********************************************************************************************************************************************************************************************************		
		if ($abt == "PF")
		{		
		// PF bekommt nur freigegebene PV und PF betreffende Änderungen zu Gesicht
		// 1. User ist PF
		$offen_daten=mysql_query("select
										z.user_id,
										z.dat_ez,
										d.tbsachnr,
										d.tbepsanr,
										d.tbbez,
										d.id
								from zustand as z
								LEFT OUTER JOIN data AS d ON d.id = z.aend_id
								where z.stat_pv = '1' AND z.stat_pf ='0' and d.chpf = '1' ORDER BY d.tbsachnr ASC");
			
		$num = mysql_num_rows($offen_daten);
		if ($num ==0) { echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PF vorhanden!</big></div></label><br>";}
		else
			{
			echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PF</big></div></label><br>";
			echo "<table align='center' border='1' class='display' id='example'>
					<thead><tr>
						<th align='center'>Auswahl</th>
						<th align='center'>EPSa-Nr.</th>
						<th align='center'>Sach-Nr.</th>
						<th align='center'>Bezeichnung</th>
						<th align='center'>Datum</th>
					</tr></thead><tbody>";			
			// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
			for ($i=0; $i<$num; $i++)		
			{
			$userid= mysql_result($offen_daten, $i, "z.user_id") ;	
			$datum= mysql_result($offen_daten, $i, "z.dat_ez") ;
			$sachnr= mysql_result($offen_daten, $i, "d.tbsachnr") ;
			$epsanr= mysql_result($offen_daten, $i, "d.tbepsanr") ;
			$bez= mysql_result($offen_daten, $i, "d.tbbez") ;
			$id= mysql_result($offen_daten, $i, "d.id") ;				
			$datum = date("d.m.Y H:i:s",strtotime($datum));
			echo "
					<tr align='center'>
					<td><a href=print.php?id=$id onClick='FensterOeffnen(this.href); return false'><img border=0 src=bilder/print.png alt=Drucken title=Drucken></a></a><a href=freigabe_all.php?id=$id><img border=0 src=bilder/share.png alt='Freigabe PF' title='Freigabe PF'></a></td>
					<td>$epsanr</td>
					<td>$sachnr</td>
					<td>$bez</td>
					<td>$datum</td>
				</tr>";
			}
			echo "</tbody></table>";
			}


		}   // Ende if abt = PF!!!
// **********************************************************************************************************************************************************************************************************		
		if ($abt == "PP")
		{		
		// PP bekommt nur freigegebene PV,PF Änderungen und PP betreffende Änderungen zu Gesicht
		// 1. User ist PP
		$offen_daten=mysql_query("select
										z.user_id,
										z.dat_ez,
										d.tbsachnr,
										d.tbepsanr,
										d.tbbez,
										d.id
								from zustand as z
								LEFT OUTER JOIN data AS d ON d.id = z.aend_id
								where z.stat_pv = '1' AND z.stat_pp ='0' and d.chpp = '1' ORDER BY d.tbsachnr ASC");
			
		$num = mysql_num_rows($offen_daten);
		if ($num ==0) { echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PP vorhanden!</big></div></label><br>";}
		else
			{
			echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PP</big></div></label><br>";
			echo "<table align='center' border='1'<tr class='tab_rot'><td align='center'>Auswahl</td><td align='center'>EPSa-Nr.</td><td align='center'>Sach-Nr.</td><td align='center'>Bezeichnung</td><td align='center'>Datum</td></tr>";			
			// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
			for ($i=0; $i<$num; $i++)		
			{
			$userid= mysql_result($offen_daten, $i, "z.user_id") ;	
			$datum= mysql_result($offen_daten, $i, "z.dat_ez") ;
			$sachnr= mysql_result($offen_daten, $i, "d.tbsachnr") ;
			$epsanr= mysql_result($offen_daten, $i, "d.tbepsanr") ;
			$bez= mysql_result($offen_daten, $i, "d.tbbez") ;
			$id= mysql_result($offen_daten, $i, "d.id") ;				
			$datum = date("d.m.Y H:i:s",strtotime($datum));
			echo "<tr align='center'>
					<td><a href=print.php?id=$id onClick='FensterOeffnen(this.href); return false'><img border=0 src=bilder/print.png alt=Drucken title=Drucken></a><a href=freigabe_all.php?id=$id><img border=0 src=bilder/share.png alt='Freigabe PP' title='Freigabe PP'></a></td>
					<td>$epsanr</td>
					<td>$sachnr</td>
					<td>$bez</td>
					<td>$datum</td>
				</tr>";
			}
			echo "</table>";
			}


		}   // Ende if abt = PP!!!
// **********************************************************************************************************************************************************************************************************		
		if ($abt == "PG")
		{		
		// PG bekommt nur freigegebene PV,PF Änderungen und PG betreffende Änderungen zu Gesicht
		// 1. User ist PG
		$offen_daten=mysql_query("select
										z.user_id,
										z.dat_ez,
										d.tbsachnr,
										d.tbepsanr,
										d.tbbez,
										d.id
								from zustand as z
								LEFT OUTER JOIN data AS d ON d.id = z.aend_id
								where z.stat_pv = '1' AND z.stat_pg ='0' and d.chpg = '1' ORDER BY d.tbsachnr ASC");
			
		$num = mysql_num_rows($offen_daten);
		if ($num ==0) { echo "<label><div align='center' class='rubrikueberschrift'><big>Keine offenen Änderungen für PG vorhanden!</big></div></label><br>";}
		else
			{
			echo "<label><div align='center' class='rubrikueberschrift'><big>Offene Änderungen von PG</big></div></label><br>";
			echo "<table align='center' border='1'<tr class='tab_rot'><td align='center'>Auswahl</td><td align='center'>EPSa-Nr.</td><td align='center'>Sach-Nr.</td><td align='center'>Bezeichnung</td><td align='center'>Datum</td></tr>";			
			// Schleife durch alle Mitarbeiter -> somit ist auch die ID bekannt
			for ($i=0; $i<$num; $i++)		
			{
			$userid= mysql_result($offen_daten, $i, "z.user_id") ;	
			$datum= mysql_result($offen_daten, $i, "z.dat_ez") ;
			$sachnr= mysql_result($offen_daten, $i, "d.tbsachnr") ;
			$epsanr= mysql_result($offen_daten, $i, "d.tbepsanr") ;
			$bez= mysql_result($offen_daten, $i, "d.tbbez") ;
			$id= mysql_result($offen_daten, $i, "d.id") ;				
			$datum = date("d.m.Y H:i:s",strtotime($datum));
			echo "<tr align='center'>
					<td><a href=print.php?id=$id onClick='FensterOeffnen(this.href); return false'><img border=0 src=bilder/print.png alt=Drucken title=Drucken></a><a href=freigabe_all.php?id=$id><img border=0 src=bilder/share.png alt='Freigabe PP' title='Freigabe PP'></a></td>
					<td>$epsanr</td>
					<td>$sachnr</td>
					<td>$bez</td>
					<td>$datum</td>
				</tr>";
			}
			echo "</table>";
			}


		}   // Ende if abt = PG!!!		
		
	} // Ende isset(username)
?>	