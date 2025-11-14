<?php
session_start();

if (empty($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Prenem l'IP i nom del dispositiu que volem afegir
$ip = trim($_POST["ipdev"]);
$name = trim($_POST["namedev"]);

// Si cap dels dos camps és buit, ho marquem com a error
if (strlen($ip) == 0 || strlen($name) == 0) {
    $_SESSION["add device error"] = "ERROR: Falten camps necessaris per a afegir el dispositiu.";
    header("Location: /dashboard.php");
    exit();
}

// Si l'IP no és una IPv4, ho marquem com a error
if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
    $_SESSION["add device error"] = "ERROR: L'IP ha de ser IPv4.";
    header("Location: dashboard.php");
    exit();
}

// Fem la inserció a la BD
$db = new SQLite3("../../db/arc.db");

$stmt = $db->prepare("INSERT INTO devices (ip, dname) VALUES (:ipdev, :dname)");
$stmt->bindValue(":ipdev", $ip, SQLITE3_TEXT);
$stmt->bindValue(":dname", $name, SQLITE3_TEXT);
$stmt->execute();

$db->close();

header("Location: /dashboard.php");
?>
