<?php
require_once 'db.php';

$title = trim(($_POST['title']));
$myHotelName = trim($_POST['myHotel']);

$sql = 'SELECT EXISTS (SELECT id FROM templates WHERE user_id = :user_id AND name = :title)';
$params = [':user_id' => $_SESSION['user_id'], ':title' => $title];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
if ($stmt->fetchColumn()) {
    echo 'Шаблон с таким названием уже существует';
}else{


?>
<session>
    <?php
    $sql_check = 'SELECT host FROM hotels WHERE name = :name';
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':name' => $myHotelName]);
    $hotel = $stmt_check->fetch(PDO::FETCH_OBJ);

    if (empty($hotel)) {
        die( "Отель с именем $myHotelName не найден, вы можете создать его во вкладке \"Добавить отель\" </a>");
    }else{
        $myHotelHost = $hotel->host;
    }

    $sql = 'INSERT INTO templates(user_id, name, my_hotel_host) VALUES(:user_id, :title, :myHotel)';
    $params = [':user_id' => $_SESSION['user_id'], ':title' => $title, ':myHotel' => $myHotelHost];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $sql_find_id = 'SELECT id FROM templates WHERE name = :title AND user_id = :user_id';
    $params_find_id = [':title' => $title, ':user_id' => $_SESSION['user_id']];
    $stmt_find_id = $pdo->prepare($sql_find_id);
    $stmt_find_id->execute($params_find_id);

    $temlate_id = $stmt_find_id->fetch(PDO::FETCH_OBJ);
    $temlate_id = $temlate_id->id;

    if (isset($_POST['hotel'])) {
        foreach ($_POST['hotel'] as $key => $name) {
            $name = trim($name);
            if (!empty($name)) {
                $sql_check = 'SELECT id FROM hotels WHERE
                            name = :name';
                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->execute([':name' => $name]);
                $hotel = $stmt_check->fetch(PDO::FETCH_OBJ);

                if (empty($hotel)) {
                    die( "Отель с именем $name не найден, вы можете создать его во вкладке \"Добавить отель\" </a>");
                } else {
                    $hotel_id = $hotel->id;
                    $sql_add = 'INSERT INTO templates_hotels(template_id, hotel_id) VALUES (:template_id, :hotel_id)';
                    $params_add = [':template_id' => $temlate_id, ':hotel_id' => $hotel_id];
                    $stmt_add = $pdo->prepare($sql_add);
                    $stmt_add->execute($params_add);
                }
            }
        }
        echo 'Шаблон успешно создан';
    }
    }
    ?>
    <a href="../index.php">Вернуться на главную </a>
    <br>
     <a href="../templates.php">Создать еше шаблон</a>

</session>


