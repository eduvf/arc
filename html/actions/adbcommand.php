<?php

session_start();

$cmd = $_POST["cmd"];
$arg = $_POST["arg"];

$_SESSION["cmd output"] = [];

if ($cmd == "shell") {
    exec("ls", $_SESSION["cmd output"]);
}

header("Location: /device.php");

?>