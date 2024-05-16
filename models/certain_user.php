<?php
// Підключення до бази даних
$db = mysqli_connect('MySQL-8.0', 'root', '', 'hit_counter');

// Перевірка з'єднання
if (mysqli_connect_errno()) {
    echo "MySQL connection error: " . mysqli_connect_error();
    exit();
}

// Отримання поточної дати
$date = date('Y-m-d', time());

// Видалення застарілих записів з таблиці list_ip
mysqli_query($db, "DELETE FROM `list_ip` WHERE `date` != '$date'");

// Оновлення таблиці statistics
mysqli_query($db, "UPDATE `statistics` SET `hosts`=0, `hits`=0 WHERE `date` != '$date'");
mysqli_query($db, "UPDATE `statistics` SET `date`='$date'");

// Отримання IP-адреси відвідувача
$ip = $_SERVER['REMOTE_ADDR'];

// Перевірка чи вже був запис з цієї IP-адреси
$result = mysqli_query($db, "SELECT * FROM `list_ip` WHERE `ip`='$ip'");
$row = mysqli_num_rows($result);

if ($row > 0) {
    // Якщо запис існує, оновлюємо дані в таблиці statistics
    $result = mysqli_query($db, "SELECT `hosts`, `hits`, `total` FROM `statistics`");
    $row = mysqli_fetch_array($result);
    $new_hits = ++$row['hits'];
    $new_total = ++$row['total'];
    mysqli_query($db, "UPDATE `statistics` SET `hits`='$new_hits', `total`='$new_total'");

    // Вивід зображення
    output_img($row['hosts'], $new_hits, $new_total);
} else {
    // Якщо запису немає, додаємо новий запис в таблицю list_ip
    mysqli_query($db, "INSERT INTO `list_ip` (`ip`, `date`) VALUES ('$ip', '$date')") or die(mysqli_error($db));

    // Оновлюємо дані в таблиці statistics
    $result = mysqli_query($db, "SELECT `hosts`, `hits`, `total` FROM `statistics`");
    
    $row = mysqli_fetch_array($result);
    $new_hosts = ++$row['hosts'];
    $new_hits = ++$row['hits'];
    $new_total = ++$row['total'];
    mysqli_query($db, "UPDATE `statistics` SET `hosts`='$new_hosts', `hits`='$new_hits', `total`='$new_total'");

    // Вивід зображення
    output_img($new_hosts, $new_hits, $new_total);
}

$res = mysqli_query($db, "SELECT `ip`, `count` FROM `ip_list2` WHERE (`ip`='$ip')");
$row = mysqli_num_rows($res);

if ($row == 0) {
    mysqli_query($db, "INSERT INTO `ip_list2` (`ip`, `count`) VALUES ('$ip', 1)");

    $res = mysqli_query($db, "SELECT `ip`, `count` FROM `ip_list2`");
    while ($row = mysqli_fetch_array($res)) {
        echo 'IP: ' . $row['ip'] . ' Count: ' . $row['count'] . '\n';
    }
} else {
    $res = mysqli_query($db, "SELECT `ip`, `count` FROM `ip_list2` WHERE (`ip`='$ip')");
    $row = mysqli_fetch_array($res);
    $count = ++$row['count'];
    mysqli_query($db, "UPDATE `ip_list2` SET `count`='$count' WHERE (`ip`='$ip')");

    $res = mysqli_query($db, "SELECT `ip`, `count` FROM `ip_list2`");
    while ($row = mysqli_fetch_array($res)) {
        echo 'IP: ' . $row['ip'] . ' Count: ' . $row['count'] . '\n';
    }
}

// Закриття з'єднання з базою даних
mysqli_close($db);

function output_img($hosts, $hits, $total)
{
    // Створення нового зображення шириною 400 та висотою 200 пікселів
    $img = imagecreatetruecolor(500, 200);

    // Встановлення коліру фону - білий
    $white = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $white);
    //$img = imagecreatefrompng('../images_db/bd.png');
    $black = ImageColorAllocate($img, 0, 0, 0);
    imagestring($img, 5, 10, 10, "The statistics obtained:", $black,);

    // Додавання підписів
    imagestring($img, 5, 25, 35, "The number of hosts:", $black);
    imagestring($img, 5, 25, 60, "The number of hits:", $black);
    imagestring($img, 5, 25, 85, "The total number:", $black);

    // Додавання значень
    imagestring($img, 5, 230, 35, $hosts, $black);
    imagestring($img, 5, 230, 60, $hits, $black);
    imagestring($img, 5, 230, 85, $total, $black);
    
    // Збереження зображення у форматі PNG
    imagepng($img, '../images_db/bd.png');

    // Звільнення ресурсів
    imagedestroy($img);
    echo "<script type='text/javascript'>
            window.open('../images_db/bd.png', '_blank');
          </script>";
}
?>
