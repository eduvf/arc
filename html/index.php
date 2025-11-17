<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC | Inici de sessió</title>
    <link rel="stylesheet" href="/assets/pico.lime.min.css">
</head>

<style>
    html {
        background-image: url(/assets/bg.jpg);
        background-position: center;
        background-size: cover;
    }

    header {
        background: var(--pico-background-color);
    }

    main {
        max-width: 400px;
        margin: 0 auto;
    }
</style>

<body>
    <header>
        <div class="container">
            <b>ARC</b> | Inici de sessió
        </div>
    </header>
    <main>
        <article>
            <form action="/actions/login.php" method="post">
                <label>
                    Usuari
                    <input name="user" />
                </label>
                <label>
                    Contrasenya
                    <input type="password" name="pass" />
                </label>

                <?php
                session_start();
                if ($_SESSION["failed_login"]) {
                    $_SESSION["failed_login"] = false;
                    echo "<span style='color: red;'>Usuari o contrasenya incorrectes.</span>";
                }
                ?>

                <input type="submit" value="Inicia sessió" />
            </form>
        </article>
    </main>
</body>

</html>
