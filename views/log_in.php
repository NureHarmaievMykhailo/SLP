<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/log_in.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/logIn.js"></script>
  </head>
  <body>
    <div class="sign_up_main">
      <div class="log_in_block">
        <div class="header" style="font-size: 38px;">
          <h>Увійти</h>
        </div>
        <div id="errorDiv" class="sign_up_error_div text_default"></div>
        <input id="emailInput" class="sign_up_input" placeholder=" Електронна пошта" style="padding: 6px;">
        <input id="pwdInput" class="sign_up_input" type="password" placeholder=" Пароль" style="padding: 6px;">
        <a href="" class="link_hidden noselect text_default">Забули пароль?</a>
        <button class="sign_up_btn text_default noselect" style="width: 100% !important;" onclick="logIn();">Увійти</button>
        <a class="sign_up_btn text_default noselect" style="width: 100% !important;">
          <img style="padding-right:10px;height: 50%; width: auto;" src="../pages/images/sign_up/u89.png">
          <p>Увійти з допомогою Google</p>
        </a>
        <p class="text_default">Не маєте акаунт? <a href="sign_up" class="link_hidden noselect " style="font-weight: bolder;">Зареєструватися</a></p>
      </div>
    </div>
  </body>
</html>
