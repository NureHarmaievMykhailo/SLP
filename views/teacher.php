<link href="../public/styles.css" type="text/css" rel="stylesheet"/>
<?php
  echo "Page under construction. Thanks for your patience.\n";
  $root = __DIR__ . "/..";
  require_once("$root/controllers/teacher-controller.php");
  require_once("teacher-block.php");
  $tc = new TeacherController;
  $id = $tc->getParams()["id"];
  $teacher = $tc->getTeacherById($id);
  $block = new TeacherBlock("", $id, $teacher->getName(), $teacher->getShortInfo(), $teacher->getPrice(), $teacher->getImageURI());
  $block->render();
?>