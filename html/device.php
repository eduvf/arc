<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC | Control de dispositiu</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>

<?php
require('actions/_lib.php');
session_start();

_login_check_is_user_admin_and_device();

$user   = $_SESSION["user"];
$dev_name = $_SESSION["dev_name"];

// Recull el nom del dispositiu
$db = new SQLite3("../db/arc.db");

$query = $db->query("SELECT dev_ip FROM devices WHERE dev_name = '$dev_name'");
$dev_ip = $query->fetchArray()[0];

$_SESSION["dev_ip"] = $dev_ip;

$db->close();
?>

<body>
    <header class="container">
        <nav>
            <span><b>ARC</b> | <?= $dev_name ?> (<?= $dev_ip ?>) | <a href="/dashboard.php">Torna al tauler de control</a></span>
            <span>Usuari: <?= $user ?> (<a href="/actions/logout.php">Tanca la sessió</a>)</span>
        </nav>
        <hr>
    </header>

    <div style="text-align: center">
        <?php
        // Mostra el QR del certificat
        if ($_SESSION["dev_qr_cert"]) {
            echo $_SESSION["dev_qr_cert"];
            $_SESSION["dev_qr_cert"] = null;
        }
        ?>
    </div>

    <main class="container">
        <div class="grid">
            <form action="/actions/device_remote.php">
                <input type="submit" value="Control de pantalla ↗" />
            </form>
            <form action="/actions/device_certificate.php">
                <input type="submit" class="outline" value="Mostra el certificat" />
            </form>
            <form action="/actions/device_disconnect.php">
                <input type="submit" class="secondary" value="Desconnecta" />
            </form>
        </div>

        <article>
            <p><?php
                $res = exec("timeout 5 adb connect $dev_ip:5555");
                if (empty($res)) {
                    $res = "<span style='color:red;'>No s'ha pogut establir la connexió.</span>";
                } else {
                    $res = "<span style='color:green;'>$res</span>";
                }
                echo $res;
            ?></p>
            <form action="/actions/device_command.php" method="post">
                <fieldset role="group">
                    <select name="cmd_type" style="width: fit-content;">
                        <option value="shell">adb shell</option>
                        <option value="push">adb push</option>
                        <option value="pull">adb pull</option>
                        <option value="install">adb install</option>
                        <option value="uninstall">adb uninstall</option>
                    </select>
                    <input type="text" name="cmd_text_arg" placeholder="Comanda o ruta...">
                    <input type="submit" value="Executa">
                </fieldset>
                <input type="file" name="cmd_file_arg">
            </form>
            <hr>
            <p><b>Sortida:</b></p>
            <pre><?= implode("\n", $_SESSION["cmd_out"]) ?></pre>
        </article>
    </main>
</body>

</html>
