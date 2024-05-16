<link href="../public/styles.css" type="text/css" rel="stylesheet"/>
<?php
  echo "Page under construction. Thanks for your patience.\n";
  $root = __DIR__ . "/..";
  require_once("$root/controllers/material-controller.php");
  require_once("./learning-material-block.php");
  $tc = new LearningMaterialController;
  $id = $tc->getParams()["id"];
  $material = $tc->getMaterialById($id);
  $block = new LearningMaterialBlock("", $id, $material->getTitle(), $material->getShortInfo(), $material->getDescription());
  $block->render_normal();
?>
