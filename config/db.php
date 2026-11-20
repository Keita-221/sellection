<?php
// Connexion à la base de données (identifiants lus dans config/config.php)

$cfg = require __DIR__ . '/config.php';

define('ADMIN_EMAIL', $cfg['admin_email']);

mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = new mysqli($cfg['host'], $cfg['user'], $cfg['password'], $cfg['database']);

if (!$mysqli->connect_errno) {
    $mysqli->set_charset('utf8mb4');
}