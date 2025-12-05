<?php
require('_lib.php');
session_start();

$user = $_SESSION["user"];
_log("L'usuari <code>$user</code> ha tancat la sessió.");

// Anul·lem les variables de sessió pertinents
$_SESSION["user"]     = null;
$_SESSION["is_admin"] = null;

header("Location: /");
?>
