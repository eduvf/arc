<?php
session_start();

if (empty($_SESSION["user"]) || empty($_SESSION["deviceip"])) {
    header("Location: /device.php");
    exit();
}

// Genera un codi QR a partir del certificat
// La imatge es genera com a un PNG, que passem a base64
// per incrustar-lo directament al "src" de l'<img>, sense generar cap fitxer

$qr_data = $_SESSION["deviceip"];
$qr_header = "data:image/png;base64,";
$qr_image = exec("qrencode '$qr_data' -o - | base64 --wrap=0");
$qr = $qr_header . $qr_image;

$_SESSION["deviceqr"] = "<img src='$qr'>";
header("Location: /device.php");
?>
