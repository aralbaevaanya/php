<header>
    <nav>
        <?php if(isset($_SESSION['user_login'])) : ?>
            <a href="index.php">Главная</a>
            <a href="templates.php">Шаблоны</a>
            <a href="add_hotel_form.php">Добавить отель</a>
        <?php else: ?>
            <a href="signin.php">Авторизоваться</a>
            <a href="signup.php">Зарегистрироваться</a>
        <?php endif; ?>
    </nav>
</header>