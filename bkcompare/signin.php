<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'parts/head.php'; ?>
</head>
<form action="logic/auth.php" method="post">
    <input type="text" name="login" required/>
    <br>
    <input type="password" name="pwd" required/>
    <br>
    <button type="submit"
            name="button">Авторизоваться
    </button>
</form>
<a href="signup.php">Страница регистрации</a>