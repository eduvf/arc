<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC | Dispositiu</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>

<?php
session_start();

if (empty($_SESSION["user"]) || empty($_SESSION["deviceip"])) {
    header("Location: /dashboard.php");
    exit();
}

$user = $_SESSION["user"];
?>

<body>
    <header class="container">
        <nav>
            <span><b>ARC</b> | Dispositiu</span>
            <span>Usuari: <?= $user ?> (<a href="/dashboard.php">Torna al Tauler de control</a>)</span>
        </nav>
        <hr>
    </header>

    <div style="text-align: center">
        <?php
        if ($_SESSION["deviceqr"]) {
            echo $_SESSION["deviceqr"];
            $_SESSION["deviceqr"] = null;
        }
        ?>
    </div>

    <main class="container-fluid">
        <div class="grid">
            <form action="">
                <input type="submit" value="Control de pantalla ↗" />
            </form>
            <form action="">
                <input type="submit" class="" value="Connexió ADB" />
            </form>
            <form action="/actions/devicecert.php">
                <input type="submit" class="outline" value="Mostra el certificat" />
            </form>
        </div>

        <article>
            <form action="/actions/adbcommand.php" method="post">
                <fieldset role="group">
                    <select name="cmd" style="width: fit-content;">
                        <option value="shell">adb shell</option>
                        <!-- <option value="push">adb push</option> -->
                        <!-- <option value="pull">adb pull</option> -->
                        <!-- <option value="install">adb install</option> -->
                        <option value="uninstall">adb uninstall</option>
                    </select>
                    <input type="text" name="arg" placeholder="Comanda o ruta...">
                    <input type="submit" value="Executa">
                </fieldset>
                <input type="file" name="file">
            </form>
            <hr>
            <p><b>Sortida:</b></p>
            <p><?= implode("<br>", $_SESSION["cmd output"]) ?></p>
        </article>
        <!-- <hr> -->
        <!-- <table class="striped">
            <thead>
                <tr>
                    <th>Historial</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>4,880</td>
                </tr>
                <tr>
                    <td>225</td>
                </tr>
                <tr>
                    <td>365</td>
                </tr>
                <tr>
                    <td>687</td>
                </tr>
            </tbody>
        </table> -->
    </main>
</body>

</html>