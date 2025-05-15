import os
import sys
import re

def split_sql_file(input_filepath, output_dir, inserts_per_file=2000):
    """
    Splits a large SQL file into smaller files based on the number of INSERT statements.

    Args:
        input_filepath (str): Path to the large input SQL file.
        output_dir (str): Directory where the split files will be saved.
        inserts_per_file (int): Maximum number of INSERT statements per output file.
    """
    if not os.path.exists(input_filepath):
        print(f"Fehler: Eingabedatei nicht gefunden: {input_filepath}")
        return

    if not os.path.isdir(output_dir):
        print(f"Warnung: Ausgabeverzeichnis {output_dir} existiert nicht. Erstelle es.")
        os.makedirs(output_dir, exist_ok=True)

    print(f"Starte das Aufteilen von {input_filepath}...")

    file_counter = 1
    insert_counter = 0
    output_file = None
    current_statement_lines = []
    is_in_insert_statement = False # Flag to help track multi-line INSERTs

    try:
        with open(input_filepath, 'r', encoding='utf-8') as infile:
            # Initiales Öffnen der ersten Ausgabedatei
            output_filename = os.path.join(output_dir, f'split_{file_counter:04d}.sql')
            output_file = open(output_filename, 'w', encoding='utf-8')
            print(f"Schreibe in Datei: {output_filename}")

            for line in infile:
                # Strippe führende/nachfolgende Leerzeichen und prüfe auf Kommentare
                stripped_line = line.strip()

                # Wenn Zeile mit Kommentar beginnt, einfach schreiben (oder ignorieren, hier schreiben)
                if stripped_line.startswith('--') or stripped_line.startswith('#'):
                     if output_file:
                        output_file.write(line)
                     continue

                # Füge die aktuelle Zeile zum aktuellen Statement hinzu
                current_statement_lines.append(line)

                # Prüfe, ob die Zeile ein Statement abschließt (endet mit Semikolon)
                if stripped_line.endswith(';'):
                    full_statement = "".join(current_statement_lines).strip()

                    # Prüfe, ob es sich um eine INSERT-Anweisung handelt
                    # Eine einfachere, aber oft funktionierende Prüfung: beginnt die nicht-kommentierte Zeile mit INSERT?
                    # Besser: Prüfe den zusammengefügten Statement-String
                    if full_statement.lower().startswith('insert'):
                         is_in_insert_statement = True # Bestätigt, dass wir in einem INSERT waren

                    # Schreibe das vollständige Statement in die aktuelle Ausgabedatei
                    if output_file:
                         output_file.writelines(current_statement_lines)

                    # Wenn es sich um eine INSERT-Anweisung handelte, zähle sie
                    if is_in_insert_statement:
                        insert_counter += 1
                        is_in_insert_statement = False # Setze Flag zurück

                    # Prüfe, ob ein Split notwendig ist
                    if insert_counter >= inserts_per_file:
                        if output_file:
                            output_file.close()
                            print(f"Datei geschlossen.")

                        file_counter += 1
                        insert_counter = 0
                        output_filename = os.path.join(output_dir, f'split_{file_counter:04d}.sql')
                        output_file = open(output_filename, 'w', encoding='utf-8')
                        print(f"Schreibe in neue Datei: {output_filename}")

                    # Setze das Statement zurück
                    current_statement_lines = []

                # Wenn die Zeile nicht mit Semikolon endet, ist es Teil eines multi-line Statements
                # Nichts weiter tun, einfach nächste Zeile lesen

    except Exception as e:
        print(f"Ein Fehler ist aufgetreten: {e}")
    finally:
        # Schließe die letzte Datei
        if output_file:
            output_file.close()
            print("Aufteilung abgeschlossen.")

# --- Skript Ausführung ---
if __name__ == "__main__":
    # VOR DEM AUSFÜHREN:
    # ÄNDERE DIE FOLGENDEN BEIDEN ZEILEN UND TRAGE DEINE PFADE EIN!
    # Passe auch inserts_per_chunk an, falls gewünscht.

    input_file = "pv_aend_sl.sql" # <-- DEINEN EINGABEPFAD HIER EINFÜGEN (z.B. C:/Users/DeinName/Dokumente/mein_dump.sql)
    output_folder = "split.sql"       # <-- DEINEN AUSGABEPFAD HIER EINFÜGEN (z.B. C:/Users/DeinName/Desktop/split_files)
    inserts_per_chunk = 2000 # <-- Anzahl der INSERTs pro Datei (kann angepasst werden, z.B. 1000, 5000, ...)

    # --- Ab hier muss nichts mehr geändert werden ---
    # (Es sei denn, es gibt Fehler im Skript selbst)

    # Einfache Prüfung, ob die Platzhalter noch da sind
    if input_file == "pv_aend_sl.sql" or \
       output_folder == "split.sql":
        print("\nFEHLER: Bitte trage deine tatsächlichen Pfade in den Variablen 'input_file' und 'output_folder' im Skript ein!")
        sys.exit(1)

    # Führe die Hauptfunktion aus
    split_sql_file(input_file, output_folder, inserts_per_chunk)