<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

$ip = $_SESSION["dev_ip"];
exec("adb disconnect $ip:5555");

$user = $_SESSION["user"];
$name = $_SESSION["dev_name"];
_log("L'usuari <code>$user</code> s'ha desconnectat al dispositiu <code>$name</code>.");

$_SESSION["dev_name"] = null;
$_SESSION["dev_ip"]   = null;

header("Location: /dashboard.php");
?>
