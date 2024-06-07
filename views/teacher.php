<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized();
?>

<?php
$root = __DIR__ . "/..";
require_once("$root/controllers/teacher-controller.php");
$tc = new TeacherController;
$id = $tc->getParams()["id"];
$teacher = $tc->getTeacherById($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../public/teacher.css" type="text/css" rel="stylesheet" />
  <link href="../public/styles.css" type="text/css" rel="stylesheet" />
  <title><?php echo "Викладач {$teacher->getName()}"; ?></title>
  <script src="../public/tabs.js"></script>
</head>

<body>
  <?php
  $headerFile = 'header.html';
  if (isset($_SESSION['permission']) && $_SESSION['permission'] == PermissionCode::User->value) {
    $headerFile = 'header-logged-in.html';
  }
  include($headerFile);
  ?>
  <div class="main">
    <div class="breadcrumbs_div">
      <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
        &nbsp;&nbsp;>&nbsp;&nbsp;
        <a href="teachers_list" class="link_hidden">Викладачі</a>
        &nbsp;&nbsp;>&nbsp;&nbsp;
        <p1 style="font-weight: bold;"><?php echo $teacher->getName(); ?></p1>
      </p>
    </div>

    <div class="topic-header">
      <div class="header-line-row">
        <div class="header-line"></div>
      </div>

      <div class="header topic-header-text">
        <p>Дізнайтеся більше про вчителя, що зацікавив вас!</p>
      </div>
    </div>

    <div class="block teacher_info_div">
      <div class="teacher_img img-contain">
        <img style="width:100%; height:auto;" class="teacher_pfp" src="<?php echo $teacher->getImageURI(); ?>">
      </div>

      <div class="teacher_name_div">
        <h class="header"><?php echo $teacher->getName(); ?></h>
      </div>

      <div class="teacher_short_info_div">
        <?php echo $teacher->getShortInfo(); ?>
      </div>

      <div class="teacher_price_div">
        <h class="header"><?php echo $teacher->getPrice(); ?>грн/год</h>
      </div>
      <div class="make_appointment_btn_div">
        <a class="link_hidden button" style="color: white;" href="make_appointment?id=<?php echo $id; ?>">Назначити заняття</a>
      </div>
    </div>

    <div class="topic-header">
      <div class="header-line-row">
        <div class="header-line"></div>
      </div>

      <div class="header topic-header-text">
        <p>Детальна інформація</p>
      </div>
    </div>

    <div class="text_default teacher_description_div">
      <?php echo $teacher->getDescription(); ?>
    </div>

    <div class="topic-header">
      <div class="header-line-row">
        <div class="header-line"></div>
      </div>

      <div class="header topic-header-text">
        <p>Додаткова інформація</p>
      </div>
    </div>
    <div class="addition_info_div text_default">
      <div class="tab">
       <button class="tab_btn tab_open" onclick="openTab(event, 'education');">Освіта</button>
       <button class="tab_btn" onclick="openTab(event, 'experience');">Досвід роботи</button>
      </div>
      <div id="education" class="tabcontent" style="display: block;">
        <?php echo $teacher->getEducation();?>
      </div>
      <div id="experience" class="tabcontent">
        <?php echo $teacher->getExperience(); ?>
      </div>
    </div>
  </div>
  <?php include('footer.html'); ?>
</body>

</html>