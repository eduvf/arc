<?php
// ARC
// Script de creació/modificació d'usuari
// 
// Els usuaris d'identifiquen amb un nom d'usuari i una contrasenya
// que es desa a la base de dades com a un hash amb les configuracions
// predeterminades de PHP.
// 
// Els usuaris també es classifiquen en superusuaris (amb permisos per
// connectar-se i administrar els dispositius Android) i en usuaris
// regulars (que només tenen accés als logs).

define("DB_FILE", "arc.db");

function ask_username_and_password()
{
    // Es fa una crida a l'utilitat 'stty' per a canviar la configuració
    // del terminal temporalment, evitant que es mostri per pantalla la
    // contrasenya.

    echo "Nom d'usuari: ";
    $user = trim(fgets(STDIN));
    echo "Contrasenya: ";
    system("stty -echo");
    $pass = trim(fgets(STDIN));
    system("stty echo");
    echo "\n";

    if (strlen($user) == 0 || strlen($pass) == 0) {
        echo "ERROR: El nom d'usuari o contrasenya no poden ser buits.\n";
        exit(1);
    }

    echo "És un superusuari? [s/N] ";
    $adm = (strtolower(trim(fgets(STDIN))) == "s");

    // Fem el hash de la contrasenya amb la configuració per defecte
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    return ['user' => $user, 'pass' => $pass, 'adm' => $adm];
}

function check_db()
{
    if (!file_exists(DB_FILE)) {
        echo "ERROR: No s'ha trobat la base de dades.\n";
        exit(1);
    }
}

function create_user($user, $pass, $adm)
{
    $db = new SQLite3(DB_FILE);

    $stmt = $db->prepare("INSERT OR REPLACE INTO users (user, pass, is_admin) VALUES (:user, :pass, :adm)");
    $stmt->bindValue(":user", $user);
    $stmt->bindValue(":pass", $pass);
    $stmt->bindValue(":adm", $adm);

    if ($stmt->execute() === false) {
        echo "ERROR: No s'ha pogut afegir l'usuari.\n";
        exit(1);
    }
    $db->close();
}

check_db();

$new_user = ask_username_and_password();

create_user($new_user["user"], $new_user["pass"], $new_user["adm"]);

?>
