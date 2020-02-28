<?php require_once 'logic/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'parts/head.php'; ?>
</head>
<body>
<?php include_once 'parts/header.php'; ?>


<?php if (isset($_SESSION['user_login'])): ?>

    <section id='templates-sec'>
        <?php include_once 'logic/print_templates.php'; ?>
    </section>

    <form action="logic/add_tmp.php" method="post">
        <label>Название шаблона</label>
        <input type="text" name="title" required/>
        <label>Ваш отель:</label>
        <input type="text" name="myHotel" required/>
        <br>
        <label>Конкурентные отели:</label>
        <div id="overHotels">
            <input type="text" name="hotel[]" required/>
            <br>
        </div>
        <br>
        <button type="submit"
                name="button">Создать шаблон</button>
    </form>


    <button onclick="bar()">Добавить новое поле</button>



    <script>
        function bar() {
            var elem = document.createElement("input");
            var br = document.createElement('br');
            elem.type = 'text';
            elem.name = 'hotel[]';
            elem.required = true;
            document.getElementById("overHotels").appendChild(elem);
            document.getElementById("overHotels").appendChild(br);
        }
    </script>


<?php else: ?>
    <?php include_once 'parts/not_auth.php'; ?>
<?php endif; ?>

</body>
</html>