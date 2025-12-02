<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

$dev_name = $_SESSION["dev_name"];
$dev_ip   = $_SESSION["dev_ip"];

// Escapar paràmetres per evitar injeccions
$safe_name = escapeshellarg($dev_name);
$safe_ip   = escapeshellarg($dev_ip);

// Comanda a executar
$cmd = "sudo /bin/NewARC $safe_name $safe_ip";

// Captura el resultat de l'execució de l'script (la sortida de "cat")
exec($cmd, $output, $retval);

// Si l'execució ha fallat, mostrem l'error
if ($retval !== 0) {
    $_SESSION["dev_error"] = "Error generant certificat: <pre>" . implode("\n", $output) . "</pre>";
    header("Location: /device.php");
    exit();
}

// Unifiquem la sortida (el contingut generat pel "cat") en una sola cadena
$conf_content = implode("\n", $output);

// Emmagatzemem el contingut a la sessió (per mostrar-lo després)
$_SESSION["dev_conf"] = $conf_content;

// Generem el codi QR a partir del contingut
$qr_image = generate_qr($conf_content);  // Funció per generar el codi QR

// Emmagatzemem el QR a la sessió per mostrar-lo a la pàgina
$_SESSION["dev_qr_cert"] = "<img src='data:image/png;base64," . $qr_image . "' />";

header("Location: /device.php");
exit();
?>
