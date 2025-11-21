<?php
require('_lib.php');
session_start();

_login_check_is_user_admin();

$_SESSION["dev_name"] = $_POST["dev_name"];

header("Location: /device.php");
?>
