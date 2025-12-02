<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

$display = $_SESSION["xpra_display"] ?? null;

if (!$display) {
    $_SESSION["error_message"] = "No hi ha cap sessió XPRA activa.";
    header("Location: /dashboard.php");
    exit;
}

exec("xpra stop :$display", $output, $ret);

if ($ret === 0) {
    unset($_SESSION["xpra_display"]);
    $_SESSION["success_message"] = "Sessió XPRA aturada correctament.";
} else {
    $_SESSION["error_message"] = "No s'ha pogut aturar la sessió XPRA.";
}

header("Location: /dashboard.php");
exit;
?>
