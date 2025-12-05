<?php
require('_lib.php');
session_start();

_login_check_is_user_admin();

$_SESSION["dev_name"] = $_POST["dev_name"];

$user = $_SESSION["user"];
$name = $_SESSION["dev_name"];
_log("L'usuari <code>$user</code> s'ha connectat al dispositiu <code>$name</code>.");

header("Location: /device.php");
?>
