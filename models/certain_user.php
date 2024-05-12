<?php
$serverName = "";
$connectionOptions = array(
    "Database" => "",
    "Uid" => "",
    "PWD" => ""
);

// З'єднання з сервером MS SQL
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Перевірка з'єднання
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Отримання поточної дати
$date = date('Y-m-d', time());

// Видалення застарілих записів з таблиці list_ip
$sql = "DELETE FROM list_ip WHERE date != ?";
$params = array($date);
$stmt = sqlsrv_query($conn, $sql, $params);

// Оновлення таблиці statistics
$sql = "UPDATE statistics SET hosts = 0, hits = 0 WHERE date != ?";
$stmt = sqlsrv_query($conn, $sql, $params);

// Оновлення дати в таблиці statistics
$sql = "UPDATE statistics SET date = ?";
$stmt = sqlsrv_query($conn, $sql, array($date));

// Отримання IP-адреси відвідувача
$ip = $_SERVER['REMOTE_ADDR'];

// Перевірка чи вже був запис з цієї IP-адреси
$sql = "SELECT * FROM list_ip WHERE ip = ?";
$stmt = sqlsrv_query($conn, $sql, array($ip));
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    // Якщо запис існує, оновлюємо дані в таблиці statistics
    $sql = "SELECT hosts, hits, total FROM statistics";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $new_hits = ++$row['hits'];
    $new_total = ++$row['total'];
    $sql = "UPDATE statistics SET hits = ?, total = ?";
    $params = array($new_hits, $new_total);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Вивід зображення
    output_img($row['hosts'], $new_hits, $new_total);
} else {
    // Якщо запису немає, додаємо новий запис в таблицю list_ip
    $sql = "INSERT INTO list_ip (ip, date) VALUES (?, ?)";
    $params = array($ip, $date);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Оновлюємо дані в таблиці statistics
    $sql = "SELECT hosts, hits, total FROM statistics";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $new_hosts = ++$row['hosts'];
    $new_hits = ++$row['hits'];
    $new_total = ++$row['total'];
    $sql = "UPDATE statistics SET hosts = ?, hits = ?, total = ?";
    $params = array($new_hosts, $new_hits, $new_total);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Вивід зображення
    output_img($new_hosts, $new_hits, $new_total);
}

// Закриття з'єднання з сервером MS SQL
sqlsrv_close($conn);

function output_img($hosts, $hits, $total)
{
    $img = imagecreatefrompng('bg.png');
    $color = ImageColorAllocate($img, 0, 0, 0);
    Imagestring($img, 5, 5, 3, $hosts, $color);
    Imagestring($img, 5, 45, 15, $hits, $color);
    Imagestring($img, 5, 45, 30, $total, $color);
    header("Content-type: image/png");
    ImagePng($img);
}
?>