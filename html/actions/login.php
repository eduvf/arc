<?php
session_start();

function login_error() {
    // Si tenim cap error en el procés d'inici ho indiquem amb 
    // una variable de sessió, que mostrarà un missatge d'error
    // a l'usuari.
    $_SESSION["failed_login"] = true;
    header("Location: /");
    exit();
}

// Prenem les credencials per a iniciar sessió i les verifiquem
$user = $_POST["user"];
$pass = $_POST["pass"];

// Si són buides, són invalides i tornem a l'inici
if (strlen($user) == 0 || strlen($pass) == 0) {
    login_error();
}

// Cerquem a la BD pel hash de la contrasenya
$db = new SQLite3("../../db/arc.db");

$stmt = $db->prepare("SELECT pass, is_admin FROM users WHERE user = :user");
$stmt->bindValue(":user", $user);
$result = $stmt->execute();

// Si no trobem l'usuari a la BD, és invalid
if ($result === false) {
    login_error();
}
$row = $result->fetchArray();

$hash     = $row["pass"];
$is_admin = $row["is_admin"];

$db->close();

// Validem si el hash coincideix amb les credencials donades
$valid = password_verify($pass, $hash);

// Si no coincideix, és invalid i tornem a l'inici
if (!$valid) {
    login_error();
}

// Si hem arribat aquí, és que l'inici de sessió és vàlid
// Ho indiquem amb dues variables de sessió i anem al tauler de control
$_SESSION["user"] = $user;
$_SESSION["is_admin"] = $is_admin;

header("Location: /dashboard.php");
?>
