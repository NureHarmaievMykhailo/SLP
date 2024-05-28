<?php
  session_start();
  require_once('../session-config.php');
  checkSessionTimeout();
  redirectUnauthorized();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/teachers_list.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <title>Teachers</title>
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
          <p1 style="font-weight: bold;">Викладачі</p1>
          </p>
        </div>

        <div class="topic-header">
            <div class="header-line-row"><div class="header-line"></div></div>

            <div class="header topic-header-text">
            <p><span>Знайдіть найкращого репетитора для вивчення української мови!</span></p>
            </div>
        </div>

        <div class="teacher_div">
            <?php
            $root = __DIR__ . "/..";
            require_once("$root/controllers/teacher-controller.php");
            require_once("teacher-block.php"); 
            $tc = new TeacherController;
            $teachers = $tc->getALL(8);

            while ($row = $teachers->fetch_assoc()) {
                $offset = "margin-right: 80px; margin-bottom:100px;";
                $block = new TeacherBlock($offset, $row["id"], $row["teacher_name"], $row["shortInfo"],
                    $row["price"], $row["imageURI"]);

                $block->render();
            }
            ?>
        </div>
    </div>
    <?php include('footer.html');?>
</body>
</html>