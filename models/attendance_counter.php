<?php
session_start();

if (!isset($_SESSION['counted'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $uri = $_SERVER['REQUEST_URI'];
    $user = $_SERVER['PHP_AUTH_USER'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "Ні";
    $dtime = date('r');
    
    $entry_line = "$dtime - IP: $ip | Agent: $agent | URL: $uri | Referrer: $ref | Username: $user" . PHP_EOL;

    $fp = fopen("../logs.txt", "a");
    fwrite($fp, $entry_line);
    fclose($fp);

    $_SESSION['counted'] = true; // Встановлюємо прапорець counted в сесії, щоб позначити, що запис був вже зроблений
}
?>