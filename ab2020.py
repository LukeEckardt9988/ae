import re
import os
import sys # sys importieren, falls nicht schon geschehen

def clean_and_filter_sql_export_easy_debug():
    """
    Reinigt, aktualisiert die Kodierung und filtert INSERT Statements
    auf Daten, die neuer sind als das Jahr 2020. Enthält Debugging-Ausgaben.
    Verwendet fest definierte Ein- und Ausgabedateinamen im selben Verzeichnis.
    """

    # --- Konfiguration ---
    # NAME deiner originalen, vermurksten SQL-Exportdatei.
    # Diese Datei MUSS im selben Verzeichnis liegen wie dieses Skript.
    input_filename = 'pv_aend_sl-alt.sql'

    # NAME für die bereinigte und gefilterte Ausgabedatei.
    # Diese Datei wird im selben Verzeichnis erstellt.
    output_filename = 'pv_aend_sl-after2020.sql'

    # Jahr, ab dem die Daten beibehalten werden sollen (ausschließlich dieses Jahres).
    # Wir wollen Daten, die NEUER sind als 2020, also ab 2021.
    filter_year_threshold = 2020

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

    # Regex, um ein Datumsmuster in einem String-Literal zu finden (z.B. 'YYYY-MM-DD HH:MM:SS')
    # Sucht nach '<beliebiges Zeichen außer '>')'YYYY-MM-DD'
    date_pattern = re.compile(r"'\d{4}-\d{2}-\d{2}") # Sucht nach 'YYYY-MM-DD

    # Stelle sicher, dass die Eingabedatei existiert
    if not os.path.exists(input_filename):
        print(f"Fehler: Die Eingabedatei '{input_filename}' wurde nicht im selben Verzeichnis gefunden.")
        print("Bitte stelle sicher, dass deine originale SQL-Exportdatei diesen Namen hat")
        print("und sich im selben Ordner befindet wie das Skript.")
        return # Skript beenden, da die Datei fehlt

    print(f"Lese Datei: {input_filename}")
    print(f"Schreibe bereinigten und gefilterten Code nach: '{output_filename}'")
    print(f"Filtert Daten NEUER als Jahr {filter_year_threshold} (d.h. ab {filter_year_threshold + 1})")
    print("-" * 30) # Trennlinie für Debug-Ausgaben

    try:
        with open(input_filename, 'r', encoding='utf-8', errors='ignore') as infile, \
             open(output_filename, 'w', encoding='utf8') as outfile:

            line_num = 0 # Zähler für die Zeilennummer (zum Debugging)
            for line in infile:
                line_num += 1

                # 1. Ersetzungen für spezifische fehlerhafte Zeichenketten
                cleaned_line = repl_pattern.sub(lambda match: replacements[match.group(0)], line)

                # 2. Modernisierung der SQL-Befehle (Zeichenkodierung der Tabellen)
                cleaned_line = cleaned_line.replace('DEFAULT CHARSET=latin1', 'DEFAULT CHARSET=utf8mb4')
                cleaned_line = cleaned_line.replace('COLLATE=latin1_general_ci', 'COLLATE=utf8mb4_unicode_ci')
                # Füge hier ggf. weitere Ersetzungen für Kollationen hinzu, falls nötig

                # 3. Filterung basierend auf INSERT Statements und Datum
                if cleaned_line.strip().upper().startswith('INSERT INTO'):
                    # Debug-Ausgabe: INSERT Zeile gefunden
                    # print(f"Zeile {line_num}: INSERT gefunden.")

                    match = date_pattern.search(cleaned_line)
                    if match:
                        # Debug-Ausgabe: Datumsmuster gefunden
                        # print(f"Zeile {line_num}: Datumsmuster '{match.group(0)}' gefunden.")

                        # Extrahiere das Jahr aus dem gefundenen Datumstring
                        try:
                            year = int(match.group(0)[1:5]) # Extrahiere das Jahr aus 'YYYY-MM-DD'
                            # Debug-Ausgabe: Extrahiertes Jahr
                            # print(f"Zeile {line_num}: Extrahiertes Jahr: {year}. Filter Schwelle: {filter_year_threshold}.")

                            if year > filter_year_threshold:
                                # Debug-Ausgabe: Bedingung erfüllt, schreibe Zeile
                                # print(f"Zeile {line_num}: Jahr > {filter_year_threshold}. Zeile wird geschrieben.")
                                outfile.write(cleaned_line)
                            else:
                                # Debug-Ausgabe: Bedingung NICHT erfüllt, überspringe Zeile
                                pass # Zeile wird nicht geschrieben
                                # print(f"Zeile {line_num}: Jahr <= {filter_year_threshold}. Zeile wird übersprungen.")

                        except ValueError:
                            # Falls das Parsen des Jahres fehlschlägt
                            print(f"Zeile {line_num}: WARNUNG: Fehler beim Parsen des Jahres aus '{match.group(0)}'. Zeile wird übersprungen.")
                            pass # Zeile wird übersprungen, da Datum unklar

                    else:
                        # Debug-Ausgabe: Kein Datumsmuster gefunden in INSERT Zeile
                        print(f"Zeile {line_num}: WARNUNG: INSERT Zeile, aber kein Datumsmuster gefunden. Zeile wird übersprungen.")
                        pass # INSERT Zeile ohne Datum wird nicht geschrieben (entsprechend der Filterlogik)

                else:
                    # Nicht-INSERT Zeilen (CREATE TABLE, SET Commands, Kommentare, etc.)
                    # werden immer geschrieben, da sie für die Struktur benötigt werden.
                    # print(f"Zeile {line_num}: Keine INSERT Zeile. Wird immer geschrieben.")
                    outfile.write(cleaned_line)

            # Debug-Ausgabe am Ende
            print("-" * 30)
            print("Verarbeitung der Datei abgeschlossen.")


    except FileNotFoundError:
        print(f"Fehler: Die Eingabedatei '{input_filename}' wurde nicht gefunden.")
        print("Bitte stelle sicher, dass deine originale SQL-Exportdatei diesen Namen hat")
        print("und sich im selben Ordner befindet wie das Skript.")
    except Exception as e:
        print(f"Ein unerwarteter Fehler ist aufgetreten: {e}")
        # Optional: Traceback ausgeben für detailliertere Fehlersuche
        # import traceback
        # traceback.print_exc()


if __name__ == "__main__":
    clean_and_filter_sql_export_easy_debug()