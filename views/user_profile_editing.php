﻿<?php
session_start();

// Припустимо, що дані користувача вже збережені в сесії
$userData = $_SESSION['userData'];
// Форматування дати народження
$birthdate = new DateTime($userData['birthdate']);
$formattedBirthdate = $birthdate->format('Y-m-d'); // Формат для input[type="date"]
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Editing</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/user_profile.css" type="text/css" rel="stylesheet"/>
    <link href="../public/sign_up.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/updateProfileData.js"></script>
  </head>
  <body>
  <?php include('header-logged-in.html') ?>
    <div class="main">

      <div class="breadcrumbs_div">
        <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <p1 class="paragraph breadcrumbs_text"><a href="user_profile" class="link_hidden">Мій кабінет</a></p1>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <p2 style="font-weight: bold;">Редагувати дані</p2>
        </p>
      </div>
      <div class="editor_container">
      <div class="editing_block">
        <div class="header_div">
          <h class="header">Редагувати дані</h>
        </div>

        <div class="profile_pic_div">
          <img src="../public/images/user_profile.svg" style="width: 100%; height: auto;">
        </div>

        <div class="first_block_div">
          <input id="firstNameInput" class="sign_up_input" placeholder=" Ім'я" value="<?php echo $userData['firstName']; ?>">
          <input id="lastNameInput"class="sign_up_input" placeholder=" Прізвище" value="<?php echo $userData['lastName']; ?>">
          <input id="emailInput" reqiured class="sign_up_input" type="email" placeholder=" Електронна пошта" value="<?php echo $userData['email']; ?>">
        </div>

        <div class="second_block_left">
          <select id="sexInput" class="sign_up_input">
            <option value="" disabled selected>Стать</option>
            <option value="male"<?php echo $userData['sex'] == 'male' ? 'selected' : ''; ?>>Чоловіча</option>
            <option value="female"<?php echo $userData['sex'] == 'female' ? 'selected' : ''; ?>>Жіноча</option>
            <option value="other"<?php echo $userData['sex'] == 'Other' ? 'selected' : ''; ?>>Інше</option>
          </select>
          <input id="countryInput" class="sign_up_input" placeholder=" Країна" value="<?php echo $userData['country']; ?>">
        </div>

        <div class="second_block_right">
          <input id="birthdateInput" type="date" class="sign_up_input" placeholder=" Дата народження" value="<?php echo $formattedBirthdate; ?>">
          <input id="cityInput" class="sign_up_input" placeholder=" Місто" value="<?php echo $userData['city']; ?>">
        </div>

        <div class="third_block_div">
          <div id="errorDiv" class="text_default sign_up_error_div"></div>
          <input id="phoneNumberInput" type="tel" class="sign_up_input" placeholder=" Номер телефону" value="<?php echo $userData['phoneNumber']; ?>">
  
          <div class="button_div">
            <button class="button_cancle"><a href="user_profile" class="link_hidden">Скасувати</a></button>
            <button class="button_confirm" onclick="updateProfileData();">Підтвердити</button>
          </div>
        </div>

      </div>
    </div>
    </div>
    <?php include('footer.html'); ?>
  </body>
</html>
