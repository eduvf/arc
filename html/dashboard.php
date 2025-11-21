<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC | Tauler de control</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>

<?php
require('actions/_lib.php');
session_start();

$user = $_SESSION["user"];
$is_admin = $_SESSION["is_admin"];

_login_check_is_user();
// Recull tots els dispositius registrats a la BD
$db = new SQLite3("../db/arc.db");
$devices = [];

// Desem tots els dispositius registrats a la variable 'devices'
$query = $db->query("SELECT * FROM devices");
while ($device = $query->fetchArray()) {
    $devices[] = $device;
}

$db->close();
?>

<body>
    <header class="container">
        <nav>
            <span><b>ARC</b> | Tauler de control</span>
            <span>Usuari: <?= $user ?> (<a href="/actions/logout.php">Tanca la sessió</a>)</span>
        </nav>
        <hr>
    </header>

    <main class="container">
        <?php if ($is_admin): ?>

        <form action="/actions/device_connect.php" method="post">
            <fieldset class="grid">
                <select name="dev_name" required>
                    <option selected disabled value="">Selecciona un dispositiu...</option>
                    <?php
                        foreach ($devices as $device) {
                            $ip   = $device['dev_ip'];
                            $name = $device['dev_name'];
                            echo "<option value='$name'>$name ($ip)</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Connecta" />
            </fieldset>
        </form>

        <form action="/actions/device_add.php" method="post">
            <fieldset class="grid">
                <input type="text" name="dev_name" required placeholder="Nom del dispositiu...">
                <input type="text" name="dev_ip" required placeholder="IP del dispositiu...">
                <input type="submit" class="secondary" value="Afegeix/Modifica" />
            </fieldset>
            <?php
                echo "<span style='color:red;'>".$_SESSION["add_device_error"]."</span>";
                $_SESSION["add_device_error"] = null;
            ?>
        </form>

        <hr>
        <?php endif; ?>

        <h4>Dispositius registrats</h4>
        <div class="overflow-auto">
            <table class="striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom del dispositiu</th>
                        <th>IP</th>
                        <th>Certificat (QR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($devices as $device) {
                        $device_name = $device['dev_name'];
                        $device_ip = $device['dev_ip'];
                        $cert_data_encrypted = $device['cert_data'];

                        // Comprovem si hi ha un certificat per aquest dispositiu
                        if ($cert_data_encrypted) {
                            // Desxifrem les dades
                            $cert_data = decrypt_data($cert_data_encrypted);

                            // Generem el codi QR
                            $qr_image = generate_qr($cert_data);

                            // Mostrem el dispositiu amb el codi QR
                            echo "<tr>
                                    <td>#</td>
                                    <td>$device_name</td>
                                    <td>$device_ip</td>
                                    <td><img src='data:image/png;base64,$qr_image' alt='QR del dispositiu'></td>
                                  </tr>";
                        } else {
                            // Si no hi ha certificat, només mostrem el dispositiu sense QR
                            echo "<tr>
                                    <td>#</td>
                                    <td>$device_name</td>
                                    <td>$device_ip</td>
                                    <td>No disponible</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>
</body>

</html>
