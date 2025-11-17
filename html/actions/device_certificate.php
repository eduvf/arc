<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

// Genera un codi QR a partir del certificat
// La imatge es genera com a un PNG, que passem a base64
// per incrustar-lo directament al "src" de l'<img>, sense generar cap fitxer

$qr_data = "reemplaça això amb el certificat del dispositiu";

$qr_header = "data:image/png;base64,";
$qr_image = exec("qrencode '$qr_data' -o - | base64 --wrap=0");

$_SESSION["dev_qr_cert"] = "<img src='".$qr_header.$qr_image."'>";
header("Location: /device.php");
?>
