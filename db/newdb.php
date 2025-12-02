<?php
// ARC
// Script d'inicialització de la base de dades
// 
// La base de dades és un fitxer d'SQLite3, situat en el mateix 
// directori que aquest script.

define("DB_FILE", "arc.db");

function ask_if_file_exists_or_rewrite()
{
    if (file_exists(DB_FILE)) {
        echo "INFO: El fitxer de la base de dades (".DB_FILE.") ja existeix en aquest directori.\n";
        echo "Vols eliminar-lo? [s/N] ";

        if (strtolower(trim(fgets(STDIN))) != "s") {
            exit(0);
        }

        if (unlink(DB_FILE)) {
            echo "INFO: Fitxer eliminat.\n";
        } else {
            echo "ERROR: No s'ha pogut eliminar el fitxer.\n";
            exit(1);
        }
    }
}

function check_ownership()
{
    // La base de dades ha de ser propietat de l'usuari amb què s'executen les
    // comandes amb PHP; o bé, aquest usuari n'hi ha de tenir accés de lectura
    // i d'escriptura. Si això no es pot fer automàticament, s'emet un avís.

    if (posix_getpwuid(fileowner(DB_FILE))['name'] !== 'www-data') {
        if (!chown(DB_FILE, "www-data")) {
            echo "WARN: No s'ha pogut canviar la propietat de la base de dades a l'usuari 'www-data'.";
        }
    }
}

function check_error($ok) {
    if (!$ok) {
        echo "ERROR: S'ha produit un error en inicialitzar la base de dades.\n";
        exit(1);
    }
}

ask_if_file_exists_or_rewrite();

$db = new SQLite3(DB_FILE);

check_ownership();

$ok = $db->exec("CREATE TABLE users (
user TEXT PRIMARY KEY,
pass TEXT NOT NULL,
is_admin BOOLEAN NOT NULL,
last_conn DATETIME
)");

check_error($ok);

$ok = $db->exec("CREATE TABLE devices (
dev_name TEXT PRIMARY KEY,
dev_ip TEXT NOT NULL,
last_conn DATETIME,
cert_data TEXT
)");

check_error($ok);

$ok = $db->exec("CREATE TABLE logs (
id INTEGER PRIMARY KEY AUTOINCREMENT,
msg TEXT,
time_at DATETIME
)");

check_error($ok);




$db->close();

echo "INFO: Base de dades inicialitzada correctament.\n";
?>
