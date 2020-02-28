<?php
require_once 'db.php';
require_once 'phpQuery-onefile.php';

$date = $_POST['date'];
$templateId = $_POST['template'];
$datecheckout = new DateTime($date);
$datecheckout->modify('+1 day');
//echo $datecheckout->format('Y-m-d');
$sql = 'SELECT my_hotel_host FROM templates WHERE id = :tmpId';
$params = [':tmpId' => $templateId];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$myHotelHost = $stmt->fetch(PDO::FETCH_OBJ);
$myHotelHost = $myHotelHost->my_hotel_host;

$sql = 'SELECT hotels.name, hotels.host FROM templates_hotels
INNER JOIN hotels
ON templates_hotels.template_id = :tmpId AND templates_hotels.hotel_id = hotels.id';
$params = [':tmpId' => $templateId];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hotelsRes = null;
while ($hotel = $stmt->fetch(PDO::FETCH_OBJ)) {

    $request = 'https://www.booking.com/hotel/ru/' . $hotel->host . '.html?checkin=' . $date .
        '&checkout=' . $datecheckout->format('Y-m-d') . "&group_adults=2&group_children=0&sb_price_type=total&sr_order=popularity";

    echo $request;

    $curl = curl_init($request);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) 
AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
    $page = curl_exec($curl);

    $document = phpQuery::newDocument($page);
    $prises = $document->find('div.bui-price-display__value.prco-font16-helper');

    $minPrice = null;
    $maxPrice = null;
    $sum = 0;
    foreach ($prises as $prise) {
        str_replace('руб.', '', $prise);
        $intPrise = str_replace(' ', '', $prise);
        if (empty($minPrice) || $intPrise < $minPrice){
            $minPrice = $intPrise;
        }
        if (empty($maxPrice) || $intPrise > $minPrice){
            $maxPrice = $intPrise;
        }
        $sum+=$intPrise;
    }

    $sum/=count($prises);
$hotelsRes[$hotel->hotels.name] = [$minPrice, $sum, $maxPrice];
}

$minPrice = 0;
$maxPrice = 0;
$midlPrice = 0;

foreach ($hotelsRes as $name=>$res){
    $minPrice += $res[0];
    $midlPrice += $res[1];
    $maxPrice += $res[2];
}

