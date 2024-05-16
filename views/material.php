<?php
  include('header.html');
  require_once('footer.php');
  $ft = new Footer(0);
  $ft->render();

  $root = __DIR__ . "/..";
  require_once("$root/controllers/material-controller.php");
  $mc = new LearningMaterialController;
  $id = $mc->getParams()["id"];
  $material = $mc->getMaterialById($id);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $material->getTitle(); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/material.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
      <div class="breadcrumbs_div">
        <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
        &nbsp;&nbsp;>&nbsp;&nbsp;
        <a href="learning_materials_all" class="link_hidden">Навчальні матеріали</a>
        &nbsp;&nbsp;>&nbsp;&nbsp;
        <p1 style="font-weight: bold;"><?php echo $material->getTitle(); ?></p1>
        </p>
        
      </div>
      <!-- Unnamed (Line) -->
      <div id="u1066" class="ax_default line2">
        <img id="u1066_img" class="img " src="../pages/images/homepage/u4.svg"/>
        <div id="u1066_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u1067" class="ax_default box_1">
        <div id="u1067_div" class=""></div>
        <div id="u1067_text" class="text ">
          <p><span><?php echo $material->getTitle(); ?></span></p>
        </div>
      </div>

      <div class="paragraph material_content">
          <?php echo $material->getDescription(); ?>
      </div>

  </body>
</html>

