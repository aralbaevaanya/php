<?php
require_once 'logic/db.php';

if(isset($_SESSION['user_login'])){
    echo 'admin';
    echo '<br>';
    echo '<a href="logic/logout.php">Выход из аккаунта</a>';
}else{
    die('Нет доступа к странице');
}
