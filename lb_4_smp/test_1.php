<?php
    session_start();
    require_once('../session-config.php');
    checkSessionTimeout();
    redirectUnauthorized([PermissionCode::User->value]);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="test_style.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>

    <title>Завантаження застосунку</title>
</head>
<body>
    <?php include('../views/header-logged-in.html') ?>
    
    <h1>Завантажити мобільний застосунок можна на цих платформах за посиланням:</h1>
    <div class="table-container">
        <?php
        include 'parser.php';// Підключення файлу з парсером

        // HTML контент для парсингу
        $xmlContent = <<<XML
        <table>
            <tr>
                <th>Назва крамниці застосунків</th>
                <th>Посилання</th>
            </tr>
            <tr>
                <td>Google Play</td>
                <td>https://play.google.com/store/apps</td>
            </tr>
            <tr>
                <td>App Store</td>
                <td>https://www.apple.com/app-store/</td>
            </tr>
            <tr>
                <td>Microsoft Store</td>
                <td>https://apps.microsoft.com/</td>
            </tr>
        </table>
        XML;

        echo parseXMLToHTML($xmlContent);// Виклик функції парсинга та виведення результату
        ?>
    </div>

    <!-- < include 'manage_users.php' ?> -->
    
   <?php include('../views/footer.html') ?> 
</body>

</html>