<?php

define("ARCDB", "/var/www/db/arc.db");
define("FILES", "/var/www/files/");
define("DOWNLOADS", "/var/www/html/download/");
define("SCRCPY", "/var/www/scrcpy/scrcpy");
define("LOGFILE", "/var/log/arc.log");

// Comprova que hem iniciat sessió
function _login_check_is_user() {
    if (empty($_SESSION["user"])) {
        header("Location: /");
    }
}

// Comprova que hem iniciat sessió i que som superusuaris
function _login_check_is_user_admin() {
    if (empty($_SESSION["user"]) || !$_SESSION["is_admin"]) {
        header("Location: /");
    }
}

// Comprova que hem iniciat sessió, que som superusuaris i que estem administrant un dispositiu
function _login_check_is_user_admin_and_device() {
    if (empty($_SESSION["user"]) || !$_SESSION["is_admin"] || empty($_SESSION["dev_name"])) {
        header("Location: /");
    }
}

function encrypt_data($data) {
    $key = 'aSecretKey';  // Clau secreta per encriptar les dades
    $method = 'AES-256-CBC';

    // Generem un IV (vector d'inicialització) per seguretat
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

    // Encriptem les dades
    $encrypted_data = openssl_encrypt($data, $method, $key, 0, $iv);
    
    // Retornem les dades encriptades i l'IV (necessari per a desxifrar)
    return base64_encode($encrypted_data . '::' . $iv);
}



function decrypt_data($encrypted_data) {
    $key = 'aSecretKey';  // Clau secreta per desxifrar (ha de ser la mateixa utilitzada per a l'encriptació)
    $method = 'AES-256-CBC';

    // Dividim les dades encriptades per obtenir el vector d'inicialització
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted_data), 2);

    // Desxifrem les dades
    return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
}

// Funció per generar un codi QR des d'un text (el certificat)
function generate_qr($data) {
    $safe_data = escapeshellarg($data); // Protegir els caràcters especials
    // Cridar a l'eina qrencode per generar el QR, convertir-lo a base64
    $cmd = "echo $safe_data | qrencode -o - | base64 --wrap=0";
    return exec($cmd);
}

function _log($msg) {
    $time = date("Y-m-d H:i:s");
    exec("echo ".escapeshellarg("<code>$time</code>  $msg")." >> ".LOGFILE);
}

function _log_list() {
    // Per defecte només es mostren els últims 100 logs
    $logs = [];
    exec("tail -n 100 ".LOGFILE, $logs);
    return $logs;
}

function _device_list() {
    $db = new SQLite3(ARCDB);

    $devices = [];
    $query = $db->query("SELECT * FROM devices");
    while ($device = $query->fetchArray()) {
        $devices[] = $device;
    }

    $db->close();
    return $devices;
}

// function _random_name() {
//     $chars = "abcdefghijklmnopqrstuvwxyz0123456789_";
//     $randname = "";

//     for ($i = 0; $i < 20; $i++) {
//         $randname .= $chars[rand(0, strlen($chars) - 1)];
//     }
//     return $randname;
// }

?>
