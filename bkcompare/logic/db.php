<?php
$driver = 'mysql';
$host = 'localhost';
$db_name = 'aralbaeva_wp1';
$db_user = 'aralbaeva_wp1';
$db_pass = 'i*hjC6F7';
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO("$driver:host=$host;dbname=$db_name;charset=$charset",
        $db_user, $db_pass, $options);

    session_start();
} catch (PDOException $e) {
    die("Не могу подключиться к базе данных, $e");
}

