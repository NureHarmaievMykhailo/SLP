<?php
  session_start();
  require_once('../session-config.php');
  checkSessionTimeout();
  redirectUnauthorized();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Learning Materials</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/learning_materials.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <?php
      include('header.html');
    ?>
    <div class="main">

      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <p><span>Навчальні матеріали: категорії</span></p>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all" class="link_hidden"><p><span>&nbsp;Усі навчальні матеріали</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=1" class="link_hidden"><p><span>&nbsp;Фонетика</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=2" class="link_hidden"><p><span>&nbsp;Орфографія</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=3" class="link_hidden"><p><span>&nbsp;Лексикологія</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=4" class="link_hidden"><p><span>&nbsp;Будова слова</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=5" class="link_hidden"><p><span>&nbsp;Морфологія</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=6" class="link_hidden"><p><span>&nbsp;Синтаксис. Пунктуація</span></p></a>
        </div>

      </div>
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <a href="learning_materials_all?category=7" class="link_hidden"><p><span>&nbsp;Стилістика</span></p></a>
        </div>

      </div>

    </div>
    <?php
      include('footer.html');
    ?>
  </body>
</html>
