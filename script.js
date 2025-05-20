$(document).ready(function() {
    $('#example').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": "get_data.php", 
      "columns": [
        { "data": "status" },
        { "data": "view_log" }, 
        { "data": "bearbeiter" },
        { "data": "epsanr" },
        { "data": "sachnr" },
        { "data": "bezeichnung" },
        { "data": "datum" },
        { "data": "pv" },
        { "data": "pf" },
        { "data": "pp" },
        { "data": "pg" }
      ],
      language: { 
        "sLengthMenu": "Zeige _MENU_ Einträge pro Seite",
        "sZeroRecords": "Nichts gefunden - sorry",
        "sInfo": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
        "sInfoEmpty": "Zeige 0 to 0 of 0 Einträge(n)",
        "sSearch": "Volltextsuche:",
        "sInfoFiltered": "(gefiltert von _MAX_ Gesamteinträgen)",
        "oPaginate": {
            "sFirst": "Erster",
            "sPrevious": "Zurück",
            "sNext": "Nächster",
            "sLast": "Letzter"
        }
      }
    });
  });




