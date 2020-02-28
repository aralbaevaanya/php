<?php
require_once 'db.php';


$sql = 'SELECT templates.name, templates.id, GROUP_CONCAT(hotels.name SEPARATOR "  ") AS hotels FROM templates
INNER JOIN templates_hotels
ON templates.id = templates_hotels.template_id AND templates.user_id = :user_id
INNER JOIN hotels
ON templates_hotels.hotel_id = hotels.id
GROUP BY templates.name, templates.id';
$params = [':user_id' => $_SESSION['user_id']];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    while ($template = $stmt->fetch(PDO::FETCH_OBJ)): ?>
<div class="template_container" id=<?php echo 'template_' . $template->id ?> >
    <h3 class="templates_name"><?php echo $template->name ?></h3>
    <br>
    <h4 class="hotels_name"><?php echo $template->hotels ?></h4>
    <br>
</div>
    <hr>
<?php endwhile; ?>


