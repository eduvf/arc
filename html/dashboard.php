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

// Desem tots els dispositiu registrats a la variable 'devices'
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

        <h4>Registre (exemple)</h4>
        <div class="overflow-auto">
            <table class="striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data i hora</th>
                        <th>Missatge</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>2025-11-1 00:00</td>
                        <td>S'ha iniciat sessió com a <code>ARC</code> des de <code>192.168.1.123</code>.</td>
                    </tr>
                    <tr>
                        <th>2</th>
                        <td>2025-11-1 00:02</td>
                        <td><code>ARC</code> ha afegit el dispositiu <code>test (192.168.1.10)</code>.</td>
                    </tr>
                    <tr>
                        <th>3</th>
                        <td>2025-11-1 00:04</td>
                        <td><code>ARC</code> ha executat <code>ls</code> a <code>test (192.168.1.10)</code>.</td>
                    </tr>
                    <tr>
                        <th>4</th>
                        <td>2025-11-1 00:08</td>
                        <td><code>ARC</code> ha iniciat el control de pantalla a <code>test (192.168.1.10)</code>.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
