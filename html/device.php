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

// Recupera el nom del dispositiu
$db = new SQLite3("../db/arc.db");

$query = $db->query("SELECT dev_ip, cert_data FROM devices WHERE dev_name = '$dev_name'");
$device = $query->fetchArray();
$dev_ip = $device['dev_ip'];
$cert_data_encrypted = $device['cert_data'];  // Certificat encriptat

$_SESSION["dev_ip"] = $dev_ip;

// Desxifrem les dades del certificat (si existeixen)
if ($cert_data_encrypted) {
    // Desxifrem el certificat
    $cert_data = decrypt_data($cert_data_encrypted);

    // Generem el codi QR amb el certificat
    $qr_image = generate_qr($cert_data);

    // Guardem la imatge en el formulari de sessió per mostrar al frontend
    $_SESSION["dev_qr_cert"] = "<img src='data:image/png;base64,$qr_image' alt='Certificat QR'>";
} else {
    $_SESSION["dev_qr_cert"] = null;
}

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
        // Mostra el QR del certificat si existeix
        if ($_SESSION["dev_qr_cert"]) {
            echo $_SESSION["dev_qr_cert"];
            $_SESSION["dev_qr_cert"] = null;
        } else {
            echo "<p style='color: red;'>Certificat no disponible per a aquest dispositiu.</p>";
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
			<option value="list_install">List install packages</option>

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
