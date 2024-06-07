<?php

$merchantAccount = '52_149_65_84'; 
$merchantDomainName = '52_149_65_84';
$merchantSecretKey = '2abd9286fc2e494c82820b47251b289f419473b7'; 
$orderReference = 'ORDER22116';
$orderDate = time(); // Время заказа в формате UNIX
$amount = 100.00; // Сумма оплаты
$currency = 'UAH'; // Валюта
$productName = ['Product 1']; // Название продукта
$productPrice = [100.00]; // Цена продукта
$productCount = [1]; // Количество продукта

// Формирование сигнатуры
$signString = $merchantAccount . ';' .
              $merchantDomainName . ';' .
              $orderReference . ';' .
              $orderDate . ';' .
              $amount . ';' .
              $currency . ';' .
              implode(';', $productName) . ';' .
              implode(';', $productCount) . ';' .
              implode(';', $productPrice);

$signature = hash_hmac('md5', $signString, $merchantSecretKey);

// URL для перенаправления
$wayForPayUrl = 'https://secure.wayforpay.com/pay';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>
<body onload="document.forms['wayforpay'].submit();">
    <form name="wayforpay" action="<?php echo $wayForPayUrl; ?>" method="POST">
        <input type="hidden" name="merchantAccount" value="<?php echo $merchantAccount; ?>">
        <input type="hidden" name="merchantDomainName" value="<?php echo $merchantDomainName; ?>">
        <input type="hidden" name="orderReference" value="<?php echo $orderReference; ?>">
        <input type="hidden" name="orderDate" value="<?php echo $orderDate; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="currency" value="<?php echo $currency; ?>">
        <input type="hidden" name="productName[]" value="<?php echo $productName[0]; ?>">
        <input type="hidden" name="productPrice[]" value="<?php echo $productPrice[0]; ?>">
        <input type="hidden" name="productCount[]" value="<?php echo $productCount[0]; ?>">
        <input type="hidden" name="merchantSignature" value="<?php echo $signature; ?>">
    </form>
</body>
</html>
