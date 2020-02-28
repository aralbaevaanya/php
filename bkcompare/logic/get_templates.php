<?php
require_once 'db.php';

$sql = 'SELECT id, name FROM templates WHERE user_id = :user_id';
$params = [':user_id' => $_SESSION['user_id']];

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

while ($template = $stmt->fetch(PDO::FETCH_OBJ)): ?>
    <option value=<?php echo $template->id; ?>>
        <?php echo $template->name; ?>
    </option>
<?php endwhile; ?>
