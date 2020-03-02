<?php
require_once 'db.php';

$sql = 'SELECT id, name FROM templates WHERE user_id = :user_id
ORDER BY id DESC LIMIT 1';
$params = [':user_id' => $_SESSION['user_id']];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tmp = $stmt->fetch(PDO::FETCH_OBJ);

?>
<?php if (!empty($tmp)):
    $sql = "SELECT hotels.name FROM templates_hotels
INNER JOIN hotels ON templates_hotels.template_id = :tmpId AND
templates_hotels.hotel_id = hotels.id";
    $params = [':tmpId' => $tmp->id];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    ?>
    <h3 class="templete_name"> <?php echo $tmp->name; ?> </h3>
    <br>
    <?php while ($hotel = $stmt->fetch(PDO::FETCH_OBJ)): ?>
    <h4 class='hotel_name'><?php echo $hotel->name; ?></h4>
    <hr>
<?php endwhile; ?>
<?php else: ?>
    <h3 class="templete_name"> У вас еще нет шаблонов</h3>
<?php endif; ?>
