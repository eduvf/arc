<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC | Tauler de control</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>

<?php
session_start();

if (empty($_SESSION["user"])) {
    header("Location: /");
    exit();
}

$user = $_SESSION["user"];

// Recull tots els dispositius registrats a la BD
$db = new SQLite3("../db/arc.db");

$devices = [];
$consulta = $db->query("SELECT * FROM devices");
while ($device = $consulta->fetchArray()) {
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
        <form action="/actions/deviceconnect.php" method="post">
            <fieldset class="grid">
                <select name="dispo" required>
                    <option selected disabled value="">Selecciona un dispositiu...</option>
                    <?php
                        foreach ($devices as $device) {
                            $ip = $device['ip'];
                            $name = $device['dname'];
                            echo "<option value='$ip'>$name ($ip)</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Connecta" />
            </fieldset>
        </form>

        <form action="/actions/adddevice.php" method="post">
            <fieldset class="grid">
                <input type="text" name="namedev" required placeholder="Nom del nou dispositiu...">
                <input type="text" name="ipdev" required placeholder="IP del dispositiu...">
                <input type="submit" class="secondary" value="Afegeix" />
            </fieldset>
            <?= $_SESSION["add device error"] ?>
            <?= $_SESSION["add device error"] = null ?>
        </form>

        <hr>

        Exemple:
        <div class="overflow-auto">
            <table class="striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuari</th>
                        <th>IP</th>
                        <th>Data i hora</th>
                        <th>Registre</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>1</th>
                        <td>Joan</td>
                        <td>x.x.x.x</td>
                        <td>2025-11-1 00:00</td>
                        <td>Ha iniciat sessió al dispositiu A.</td>
                    </tr>
                    <tr>
                        <th>2</th>
                        <td>Marta</td>
                        <td>x.x.x.x</td>
                        <td>2025-11-1 00:00</td>
                        <td>Ha registrat el nou dispositiu B.</td>
                    </tr>
                    <tr>
                        <th>3</th>
                        <td>Joan</td>
                        <td>x.x.x.x</td>
                        <td>2025-11-1 00:00</td>
                        <td>Ha executat una comanda al dispositiu A (...).</td>
                    </tr>
                    <tr>
                        <th>4</th>
                        <td>Marta</td>
                        <td>x.x.x.x</td>
                        <td>2025-11-1 00:00</td>
                        <td>S'ha connectat al dispositiu B.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>