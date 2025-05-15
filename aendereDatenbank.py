import re
import os # Importiere das os-Modul für Pfade

def clean_sql_export_easy():
    """
    Reinigt einen SQL-Export von spezifischen fehlerhaften Zeichenkodierungen
    und aktualisiert die Tabellenkodierung auf utf8mb4.
    Verwendet fest definierte Ein- und Ausgabedateinamen im selben Verzeichnis.
    """

    # --- Konfiguration ---
    # NAME deiner originalen, vermurksten SQL-Exportdatei.
    # Diese Datei MUSS im selben Verzeichnis liegen wie dieses Skript.
    input_filename = 'pv_aend_sl-alt.sql'

    # NAME für die bereinigte Ausgabedatei.
    # Diese Datei wird im selben Verzeichnis erstellt.
    output_filename = 'pv_aend_sl-cleaned.sql'

    # Deine Liste der Ersetzungen: 'fehlerhafte_sequenz': 'korrektes_zeichen'
    # Die Reihenfolge ist wichtig, längere fehlerhafte Sequenzen zuerst!
    replacements = {
        'ÃƒÂ¼': 'ü',
        'ÃƒÂ¤': 'ä',
        'ÃƒÂ¶': 'ö',
        'Ãƒâ€“': 'Ö',
        'ÃƒÂ–': 'Ö', # Doppeltes Mapping für Ö
        'Ã¶': 'ö',   # Anderes Muster für ö
        'Ã¯Â¿Â½?': 'Ä', # Spezifisches Muster für Ä
        'Ãƒ?': 'Ä',   # Anderes Muster für Ä
        'Ãƒâ€ž': 'Ä', # Anderes Muster für Ä
        'ÃƒÂ„': 'Ä',   # Anderes Muster für Ä
        'Ã‚Âµ': 'µ',
        'Ã¯Â¿Â½': 'µ', # Anderes Muster für µ
        'ÃƒÂœ': 'Ü',
        'ÃƒÂŸ': 'ß',
        'ÃƒÅ': 'ß',   # Anderes Muster für ß
        'Ã‚Â°': '°',
        'Ã¢Â€Â¢': '•',
        'Ã¢Â€Â“': '-', # Em dash Mojibake zu Bindestrich
        'Ã‚Â´s': 's', # Akut + s Mojibake zu s
        'Ã¢Â€Âž': '*', # Linkes Anführungszeichen Mojibake zu *
        'Ã¢Â€Âœ': '*', # Rechtes Anführungszeichen Mojibake zu *
        # Füge hier bei Bedarf weitere Ersetzungen hinzu
    }
    # --- Ende Konfiguration ---


    # Kompilieren eines einzigen Regex-Musters für alle zu ersetzenden Sequenzen
    # Sortiere die Keys nach Länge absteigend, damit längere Muster zuerst passen
    repl_pattern = re.compile('|'.join(re.escape(key) for key in sorted(replacements, key=len, reverse=True)))

    # Stelle sicher, dass die Eingabedatei existiert
    if not os.path.exists(input_filename):
        print(f"Fehler: Die Eingabedatei '{input_filename}' wurde nicht im selben Verzeichnis gefunden.")
        print("Bitte stelle sicher, dass deine originale SQL-Exportdatei diesen Namen hat")
        print("und sich im selben Ordner befindet wie das Skript.")
        return # Skript beenden, da die Datei fehlt

    print(f"Lese Datei: {input_filename}")
    print(f"Schreibe bereinigten Code nach: {output_filename}")

    try:
        with open(input_filename, 'r', encoding='utf-8', errors='ignore') as infile, \
             open(output_filename, 'w', encoding='utf8') as outfile:

            for line in infile:
                # Annahme: Die original Datei könnte schon versuchen UTF-8 zu sein,
                # aber die Daten sind falsch. Wir lesen als UTF-8 und ignorieren Fehler,
                # um die fehlerhaften Sequenzen zu erhalten.

                # 1. Ersetzungen für spezifische fehlerhafte Zeichenketten
                cleaned_line = repl_pattern.sub(lambda match: replacements[match.group(0)], line)

                # 2. Modernisierung der SQL-Befehle (Zeichenkodierung der Tabellen)
                # Ersetze latin1 durch utf8mb4 und latin1_general_ci durch utf8mb4_unicode_ci
                cleaned_line = cleaned_line.replace('DEFAULT CHARSET=latin1', 'DEFAULT CHARSET=utf8mb4')
                cleaned_line = cleaned_line.replace('COLLATE=latin1_general_ci', 'COLLATE=utf8mb4_unicode_ci')
                # Füge hier ggf. weitere Ersetzungen für Kollationen hinzu, falls nötig

                # 3. Sicherstellen, dass IDENTIFIER (Datenbank-, Tabellen-, Spaltennamen)
                # mit Backticks korrekt sind. Dies sollte bei einem properen Export passen,
                # aber falls nötig, hier anpassen.

                outfile.write(cleaned_line)

        print(f"Bereinigung abgeschlossen. Die bereinigte Datei ist '{output_filename}'.")

    except Exception as e:
        print(f"Ein Fehler ist aufgetreten: {e}")

if __name__ == "__main__":
    clean_sql_export_easy()