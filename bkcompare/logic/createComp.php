<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../parts/head.php'; ?>
    <style type="text/css">
        TABLE {
            width: 300px; /* Ширина таблицы */
            border: 1px solid black; /* Рамка вокруг таблицы */
            border-bottom: none; /* Убираем линию снизу */
        }

        TD, TH {
            padding: 3px; /* Поля вокруг содержимого ячеек */
        }

        TH {
            text-align: left; /* Выравнивание по левому краю */
            background: black; /* Цвет фона */
            color: white; /* Цвет текста */
            border: 1px solid white; /* Рамка вокруг ячеек */
        }

        TD {
            border-bottom: 1px solid black; /* Линия снизу */
        }
    </style>
</head>
<?php
require_once 'db.php';
require_once 'simple_html_dom.php';

$date = $_POST['date'];
$templateId = $_POST['template'];
$datecheckout = new DateTime($date);
$datecheckout->modify('+1 day');
$sql = 'SELECT my_hotel_host FROM templates WHERE id = :tmpId';
$params = [':tmpId' => $templateId];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$myHotelHost = $stmt->fetch(PDO::FETCH_OBJ);
$myHotelHost = $myHotelHost->my_hotel_host;

$sql_my_hotel = 'SELECT name, host FROM hotels WHERE host = :myHost';
$params_my_hotel = [':myHost' => $myHotelHost];
$stmt_my_hotel = $pdo->prepare($sql_my_hotel);
$stmt_my_hotel->execute($params_my_hotel);
$myHotel = $stmt_my_hotel->fetch(PDO::FETCH_OBJ);

$myHotelRes = null;
getInfo($myHotel, $myHotelRes);

$sql = 'SELECT hotels.name, hotels.host FROM templates_hotels
INNER JOIN hotels
ON templates_hotels.template_id = :tmpId AND templates_hotels.hotel_id = hotels.id';
$params = [':tmpId' => $templateId];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hotelsRes = null;

while ($hotel = $stmt->fetch(PDO::FETCH_OBJ)) {
    getInfo($hotel, $hotelsRes);
}

$averageMinPrice = 0;
$averageMaxPrice = 0;
$averageMidlPrice = 0;

foreach ($hotelsRes as $name => $res) {
    $averageMinPrice += $res[0];
    $averageMidlPrice += $res[1];
    $averageMaxPrice += $res[2];
}

$averageMinPrice /= count($hotelsRes);
$averageMidlPrice /= count($hotelsRes);
$averageMaxPrice /= count($hotelsRes);

$dmin = $averageMinPrice - $myHotelRes[$myHotel->name][0];
$dmidle = $averageMidlPrice - $myHotelRes[$myHotel->name][1];
$dmax = $averageMaxPrice - $myHotelRes[$myHotel->name][2];


?>
<body>
<h1><?php echo $myHotel->name ?></h1>
<br>
<h2>Наименьшая цена <?php echo ($dmin > 0) ? 'меньше на ' . $dmin : 'больше на ' . -$dmin ?></h2>
<br>
<h2>Средняя цена <?php echo ($dmidle > 0) ? 'меньше на ' . $dmidle : 'больше на ' . -$dmidle ?></h2>
<br>
<h2>Наибольшая цена <?php echo ($dmax > 0) ? 'меньше на ' . $dmax : 'больше на ' . -$dmax ?></h2>
<br>

<table>
    <tr>
        <th>Название отеля</th>
        <th>Минимальная цена</th>
        <th>Средняя цена</th>
        <th>Максимальная цена</th>
    </tr>
    <?php
    echo "<tr><td>$myHotel->name</td>";
    foreach ($myHotelRes[$myHotel->name] as $value) {
        echo "<td>$value</td>";
    }
    echo '</tr>';
    foreach ($hotelsRes as $k => $value) {
        echo '<tr>';
        echo '<td>' . $k . '</td>';
        foreach ($value as $v) {
            echo '<td>' . $v . '</td>';
        }
        echo '</tr>';
    }
    ?>
</table>
'
</body>

<?php
function getInfo($hotel, &$hotelsRes)
{
    global $datecheckout;
    global $date;
    //global $hotelsRes;

    $request = 'https://ru.hotels.com/' . $hotel->host . '/?q-check-out=' . $datecheckout->format('Y-m-d') .
        '&tab=description&q-room-0-adults=2&YGF=14&q-check-in=' . $date .
        '&MGT=1&WOE=6&WOD=5&ZSX=0&SYE=3&q-room-0-children=0';
    echo $request . '<br>';

    $document = file_get_html($request);

    $prises = $document->find('ins.current-price');
    $prises = array_merge($prises, $document->find('strong.current-price'));
    if (empty($prises)) {
        return;
    }

    $minPrice = null;
    $maxPrice = null;
    $sum = 0;
    foreach ($prises as $prise) {
        $prise = $prise->plaintext;
        $prise = str_replace(' RUB', '', $prise);
        $prise = str_replace(',', '', $prise);
        $intPrise = intval($prise);
        if (empty($minPrice) || $intPrise < $minPrice) {
            $minPrice = $intPrise;
        }
        if (empty($maxPrice) || $intPrise > $minPrice) {
            $maxPrice = $intPrise;
        }
        $sum += $intPrise;
    }

    $sum /= count($prises);
    $hotelsRes[$hotel->name] = [$minPrice, $sum, $maxPrice];
}

?>