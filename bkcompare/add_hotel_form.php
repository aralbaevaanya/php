<?php require_once 'logic/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'parts/head.php'; ?>
</head>
<body>
<?php include_once 'parts/header.php'; ?>

<?php if (isset($_SESSION['user_login'])): ?>

    <form action="logic/add_hotel.php" method="post">
        <input type="text" name="title" placeholder="Название отеля на hotels.com" required/>
        <input type="text" name="host" placeholder="часть url между hotels.com/ и /?" required/>
        <button type="submit"
                name="button">Добавить
        </button>
    </form>

    <a href="logic/logout.php">Выход из аккаунта</a>
    <br>
<?php else: ?>
    <?php include_once 'parts/not_auth.php'; ?>
<?php endif; ?>

</body>
</html>
