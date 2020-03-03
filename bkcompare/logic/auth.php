<?php
require_once 'db.php';
require_once '../parts/head.php';

$login =trim($_POST['login']);
$pwd = trim($_POST['pwd']);

if(!empty($login) && !empty($pwd)){
    $sql = 'SELECT * FROM users WHERE login = :login';
    $params = [':login' => $login];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_OBJ);
    if($user){
        if(password_verify($pwd, $user->password)) {
            $_SESSION['user_login'] = $user->login;
            $_SESSION['user_id'] = $user->id;
            echo 'Успешная авторизация';
            header('Location: ../signin.php');
        }else{
            echo 'Неверный пароль';
        }
    }else{
        echo "Неверный логин $login" ;
    }
}else {
    echo "Пожалуйста заполните все поля";
}
?>
<br>
<a href="../index.php">Главная страница</a>
<br>
<a href="../signup.php">Страница регистрации</a>