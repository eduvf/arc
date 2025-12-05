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

// Cridem l'script per generar el certificat
$cmd = "sudo /bin/NewARC " . escapeshellarg($name) . " " . escapeshellarg($ip);
exec($cmd, $output, $retval);

// Comprovem que l'script ha funcionat correctament
if ($retval !== 0) {
    $_SESSION["add_device_error"] = "Error generant el certificat: <pre>" . implode("\n", $output) . "</pre>";
    header("Location: /dashboard.php");
    exit();
}

// El resultat de l'script (dades generades pel certificat)
$conf_content = implode("\n", $output);

// Encriptar les dades generades per al dispositiu
$encrypted_data = encrypt_data($conf_content);

// Fem la inserció a la BD (afegim també les dades encriptades del certificat)
$db = new SQLite3("../../db/arc.db");
$stmt = $db->prepare("INSERT OR REPLACE INTO devices (dev_ip, dev_name, cert_data) VALUES (:d_ip, :d_name, :cert_data)");
$stmt->bindValue(":d_ip", $ip);
$stmt->bindValue(":d_name", $name);
$stmt->bindValue(":cert_data", $encrypted_data);

// Executem la comanda i comprovem que hagi funcionat
if ($stmt->execute() === false) {
    $_SESSION["add_device_error"] = "ERROR: No s'ha pogut afegir el dispositiu. " . $db->lastErrorMsg();
    header("Location: /dashboard.php");
    exit();
}

$db->close();

$user = $_SESSION["user"];
_log("L'usuari <code>$user</code> ha afegit el dispositiu <code>$name</code> (<code>$ip</code>).");

header("Location: /dashboard.php");
exit();
?>
