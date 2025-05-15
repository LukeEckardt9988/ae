<?php
include "connect.php";

// SQL-Abfrage für die Gesamtanzahl der Einträge
$sql_count = "SELECT COUNT(*) 
    FROM data, zustand, adlogin
    WHERE data.id = zustand.aend_id 
    AND zustand.user_id = adlogin.id";

// SQL-Abfrage für die Daten mit Limit und Offset für die Paginierung
$sql = "SELECT 
    data.tbepsanr as epsanr,
    data.tbsachnr as sachnr,
    data.chpf as betpf,
    data.id as id,
    data.chpp as betpp,
    data.chpg as betpg,
    data.tbbez as bezeichnung,
    data.tbtechno as technologe,
    zustand.user_id as user_id,
    zustand.stat_pv as stpv,
    zustand.stat_pf as stpf,
    zustand.stat_pp as stpp,
    zustand.stat_pg as stpg,
    zustand.dat_ez as dez,
    zustand.dat_pv as dpv,
    zustand.dat_pf as dpf,
    zustand.dat_pp as dpp,
    zustand.dat_pg as dpg,
    adlogin.nachname as name,
    adlogin.vorname as vorname
    FROM data, zustand, adlogin
    WHERE data.id = zustand.aend_id 
    AND zustand.user_id = adlogin.id";

// Suche
if (isset($_GET['search']['value']) && !empty($_GET['search']['value'])) {
    $search = $_GET['search']['value'];
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
                    data.tbepsanr LIKE '%$search%' OR
                    data.tbsachnr LIKE '%$search%' OR
                    data.tbbez LIKE '%$search%' OR
                    data.tbtechno LIKE '%$search%' OR
                    data.tbbslchange LIKE '%$search%' OR
                    adlogin.nachname LIKE '%$search%' OR
                    adlogin.vorname LIKE '%$search%'
                    )";
}

// Sortierung
if (isset($_GET['order'])) {
    $order_column = $_GET['order'][0]['column'];
    $order_dir = $_GET['order'][0]['dir'];
    $columns = array(
        // Definiere die Spaltennamen für die Sortierung
        0 => 'zustand.stat_pv', 
        1 => 'adlogin.nachname', 
        2 => 'data.tbepsanr',
        // ... weitere Spalten
    );
    $sql .= " ORDER BY " . $columns[$order_column] . " " . $order_dir;
}

// Paginierung
$limit = $_GET['length'];
$start = $_GET['start'];
$sql .= " LIMIT $limit OFFSET $start";

$result = mysqli_query($conn, $sql);
$result_count = mysqli_query($conn, $sql_count);
$total_records = mysqli_fetch_array($result_count)[0];

$data = array();
while($row = mysqli_fetch_assoc($result)) {
  // Formatiere die Daten für DataTables
  $row['status'] =  generateStatusIcon($row); // Funktion für Status-Icons (siehe unten)
  $row['view_log'] = generateViewLogLinks($row['id']); // Funktion für Links (siehe unten)
  $row['bearbeiter'] = $row['name'] . ", " . $row['vorname'];
  $row['datum'] = $row['dez'];
  $row['pv'] = formatDate($row['dpv'], $row['stpv']);
  $row['pf'] = formatDate($row['dpf'], $row['stpf']);
  $row['pp'] = formatDate($row['dpp'], $row['stpp']);
  $row['pg'] = formatDate($row['dpg'], $row['stpg']);

  $data[] = $row;
}

// Ausgabe im JSON-Format für DataTables
echo json_encode(array(
  "draw" => $_GET['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_records,
  "data" => $data
));

// Hilfsfunktionen
function generateStatusIcon($row) {
    $stpv = $row['stpv'];
    $betpf = $row['betpf'];
    $stpf = $row['stpf'];
    $betpp = $row['betpp'];
    $stpp = $row['stpp'];
    $betpg = $row['betpg'];
    $stpg = $row['stpg'];

    if (!$stpv) {
        return "<img src='bilder/flag_red.png' alt='Nicht freigegeben'>";
    } elseif ($betpf == $stpf && $betpp == $stpp && $betpg == $stpg) {
        return "<img src='bilder/flag_green.png' alt='Freigegeben'>";
    } elseif ($stpv < $stpf || $stpv < $stpp || $stpv < $stpg || $betpf < $stpf || $betpp < $stpp || $betpg < $stpg) {
        return "<img src='bilder/warning.png' height='32' alt='Fehler'>";
    } else {
        return "<img src='bilder/flag_yellow.png' alt='In Bearbeitung'>";
    }
}

function generateViewLogLinks($id) {
    return "<a href='print.php?id=$id' onClick='FensterOeffnen(this.href); return false;'><img border='0' src='bilder/preview.png' alt='Ansicht / Drucken' title='Ansicht / Drucken'></a>
            <a href='log.php?id=$id' onClick='FensterOeffnen(this.href); return false;'><img border='0' src='bilder/log.png' height='32' alt='Logfile' title='Logfile'></a>";
}

function formatDate($date, $status) {
    return $status == 1 ? date("d.m.Y", strtotime($date)) : "Bedingung";
}

?>