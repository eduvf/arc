<?php

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

?>
