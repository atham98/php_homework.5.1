<?php
error_reporting(E_ALL);
session_start();
require  __DIR__ . '/vendor/autoload.php';

$_SESSION['address'] = $_GET['address'] ?? $_SESSION['address'];
if(isset($_GET['submit']) && !empty($_GET['submit']) || !empty($_SESSION['address'])) {
        $api = new \Yandex\Geo\Api();


        $address = $_SESSION['address'];
    //икать по адресу
        $api->setQuery($address);

    // Настройка фильтров
        $api
            //  ->setLimit(1) // кол-во результатов
            ->setLang(\Yandex\Geo\Api::LANG_US)// локаль ответа
            ->load();

        $response = $api->getResponse();
}
if(!empty($response)) {
    //   $response->getFoundCount(); // кол-во найденных адресов

    // Список найденных точек
    $collection = $response->getList();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap"><div>
    <form action="index.php" method="get">
        <input type="text" name="address" placeholder="Адрес">
        <input type="submit" name="submit" value="Найти">
    </form>
    <table>
        <?php if(!empty($collection) || !empty($_GET['Address'])):?>
        <tr>
            <th>Адрес</th>
            <th>Широта</th>
            <th>Долгота</th>
        </tr>
        <?php endif; ?>
        <?php if(!empty($collection)) : foreach ($collection as $item): ?>
            <tr class="con_tr">
                <td>
                    <a href="index.php?Address=<?=$item->getAddress();?>&Latitude=<?=$item->getLatitude();?>&Longitude=<?=$item->getLongitude();?>"><?= $item->getAddress(); ?></a>
                </td>
                <td><?= $item->getLatitude(); ?></td>
                <td><?= $item->getLongitude(); ?></td>
            </tr>
        <?php endforeach; endif;?>
        <?php if(!empty($_GET['Address'])): ?>
            <tr>
                <td id="get_address">Ваш адрес : <?= $_GET['Address']; ?></td>
                <td><?= $_GET['Latitude']; ?></td>
                <td><?= $_GET['Longitude']; ?></td>
            </tr>
        <?php endif; ?>
    </table>
    </div>
    <div id="map"><script><?php require_once 'reverse_geocode.js'; ?></script></div>
</div>
</body>
</html>
