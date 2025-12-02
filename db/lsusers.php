<?php
// ARC
// Script de llistat d'usuaris
// 
// Aquest simple script permet visualitzar el llistat d'usuaris.

define("DB_FILE", "arc.db");

function check_db()
{
    if (!file_exists(DB_FILE)) {
        echo "ERROR: No s'ha trobat la base de dades.\n";
        exit(1);
    }
}

function list_users()
{
    $db = new SQLite3(DB_FILE);

    echo "USER\t| SU?\t| LAST CONNECTION\n";
    echo "-------------------------------------------------\n";

    $ls = $db->query("SELECT user, is_admin, last_conn FROM users");
    if ($ls === false) {
        echo "ERROR: Hi ha hagut un error recollint la llista d'usuaris.\n";
        exit(1);
    }

    while ($u = $ls->fetchArray()) {
        $user      = $u['user'];
        $is_admin  = $u['is_admin'];
        $last_conn = $u['last_conn'];
        echo "$user\t| $is_admin\t| $last_conn\n";
    }

    $db->close();
}

check_db();
list_users();
?>
