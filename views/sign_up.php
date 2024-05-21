<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sign up</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/sign_up.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/signUp.js"></script>
  </head>
  <body>
    <div class="sign_up_main">
      <div class="sign_up_block">
        <div class="header_div">
          <h class="header">Зареєструватися</h>
        </div>
        <div class="profile_pic_div">
          <img src="../public/images/user_profile.svg" style="width: 100%; height: auto;">
        </div>
        <div class="first_block_div">
          <input id="firstNameInput" class="sign_up_input" placeholder=" Ім'я">
          <input id="lastNameInput"class="sign_up_input" placeholder=" Прізвище">
          <input id="emailInput" reqiured class="sign_up_input" type="email" placeholder=" Електронна пошта">
        </div>
        <div class="second_block_left">
          <select id="sexInput" class="sign_up_input">
            <option value="" disabled selected>Стать</option>
            <option value="male">Чоловіча</option>
            <option value="female">Жіноча</option>
            <option value="other">Інше</option>
          </select>
          <input id="countryInput" class="sign_up_input" placeholder=" Країна">
        </div>
        <div class="second_block_right">
          <input id="birthdateInput" type="date" class="sign_up_input" placeholder=" Дата народження">
          <input id="cityInput" class="sign_up_input" placeholder=" Місто">
        </div>
        <div class="third_block_div">
          <div id="errorDiv" class="text_default sign_up_error_div"></div>
          <input id="phoneNumberInput" type="tel" class="sign_up_input" placeholder=" Номер телефону">
          <input id="pwdInput" reqiured type="password" class="sign_up_input" placeholder=" Пароль">
          <input id="pwdConfirmInput" reqiured type="password" class="sign_up_input" placeholder=" Повторити пароль">
        </div>
        <div class="sign_up_btn_div">
          <!-- <div id="error_div" class="sign_up_error_div"></div> -->
          <a class="sign_up_btn text_default noselect sign_up_google_btn">
            <img style="padding-left:10px;height: 50%; width: auto;" src="../pages/images/sign_up/u89.png">
            <p>Зареєструватися з допомогою Google</p>
          </a>
          <button class="sign_up_btn text_default noselect" onclick="signUp();">Зареєструватися</button>
        </div>
        <div class="log_in_btn_div">
          <p class="text_default noselect">Вже маєте акаунт? <a href="log_in" class="link_hidden" style="font-weight: bold;">Увійти</a></p>
        </div>
      </div>
    </div>
  </body>
</html>
