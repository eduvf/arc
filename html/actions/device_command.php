<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

// Ens assegurem de que la variable de sessió de sortida és buida
$_SESSION["cmd_out"] = null;

function adb_shell()
{
    // L'opció shell permet executar una comanda qualsevol al dispositiu
    // Per evitar injeccions, fem hem d'escapar l'entrada de l'usuari amb escapeshellarg()
    $ip = $_SESSION["dev_ip"]; 
    $cmd = "adb -s $ip:5555 shell ".escapeshellarg($_POST["cmd_text_arg"]);

    exec($cmd, $_SESSION["cmd_out"]);
}

switch ($_POST["cmd_type"]) {
    case 'shell':
        adb_shell();
        break;

    case 'push':
        break;
    
    case 'pull':
        break;
    
    case 'install':
        break;
    
    case 'uninstall':
        break;
    
    default:
        break;
}

header("Location: /device.php");
?>
