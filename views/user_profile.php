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
    <link href="../public/user_profile.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/logOut.js"></script>
    <title>User Profile</title>
</head>
<body>
    <?php include('header-logged-in.html') ?>
    
    <div class="main">
        <div class="breadcrumbs_div">
          <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <p1 style="font-weight: bold;">Мій кабінет</p1>
          </p>
        </div>

        <div class="topic-header">
          <div class="header-line-row"><div class="header-line"></div></div>

          <div class="header topic-header-text">
            <p>Мій кабінет</p>
          </div>
        </div>

        <div class="block profile_data_div">
            <div class="user_img_div img-contain">
                <img class="user_img" src="../public/images/user_profile_img.svg">
            </div>
            <div class="user_data_div">
                <p class="header"><?php echo "{$_SESSION['userData']['firstName']} {$_SESSION['userData']['lastName']}"; ?></p>
                <p class="text_default"><?php echo $_SESSION['userData']['email']; ?></p>
                <div class="user_registration_date_div">
                    <div class="img-contain"><img src="../public/images/user_profile_watch.svg"></div>
                    <p class="text_default"><?php echo "Доєднався " . date_format(new DateTime($_SESSION['userData']['registrationDate']), "d.m.Y"); ?></p>
                </div>
            </div>
            
            <div class="user_edit_div">
            <button class="button" onclick="window.location.href='../views/user_profile_editing';">Редагувати</button>
            </div>
            
            <div class="user_edit_div">
                <button class="button" onclick="logOut();">Вийти</button>
            </div>
            <div id ="toast-notification" class="toast-notification"></div>
        </div>
        
        <?php
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo "<div id='messageBlock' class='alert alert-success' style='display:none;'>Зміни успішно збережено!</div>";
            }
        ?>

        <script>
            window.onload = function() {
            var messageBlock = document.getElementById('messageBlock');
            messageBlock.style.display = 'block';
            var duration = 5000;
            setTimeout(function() {
                messageBlock.style.display = 'none';
            }, duration);
        }
        </script>
    </div>
   <?php include('footer.html') ?> 
</body>

</html>