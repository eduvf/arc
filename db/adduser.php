<?php
// Aquest script s'ha d'executar com a CLI

// Demana un nom d'usuari i contrasenya
// En demanar la contrasenya, es fa una crida a "stty"
// per a desactivar l'eco del teclat, evitant així
// que es mostri per pantalla la contrasenya mentre l'escrivim
echo "Nom d'usuari: ";
$user = trim(fgets(STDIN));
echo "Contrasenya: ";
system("stty -echo");
$pass = trim(fgets(STDIN));
system("stty echo");
echo "\n";

// Si cap dels dos camps és buit, dona error
if (strlen($user) == 0 || strlen($pass) == 0) {
    echo "ERROR: El nom d'usuari o contrasenya no poden ser buits.";
} else {
    $db = new SQLite3("arc.db");
    
    // La contrasenya es desa com a hash amb la configuració d'algorisme per defecte
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (user, pass) VALUES (:user, :pass)");
    $stmt->bindValue(":user", $user, SQLITE3_TEXT);
    $stmt->bindValue(":pass", $pass, SQLITE3_TEXT);
    $stmt->execute();

    $db->close();
}
?>