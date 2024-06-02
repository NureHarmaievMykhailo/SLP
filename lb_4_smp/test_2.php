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
    <title>XML-документ</title>
</head>
<body>
    <?php include('../views/header-logged-in.html') ?>
    <h2>Результат завантаження документу та отримання кореневого елементу:</h2>
    <div class="info-container">
        <?php include 'manage_users.php' ?> 
    </div>

   <?php include('../views/footer.html') ?> 
</body>

</html>