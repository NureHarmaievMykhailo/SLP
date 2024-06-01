<?php
session_start();

$callbackData = json_decode(file_get_contents('php://input'), true);

file_put_contents('callback_log.txt', print_r($callbackData, true), FILE_APPEND);

if ($callbackData['transactionStatus'] === 'Approved') {

    http_response_code(200);
    echo json_encode(['status' => 'success']);
} else {

    http_response_code(400);
    echo json_encode(['status' => 'failure']);
}
?>
