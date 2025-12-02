<?php
require('actions/_lib.php');
session_start();
_login_check_is_user_admin_and_device();

$display = $_SESSION["xpra_display"] ?? null;

if (!$display) {
    $_SESSION["error_message"] = "No hi ha cap sessió XPRA activa.";
    header("Location: /dashboard.php");
    exit;
}

$port = 10000 + $display;
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>ARC | Control de pantalla</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>
<body>

<header class="container">
    <nav>
        <span><b>ARC</b> | Control de pantalla</span>
        <span><a href="/dashboard.php">Torna</a></span>
    </nav>
</header>

<main class="container">
    <h2>Control remot del dispositiu</h2>
    <p>
        Connexió establerta via XPRA.  
        <br>
        Si no es carrega automàticament, fes clic aquí:
    </p>

    <a class="contrast" href="http://<?= $_SERVER['SERVER_ADDR'] ?>:<?= $port ?>" target="_blank">
        Obrir finestra XPRA ↗
    </a>

    <hr>

    <form action="/actions/stop_remote.php" method="post">
        <button type="submit" class="secondary">Atura la sessió</button>
    </form>
</main>

</body>
</html>
