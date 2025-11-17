<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

$ip = $_SESSION["dev_ip"];
exec("adb disconnect $ip:5555");

$_SESSION["dev_name"] = null;
$_SESSION["dev_ip"]   = null;

header("Location: /dashboard.php");
?>
