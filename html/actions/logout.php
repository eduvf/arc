<?php
session_start();

// Anul·lem les variables de sessió pertinents
$_SESSION["user"]     = null;
$_SESSION["is_admin"] = null;

header("Location: /");
?>
