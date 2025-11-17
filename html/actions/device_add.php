<?php
require('_lib.php');
session_start();

_login_check_is_user_admin();

// Prenem l'IP i nom del dispositiu que volem afegir
$ip = trim($_POST["dev_ip"]);
$name = trim($_POST["dev_name"]);

// Si cap dels dos camps és buit, ho marquem com a error
if (strlen($ip) == 0 || strlen($name) == 0) {
    $_SESSION["add_device_error"] = "ERROR: Falten camps necessaris per a afegir el dispositiu.";
    header("Location: /dashboard.php");
    exit();
}

// Si el nom no és vàlid, ho marquem com a error
if (preg_match('/^\w+$/', $name) === false) {
    $_SESSION["add_device_error"] = "ERROR: El nom només pot contenir lletres (A-Z i a-z), dígits (0-9) i '_'.";
    header("Location: /dashboard.php");
    exit();
}

// Si l'IP no és una IPv4, ho marquem com a error
if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
    $_SESSION["add_device_error"] = "ERROR: L'IP ha de ser IPv4.";
    header("Location: /dashboard.php");
    exit();
}

// Fem la inserció a la BD
$db = new SQLite3("../../db/arc.db");

$stmt = $db->prepare("INSERT OR REPLACE INTO devices (dev_ip, dev_name) VALUES (:d_ip, :d_name)");
$stmt->bindValue(":d_ip", $ip);
$stmt->bindValue(":d_name", $name);

// Executem la comanda i comprovem que hagi funcionat
if ($stmt->execute() === false) {
    $_SESSION["add_device_error"] = "ERROR: No s'ha pogut afegir el dispositiu degut a un error intern.";
    header("Location: /dashboard.php");
    exit();
}

$db->close();

header("Location: /dashboard.php");
?>
