<?php
/*   error_reporting(E_ALL);
  ini_set('display_errors', 1); */
  session_start();
  require_once('../models/PermissionCode.php');
  require_once('../session-config.php');
  checkSessionTimeout();
  redirectUnauthorized();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Homepage</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/homepage.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <?php
      $headerFile = 'header.html';
      if(isset($_SESSION['permission']) && $_SESSION['permission'] == PermissionCode::User->value) {
        $headerFile = 'header-logged-in.html';
      }
      include($headerFile);
    ?>
    <div class="main">

      <div class="index-summary">
        <h class="header index-summary-header"><span>Вивчайте українську разом з нами</span></h>
        <!-- App text -->
        <p class="text_default index-summary-paragraph"><span>Інтерактивні заняття з викладачами, вільний доступ до навчальних матеріалів, все це ви знайдете на нашому сайті. А задля закріплення вивченого матеріалу ви може виконати тестові завдання в нашому мобільному застосунку.</span></p>
        <!-- App button -->
        <div class="app-details-div">
          <a class="button noselect app-details-btn" style="font-size:20px; ">Дізнатися більше</a>
        </div>
      </div>

      <!-- Materials header -->
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <div class="header topic-header-text">
          <p><span>Навчальні матеріали</span></p>
        </div>

        <div class="header find-out-more">
          <a href="learning_materials" class="link_hidden"><span>Переглянути все</span></a>
          <img class="right-arrow-img" src="../public/images/right-arrow.png"/>
        </div>

      </div>
      
      <div class="material-showcase">
        <?php
          $root = __DIR__ . "/..";
          require_once("$root/controllers/material-controller.php");
          require_once("learning-material-block.php");

          // Get 3 top learning materials from DB
          $mc = new LearningMaterialController;
          $materials = $mc->getAll(3);

          foreach ($materials as $material) {
            $offset = "margin-right: 80px; margin-bottom:100px;";
            $block = new LearningMaterialBlock($material['id'], $material['title'], $material['shortInfo'], [],  $offset);
            $block->render_small();
          }
        ?>
      </div>

      <!-- Teachers header -->
      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <p><span>Викладачі</span></p>
        </div>

        <div class="header find-out-more">
          <a href="teachers_list" class="link_hidden"><span>Переглянути все</span></a>
          <img class="right-arrow-img" src="../public/images/right-arrow.png"/>
        </div>

      </div>

      <div class="teacher-showcase">
        <?php
          require_once("$root/controllers/teacher-controller.php");
          require_once("teacher-block.php"); 
          $tc = new TeacherController;
          // Get 3 top teachers from DB
          $teachers = $tc->getALL(3);

          while ($row = $teachers->fetch_assoc()) {
            $offset = "margin-right: 80px; margin-bottom:100px;";
            $block = new TeacherBlock($offset, $row["id"], $row["teacher_name"], $row["shortInfo"],
                $row["price"], $row["imageURI"]);

            $block->render();
          }
        ?>
      </div>
    <!-- End of main -->
    </div>
    <?php
      include('footer.html');
    ?>
  </body>
</html>
