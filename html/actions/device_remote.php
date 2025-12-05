<?php
require('_lib.php');
session_start();

_login_check_is_user_admin_and_device();

function find_free_port()
{
    $sock = socket_create_listen(0); 
    socket_getsockname($sock, $addr, $port); 
    socket_close($sock);

    return $port;
}

function log_scpy($cmd)
{
    $user = $_SESSION["user"];
    $name = $_SESSION["dev_name"];
    _log("L'usuari <code>$user</code> ha iniciat control de pantalla al dispositiu <code>$name</code>: <code>$cmd</code>");
}

$port = find_free_port();
$ip = $_SESSION["dev_ip"];

$scpy = SCRCPY . " -s $ip:5555 -f";
$cmd = "xpra start --bind-tcp=:$port --start-new-commands=no --exit-with-children=yes --start-child=\"$scpy\"";

exec($cmd);
log_scpy($cmd);

sleep(2);
header("Location: http://".$_SERVER['SERVER_ADDR'].":$port");
?>
