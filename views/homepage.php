<!DOCTYPE html>
<html>
  <head>
    <title>Homepage</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../pages/resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/files/homepage/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <div id="base" class="">
    <?php    
      include('header.html');
      $root = __DIR__ . "/..";
      require_once("$root/controllers/teacher-controller.php");
      require_once("teacher-block.php");
      require_once("$root/controllers/material-controller.php");
      require_once("learning-material-block.php");

      // Get 3 top learning materials from DB
      $mc = new LearningMaterialController;
      $materials = $mc->getAll(3);

      // Get 3 top teachers from DB
      $tc = new TeacherController;
      $teachers = $tc->getALL(3);
      $default_left_offset = 60;
      $default_top_offset = 900;

      $left_offset = $default_left_offset;
      $top_offset = $default_top_offset;

      foreach ($materials as $material) {
        $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";
        $block = new LearningMaterialBlock($offset, $material->getId(), $material->getTitle(), $material->getShortInfo());
        $block->render_small();
        $left_offset += 460;
      }

      // Reset left offset before rendering teachers, set top offset
      $left_offset = $default_left_offset;
      $top_offset += 480;

      while ($row = $teachers->fetch_assoc()) {
        $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";
        $block = new TeacherBlock($offset, $row["id"], $row["teacher_name"], $row["shortInfo"],
            $row["price"], $row["imageURI"]);

        $block->render();

        $left_offset += 460;
      }
    ?>
      <!-- Unnamed (Image) -->
      <div id="u0" class="ax_default image">
        <img id="u0_img" class="img " src="../pages/images/homepage/u0.png"/>
        <div id="u0_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u1" class="ax_default heading_1">
        <div id="u1_div" class=""></div>
        <div id="u1_text" class="text ">
          <p><span>Вивчайте українську разом з нами</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u2" class="ax_default heading_3">
        <div id="u2_div" class=""></div>
        <div id="u2_text" class="text ">
          <p><span>Інтерактивні заняття з викладачами, вільний доступ до навчальних матеріалів, все це ви знайдете на нашому сайті. А задля закріплення вивченого матеріалу ви може виконати тестові завдання в нашому мобільному застосунку.</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u3" class="ax_default">
        <div id="u3_text" class="text ">
          <p><span><a class="button noselect" style="font-size:20px; ">Дізнатися більше</a></span></p>
        </div>
      </div>

      <!-- Unnamed (Line) -->
      <div id="u4" class="ax_default line2">
        <img id="u4_img" class="img " src="../pages/images/homepage/u4.svg"/>
        <div id="u4_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u5" class="ax_default box_1">
        <div id="u5_div" class=""></div>
        <div id="u5_text" class="text ">
          <p><span>Навчальні матеріали</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u6" class="ax_default box_1">
        <div id="u6_div" class=""></div>
        <div id="u6_text" class="text ">
          <p><span>&nbsp;Переглянути все</span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7" class="ax_default image">
        <img id="u7_img" class="img " src="../pages/images/homepage/u7.png"/>
        <div id="u7_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Teachers header -->
      <!-- Unnamed (Line) -->
      <div id="u17" class="ax_default line2">
        <img id="u17_img" class="img " src="../pages/images/homepage/u4.svg"/>
        <div id="u17_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u18" class="ax_default box_1">
        <div id="u18_div" class=""></div>
        <div id="u18_text" class="text ">
          <p><span>Викладачі</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u19" class="ax_default box_1">
        <div id="u19_div" class=""></div>
        <div id="u19_text" class="text ">
          <p><span>&nbsp;Переглянути все</span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u20" class="ax_default image">
        <img id="u20_img" class="img " src="../pages/images/homepage/u7.png"/>
        <div id="u20_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>
      <!-- end of teachers header -->
      <?php
        require_once('footer.php');
        $ft = new Footer(560);
        $ft->render();
      ?>

  <!--
      log in button
      <div id="u80" class="ax_default button">
        <div id="u80_div" class=""></div>
        <div id="u80_text" class="text ">
          <p><span>Увійти</span></p>
        </div>
      </div>
      sign up button
      <div id="u81" class="ax_default button">
        <div id="u81_div" class=""></div>
        <div id="u81_text" class="text ">
          <p><span>Зареєструватися</span></p>
        </div>
      </div>
    -->
    </div>
  </body>
</html>
