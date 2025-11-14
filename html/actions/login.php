<?php

session_start();

// Prenem les credencials per a iniciar sessió i les verifiquem
$user = $_POST["user"];
$pass = $_POST["pass"];

// Si són buides, són invalides i tornem a l'inici
// La variables de sessió "failed login" mostrarà un missatge a l'inici
if (strlen($user) == 0 || strlen($pass) == 0) {
    $_SESSION["failed login"] = true;
    header("Location: /");
    exit();
}

// Cerquem a la BD pel hash de la contrasenya
$db = new SQLite3("../../db/arc.db");

$stmt = $db->prepare("SELECT pass FROM users WHERE user = :user");
$stmt->bindValue(":user", $user, SQLITE3_TEXT);
$result = $stmt->execute();
$hash = $result->fetchArray()["pass"];

$db->close();

// Validem si coincideix amb les credencials donades
$valid = password_verify($pass, $hash);

// La variables de sessió "user" ens indica si hem iniciat sessió (i amb quin usuari)
if ($valid) {
    $_SESSION["user"] = $user;
    header("Location: /dashboard.php");
} else {
    $_SESSION["user"] = null;
    $_SESSION["failed login"] = true;
    header("Location: /");
}
?>
