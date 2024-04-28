<!DOCTYPE html>
<html>
  <head>
    <title>shop_list</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../pages/resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/files/shop_list/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../pages/resources/scripts/axure/axQuery.js"></script>
    <script src="../pages/resources/scripts/axure/globals.js"></script>
    <script src="../pages/resources/scripts/axutils.js"></script>
    <script src="../pages/resources/scripts/axure/annotation.js"></script>
    <script src="../pages/resources/scripts/axure/axQuery.std.js"></script>
    <script src="../pages/resources/scripts/axure/doc.js"></script>
    <script src="../pages/resources/scripts/messagecenter.js"></script>
    <script src="../pages/resources/scripts/axure/events.js"></script>
    <script src="../pages/resources/scripts/axure/recording.js"></script>
    <script src="../pages/resources/scripts/axure/action.js"></script>
    <script src="../pages/resources/scripts/axure/expr.js"></script>
    <script src="../pages/resources/scripts/axure/geometry.js"></script>
    <script src="../pages/resources/scripts/axure/flyout.js"></script>
    <script src="../pages/resources/scripts/axure/model.js"></script>
    <script src="../pages/resources/scripts/axure/repeater.js"></script>
    <script src="../pages/resources/scripts/axure/sto.js"></script>
    <script src="../pages/resources/scripts/axure/utils.temp.js"></script>
    <script src="../pages/resources/scripts/axure/variables.js"></script>
    <script src="../pages/resources/scripts/axure/drag.js"></script>
    <script src="../pages/resources/scripts/axure/move.js"></script>
    <script src="../pages/resources/scripts/axure/visibility.js"></script>
    <script src="../pages/resources/scripts/axure/style.js"></script>
    <script src="../pages/resources/scripts/axure/adaptive.js"></script>
    <script src="../pages/resources/scripts/axure/tree.js"></script>
    <script src="../pages/resources/scripts/axure/init.temp.js"></script>
    <script src="../pages/resources/scripts/axure/legacy.js"></script>
    <script src="../pages/resources/scripts/axure/viewer.js"></script>
    <script src="../pages/resources/scripts/axure/math.js"></script>
    <script src="../pages/resources/scripts/axure/jquery.nicescroll.min.js"></script>
    <script src="../pages/data/document.js"></script>
    <script src="../pages/files/shop_list/data.js"></script>
    <script type="text/javascript">
      $axure.utils.getTransparentGifPath = function() { return '../pages/resources/images/transparent.gif'; };
      $axure.utils.getOtherPath = function() { return '../pages/resources/Other.html'; };
      $axure.utils.getReloadPath = function() { return '../pages/resources/reload.html'; };
    </script>
  </head>
  <body>
    <div id="base" class="">
      <?php 
        include('header.html');
      ?>
      <!-- Header line -->
      <div id="u7" class="ax_default line2">
        <img id="u7_img" class="img " src="../pages/images/shop_list/u7.svg"/>
        <div id="u7_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Page header -->
      <div id="u42" class="ax_default heading_1">
        <div id="u42_div" class=""></div>
        <div id="u42_text" class="text ">
          <p><span>Поринь у яскравий світ української літератури!</span></p>
        </div>
      </div>
      <?php
            require_once('../models/shopping-cart.php');
            require_once('book-block.php');
            $romantyka = new Book("\"Я (Романтика)\"", 485, "", "/pages/images/shop_list/u1.svg", "Микола Хвильовий");
            $kaydasheva_simya = new Book("\"Кайдашева сім'я\"", 500, "", "/pages/images/shop_list/u4.svg", "Іван Нечуй-Левицький");
            $tygrolovy = new Book("\"Тигролови\"", 490, "", "/pages/images/shop_list/u6.svg", "Іван Багряний");
            $trysta_poeziy = new Book("\"Триста поезій\"", 350, "", "/pages/images/shop_list/u59.svg", "Ліна Костенко");
            $konotopska_vidma = new Book("\"Конотопська відьма\"", 400, "", "/pages/images/shop_list/u56.svg", "Грирорій Квітка-Основ'яненко");
            $valce = new Book("\"Valse mélancolique\"", 545, "", "/pages/images/shop_list/u81.svg", "Ольга Кобилянська");

            $book_list = array($romantyka, $kaydasheva_simya, $tygrolovy, $trysta_poeziy, $konotopska_vidma, $valce);

            $default_left_offset = 100;
            $default_top_offset = 220;

            $left_offset = $default_left_offset;
            $top_offset = $default_top_offset;
            // we assign i to 1 to make limitation to 3 blocks per row work
            for ($i = 1; $i <= count($book_list); $i++) {
              // subtract 1 to make for the offset
              $book_title = $book_list[$i-1]->get_title();
              $book_author = $book_list[$i-1]->get_author();
              $book_price = $book_list[$i-1]->get_price();
              $book_image_uri = $book_list[$i-1]->get_image_URI();
              $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";

              $block = new BookBlock($offset, $book_author, $book_title, $book_image_uri, $book_price);
              $block->render();
              if ($i % 3 != 0) {
                $left_offset += 430;
              }
              else {
                $left_offset = $default_left_offset;
                $top_offset += 580;
              }
            }
      ?>

      <?php 
        include('footer.html');
      ?>
    </div>
    <script src="/pages/resources/scripts/axure/ios.js"></script>
  </body>
</html>
