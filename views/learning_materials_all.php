<?php
  session_start();
  require_once('../session-config.php');
  checkSessionTimeout();
  redirectUnauthorized();

  $root = __DIR__ . "/..";
  require_once("$root/controllers/material-controller.php");
  require_once("learning-material-block.php");

  $mc = new LearningMaterialController;
  $isCategorySet = isset($mc->getParams()["category"]);

  // Default mode if category is not set, equals 0 or can't be cast to integer
  if(!$isCategorySet || !intval($mc->getParams()["category"])) {
    // Get some top learning materials from DB. TODO:implement paging later 
    $materials = $mc->getAll(6);
  }
  else {
    $categoryId = $mc->getParams()["category"];
    $categoryName = $mc->getCategoryName($categoryId);
    $materials = $mc->getMaterialsByCategoryId($categoryId);
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Learning Materials: All</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/learning_materials_all.css" type="text/css" rel="stylesheet"/>
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
      <div class="breadcrumbs_div">
          <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <a href="learning_materials" class="link_hidden">Навчальні матеріали</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <?php
            if ($isCategorySet) {
              echo "<p1 style=\"font-weight: bold;\">Навчальні матеріали: $categoryName</p1>";
            } else {
              echo "<p1 style=\"font-weight: bold;\">Навчальні матеріали: Усі</p1>";
            }
          ?>
          </p>
          
      </div>

      <div class="topic-header">
        <!-- Strikethrough line -->
        <div class="header-line-row"><div class="header-line"></div></div>

        <!-- Unnamed (Rectangle) -->
        <div class="header topic-header-text">
          <p>Навчальні матеріали: <?php $categoryText = ($isCategorySet) ? ($categoryName) : "Усі"; echo $categoryText; ?></p>
        </div>

      </div>


      <div class="materials-container">
        <?php
          foreach ($materials as $material) {
            $block = new LearningMaterialBlock($material->getId(), $material->getTitle(), $material->getShortInfo(), $material->getCategories());
            $block->render_normal();
          }

        ?>
      </div>
    </div>
    <?php include('footer.html'); ?>
  </body>
</html>
