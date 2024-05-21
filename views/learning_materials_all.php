<!DOCTYPE html>
<html>
  <head>
    <title>Learning Materials: All</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<!--     <link href="../pages/resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/data/styles.css" type="text/css" rel="stylesheet"/> -->
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/learning_materials_all.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <?php include('header.html'); ?>
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
      <!-- Unnamed (Line) -->
      <div id="u799" class="ax_default line2">
        <img id="u799_img" class="img " src="../pages/images/homepage/u4.svg"/>
        <div id="u799_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u800" class="ax_default box_1" style="width: 450px;">
        <div id="u800_div" class="" style="width: 450px;"></div>
        <div id="u800_text" class="text ">
        <?php
            if ($isCategorySet) {
              echo "<p><span>Навчальні матеріали: $categoryName</span></p>";
            } else {
              echo "<p><span>Навчальні матеріали: Усі</span></p>";
            }
          ?>
        </div>
      </div>
      <div class="materials-container">
        <?php
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


          $default_left_offset = 60;
          $default_top_offset = 300;

          $left_offset = $default_left_offset;
          $top_offset = $default_top_offset;

          $i = 1;
          foreach ($materials as $material) {
            $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";
            $block = new LearningMaterialBlock($offset, $material->getId(), $material->getTitle(), $material->getShortInfo(), $material->getCategories());
            $block->render_normal();

            if ($i % 2 == 0) {
              $left_offset = $default_left_offset;
              $top_offset += 300;
            } else {
              $left_offset += 700;
            }
            $i++;
          }

        ?>
      </div>
    </div>
    <?php include('footer.html'); ?>
  </body>
</html>
