<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized([PermissionCode::Moderator->value, PermissionCode::Admin->value]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../public/styles.css" type="text/css" rel="stylesheet" />
  <link href="../public/moderator-home.css" type="text/css" rel="stylesheet" />
  <script src="../public/jquery-3.7.1.min.js"></script>
  <script src="../public/sendPost.js"></script>
  <script src="../public/logOut.js"></script>
  <title>Home: moderator</title>
</head>

<body>
  <?php include("moderator-header.php"); ?>
  <div class="main">
    <div class="mod_home_header">
      <h class="text_default header">Ласкаво просимо на сторінку модератора</h>
    </div>
    <div class="mod_home_main">
      <div class="mod_home_block">
        <h class="text_default header">Доброго дня, <?php echo $_SESSION['userData']['email']; ?></h>
        <button class="button" onclick="logOut();">Вийти з профілю</button>
      </div>
      <div class="mod_home_block">
        <h class="text_default header ">Перегляд та редагування:</h>
        <p class="noselect header "><a href="material_control_panel">Material overview</a></p>
        <p class="noselect header "><a href="teacher_control_panel">Teacher overview</a></p>
        <p class="noselect header "><a href="material_edit">Add new material</a></p>
        <p class="noselect header "><a href="teacher_edit">Add new teacher</a></p>
      </div>
    </div>
  </div>
</body>

</html>