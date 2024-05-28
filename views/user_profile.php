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
                    <p class="text_default"><?php echo "Доєднався з " . date_format(new DateTime($_SESSION['userData']['registrationDate']), "d.m.Y"); ?></p>
                </div>
            </div>
            <div class="user_edit_div">
                <button class="button" onclick="logOut();">Вийти</button>
            </div>
        </div>
    </div>
    <?php include('footer.html') ?>
</body>
</html>