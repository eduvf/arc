<?php
session_start();

if (empty($_SESSION["user"])) {
    header("Location: /dashboard.php");
    exit();
}

$_SESSION["deviceip"] = $_POST["dispo"];
header("Location: /device.php");
?>
