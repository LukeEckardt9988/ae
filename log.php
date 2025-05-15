<?php
session_start();
$session = session_id();
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$id = intval($id); 
	$ds_id = $id; 
  } else {
	echo "<label><div align='center'><big>Fehler: Keine ID übergeben!</big></div></label><br>";
	exit;
  }
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aenderungsprotokoll</title>
<link rel="stylesheet" href="style.css">
<style type="text/css">
@page { size:21.0cm 29.7cm; margin:5.7cm 2cm 1.4cm 1cm; }
.umbruch_vor
{
page-break-before: always
}
.tableBorder {
	border: 1px solidrgb(255, 255, 255);
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
.nichtDrucken { 
    display: none;
  }
</style>
</head>
<body>

<?php
include "connect.php";
// *******************************************************************************************************************
// OK -> Keinerlei Abfragen --> simple print -> Jeder darf das sehen
// *******************************************************************************************************************
$sql_data=mysqli_query($conn, "SELECT
								l.aend_id,
								l.vorgang,
								l.dat,
								DATE_FORMAT(l.dat, '%d.%m.%Y %H:%i:%s') as datdeutsch,
								l.user_id,
						
								d.tbsachnr,
								d.tbepsanr,
						
								a.vorname,
								a.nachname,
								a.abt
						FROM
								log as l
								LEFT OUTER JOIN data AS d ON d.id = l.aend_id
								LEFT OUTER JOIN adlogin AS a ON a.id = l.user_id
						WHERE
								l.aend_id ='$id'
						ORDER BY l.dat ASC   ");
	if (!$sql_data) 
	{
	echo "<table width='800px'><tr><td align ='center'><br><br><b><strong><font color='red'>Keine Log - Daten verfügbar!<br>
								Bitte informieren Sie M.Jäger (216) -> Datensatz: $id
								</font></strong></b></td></tr></table>"; 	
	exit;
	}
						
		$num_temp = mysqli_num_rows($sql_data);
		for ($i=0; $i<$num_temp; $i++)
		{
		$row = mysqli_fetch_assoc($sql_data);

		$vorgang= $row["vorgang"];
		$abt= $row["abt"];
		$datdeutsch= $row["datdeutsch"];
		$sachnr= $row["tbsachnr"];
		$epsanr= $row["tbepsanr"];
		$vorname= $row["vorname"];
		$nachname= $row["nachname"];
		
		// Kopfzeile in der 1. Schleife erzeugen
		if ($i == '0')
			{
			// KOPF erzeugen
				echo "<table width='800px' border=1 align='center'>
				<tr class=tabhead> <td align=center>Sachnummer</td><td align=center>EPSa-Nr.</td></tr>
				<tr class=tabbody><td align=center>$sachnr</td><td align=center>$epsanr</td></tr>
			  </table><p>";			
				echo "<table width='800px' border=1 align='center'>
				<tr class=tabhead><td align=center>Datum</td><td align=center>Vorgang</td><td align=center>Bearbeiter</td><td align=center>Abteilung</td></tr>";
			}
			// Befüllen
				echo "<tr class=tabbody><td align=center>$datdeutsch&nbsp;</td><td align=center>$vorgang&nbsp;</td><td align=center>$vorname $nachname&nbsp;</td><td align=center>$abt&nbsp;</td></tr>";
		}	
			// Tabelle closen		
			 echo"</table>";			
	

?>




</body>
</html>