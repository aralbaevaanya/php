<?php require_once 'logic/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'parts/head.php'; ?>
</head>
<body>
<?php include_once 'parts/header.php'; ?>

<?php if (isset($_SESSION['user_login'])): ?>

    <session id='last_tmp_sec'>
        <?php include_once 'logic/print_last_tmp.php'; ?>
    </session>

    <form action="logic/createComp.php" name="creteComp" method="post">
        <label>Дата</label>
        <input type="date" name='date' value=time() required/>
        <br>
        <label>Шаблон</label>
        <select name="template">
            <?php include_once 'logic/get_templates.php'; ?>
        </select>
        <button type="submit"
                name="button">Поличить статистику</button>

    </form>


    <a href="logic/logout.php">Выход из аккаунта</a>
    <br>
<?php else: ?>
    <?php include_once 'parts/not_auth.php'; ?>
<?php endif; ?>

</body>
</html>