<?php
  session_start();
  require_once('../session-config.php');
  checkSessionTimeout();
  redirectUnauthorized();

  $root = __DIR__ . "/..";
  require_once("$root/controllers/material-controller.php");
  $mc = new LearningMaterialController;
  $id = $mc->getParams()["id"];
  $material = $mc->getMaterialById($id);
  $categories = $material->getCategories();
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
      <?php include('header.html'); ?>
      <div class="main">

        <div class="breadcrumbs_div">
          <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <a href="learning_materials_all" class="link_hidden">Навчальні матеріали</a>
          &nbsp;&nbsp;>&nbsp;&nbsp;
          <p1 style="font-weight: bold;"><?php echo $material->getTitle(); ?></p1>
          </p>
          
        </div>

        <div class="topic-header">
          <!-- Strikethrough line -->
          <div class="header-line-row"><div class="header-line"></div></div>

          <div class="header topic-header-text">
            <p><?php echo $material->getTitle(); ?></p>
          </div>

        </div>

        <div class="material_content">
          <div class="categories_div">
            <?php foreach($categories as $category): ?>
              <a href="learning_materials_all?category=<?php echo $category->getId(); ?>" class = "button link_hidden"><?php echo $category->getCategoryName(); ?></a>
            <?php endforeach; ?>
          </div>

          <div class="paragraph">
              <?php echo $material->getDescription(); ?>
          </div>
        </div>
      </div>
      <?php
        include('footer.html');
      ?>
  </body>
</html>

