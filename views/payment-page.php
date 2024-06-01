<?php
session_start();
require_once 'config.php'; // подключаем файл конфигурации

// Ваши данные для доступа к WayForPay
$merchantAccount = "52_149_65_84"; // замените на ваш логин WayForPay
$merchantSecretKey = "2abd9286fc2e494c82820b47251b289f419473b7"; // замените на ваш секретный ключ WayForPay

// URL для запросов к API WayForPay
$wayForPayApiUrl = "https://secure.wayforpay.com/pay";

function generateSignature($data, $secretKey) {
    $hashString = implode(';', $data);
    return hash_hmac('md5', $hashString, $secretKey);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderReference = uniqid("order_", true);
    $orderDate = time();
    $amount = $_POST['amount'];
    $currency = 'UAH';
    $productName = ['Тестовый товар'];
    $productCount = [1];
    $productPrice = [$amount];

    $data = [
        'merchantAccount' => $merchantAccount,
        'merchantDomainName' => $_SERVER['localhost:3000/views'],
        'orderReference' => $orderReference,
        'orderDate' => $orderDate,
        'amount' => $amount,
        'currency' => $currency,
        'productName' => $productName,
        'productCount' => $productCount,
        'productPrice' => $productPrice,
        'merchantSignature' => generateSignature([
            $merchantAccount,
            $_SERVER['localhost:3000/views'],
            $orderReference,
            $orderDate,
            $amount,
            $currency,
            implode(';', $productName),
            implode(';', $productCount),
            implode(';', $productPrice)
        ], $merchantSecretKey),
        'returnUrl' => 'http://' . $_SERVER['localhost:3000/views'] . '/payment_success.php',
        'serviceUrl' => 'http://' . $_SERVER['localhost:3000/views'] . '/payment_callback.php'
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Оплата</title>
</head>
<body>
    <h1>Оплата через WayForPay</h1>
    <form method="POST" action="payment.php">
        <label for="amount">Сумма к оплате (UAH):</label>
        <input type="number" name="amount" id="amount" required>
        <button type="submit">Оплатить</button>
    </form>
    <?php if (!empty($data)): ?>
        <form id="wayforpay-payment-form" method="POST" action="<?php echo $wayForPayApiUrl; ?>">
            <?php foreach ($data as $key => $value): ?>
                <?php if (is_array($value)): ?>
                    <?php foreach ($value as $v): ?>
                        <input type="hidden" name="<?php echo $key; ?>[]" value="<?php echo $v; ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        </form>
        <script>
            document.getElementById('wayforpay-payment-form').submit();
        </script>
    <?php endif; ?>
</body>
</html>
