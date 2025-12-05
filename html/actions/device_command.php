<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

// Ens assegurem de que la variable de sessió de sortida és buida
$_SESSION["cmd_out"] = null;

function log_cmd($cmd)
{
    $user = $_SESSION["user"];
    $name = $_SESSION["dev_name"];
    _log("L'usuari <code>$user</code> ha executat <code>$cmd</code> al dispositiu <code>$name</code>.");
}

function upload_file($ext) {
    // L'$ext és l'extensió del fitxer, que ha de començar amb un punt '.'
    // Si l'extensió no importa, es pot fer servir una cadena buida ""
    $file = $_FILES["cmd_file_arg"];
    $filename = $file["name"];
    $temppath = $file["tmp_name"];

    /*
    // Genera un nom de fitxer aleatori
    $fname = _random_name();
    while (file_exists(FILES.$fname.$ext)) {
        $fname = _random_name();
    }
    */

    // Si hi ha cap error en pujar el fitxer, ho indica a la sortida i torna a /device.php
    if (move_uploaded_file($temppath, FILES.$filename)) {
        return FILES.$filename;
    } else {
        $_SESSION["cmd_out"] = ["Hi ha hagut un error."];
        header("Location: /device.php");
    }
}

function adb_shell()
{
    $ip = $_SESSION["dev_ip"];
    // L'opció shell permet executar una comanda qualsevol al dispositiu
    // Per evitar injeccions, fem hem d'escapar l'entrada de l'usuari amb escapeshellarg()
    $cmd = "adb -s $ip:5555 shell ".escapeshellarg($_POST["cmd_text_arg"]);

    log_cmd($cmd);

    exec($cmd, $_SESSION["cmd_out"]);
}

function adb_push()
{
    $ip = $_SESSION["dev_ip"];
    // L'opció push permet pujar un fitxer a la plataforma per baixar-lo al dispositiu
    // El fitxer es desa al servidor amb un nom aleatori, però es baixa al dispositiu
    // al camí indicat per l'usuari
    $srvfile = upload_file("");

    $cmd = "adb -s $ip:5555 push $srvfile ".escapeshellarg($_POST["cmd_text_arg"]);
    log_cmd($cmd);

    exec($cmd, $_SESSION["cmd_out"]);
}

function adb_pull()
{
    $ip = $_SESSION["dev_ip"];
    // L'opció pull permet baixar un fitxer del dispositiu
    /*
    $fname = _random_name();
    while (file_exists(FILES.$fname)) {
        $fname = _random_name();
    }
    */

    $file = $_POST["cmd_text_arg"];
    $cmd = "adb -s $ip:5555 pull ".escapeshellarg($file)." ".FILES.basename($file);
    log_cmd($cmd);

    exec($cmd, $_SESSION["cmd_out"]);

    rename(FILES.basename($file), DOWNLOADS.basename($file));

    // Assigna una adreça per a baixar el fitxer
    $_SESSION["cmd_download"] = "download/".basename($file);
}

function adb_install()
{
    $ip = $_SESSION["dev_ip"];
    // L'opció install permet pujar un fitxer apk i instal·lar-lo al dispositiu
    $srvfile = upload_file(".apk");

    $cmd = "adb -s $ip:5555 install $srvfile";
    log_cmd($cmd);

    exec($cmd, $_SESSION["cmd_out"]);
}

function adb_uninstall()
{
    $ip = $_SESSION["dev_ip"];
    // L'opció uninstall permet desinstal·lar una aplicació del dispositiu
    $cmd = "adb -s $ip:5555 uninstall ".escapeshellarg($_POST["cmd_text_arg"]);

    log_cmd($cmd);

    exec($cmd, $_SESSION["cmd_out"]);
}

switch ($_POST["cmd_type"]) {
    case 'shell':
        adb_shell();
        break;

    case 'push':
        adb_push();
        break;
    
    case 'pull':
        adb_pull();
        break;
    
    case 'install':
        adb_install();
        break;
    
    case 'uninstall':
        adb_uninstall();
        break;
    
    default:
        break;
}

header("Location: /device.php");
?>
