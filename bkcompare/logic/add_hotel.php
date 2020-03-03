<?php
require_once 'db.php';
require_once '../parts/head.php';

$titel = htmlentities(trim($_POST['title']));
$host = htmlentities(trim($_POST['host']));

$sql_check = 'SELECT * FROM hotels WHERE
    host = :host';
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([':host' => $host]);
if ($hotel = $stmt_check->fetch(PDO::FETCH_OBJ)) {
    die("По нашим данным этот отель называется $hotel->name");
}


$sql = 'INSERT INTO hotels(name, host) VALUES(:name , :host)';
$params = [':name' => $titel, ':host' => $host];

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo "Отель  $titel успешно добавлен";