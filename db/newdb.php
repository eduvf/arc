<?php
// Aquest script s'ha d'executar com a CLI
// Inicialitza la base de dades

$db = new SQLite3("arc.db");

$db->exec("CREATE TABLE IF NOT EXISTS users (
user TEXT PRIMARY KEY,
pass TEXT NOT NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS devices (
ip TEXT PRIMARY KEY,
dname TEXT NOT NULL
)");

$db->close();
?>
