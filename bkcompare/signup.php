<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'parts/head.php'; ?>
</head>
<form action="logic/reg.php" method="post">
    <input type="text" name="login" placeholder='Login' required/>
    <br>
    <input type="password" name="pwd" placeholder="Password" required/>
    <br>
    <button type="submit"
            name="button">Зарегистрироваться
    </button>
</form>

<a href="signin.php">Страница авторизации</a>