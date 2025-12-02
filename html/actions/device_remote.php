<?php
require('_lib.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug de sessió
file_put_contents('/tmp/debug_remote.log', print_r($_SESSION, true), FILE_APPEND);
file_put_contents('/tmp/debug_remote.log', "\n----\n", FILE_APPEND);

// Només admins
_login_check_is_user_admin_and_device();

if ($_SESSION["role"] !== "admin") {
    header("Location: /dashboard.php");
    exit;
}

$dev_ip = $_SESSION["dev_ip"] ?? null;

if (!$dev_ip || !filter_var($dev_ip, FILTER_VALIDATE_IP)) {
    $_SESSION["error_message"] = "IP del dispositiu no vàlida.";
    header("Location: /dashboard.php");
    exit;
}

// Generem display lliure
$display = rand(100, 200);

// Preparem comandament XPRA + SCRCPY
$cmd = sprintf(
    "xpra start :%d --start-child=\"scrcpy --tcpip=%s:5555\" --bind-tcp=0.0.0.0:%d --daemon=no",
    $display,
    $dev_ip,               // aquí JA no cal escapeshellarg
    10000 + $display
);

// Llança en background sense comprovar $ret
exec($cmd . " > /tmp/xpra_$display.log 2>&1 &");

// Assigna directament la sessió i redirigeix
$_SESSION["xpra_display"] = $display;
$_SESSION["success_message"] = "Sessió remota iniciada! Revisa /tmp/xpra_$display.log si hi ha errors.";
header("Location: /screen_control.php");
exit;
?>
