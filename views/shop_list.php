<!DOCTYPE html>
<html>
  <head>
    <title>Shop</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../pages/resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/files/shop_list/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
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
      <div id="u42" class="ax_default heading_1 page_header">
        <div id="u42_div" class=""></div>
        <div id="u42_text" class="text ">
          <p><span>Поринь у яскравий світ української літератури!</span></p>
        </div>
      </div>
      <?php
            require_once('../models/shopping-cart.php');
            require_once('book-block.php');
            include('../public/book-data.php');

            $default_left_offset = 100;
            $default_top_offset = 220;

            $left_offset = $default_left_offset;
            $top_offset = $default_top_offset;
            // we assign i to 1 to make limitation to 3 blocks per row work
            for ($i = 1; $i <= count($book_list); $i++) {
              // subtract 1 to make for the offset
              $id = $book_list[$i-1]->get_id();
              $book_title = $book_list[$i-1]->get_title();
              $book_author = $book_list[$i-1]->get_author();
              $book_price = $book_list[$i-1]->get_price();
              $book_image_uri = $book_list[$i-1]->get_image_URI();
              $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";

              $block = new BookBlock($offset, $id, $book_author, $book_title, $book_image_uri, $book_price);
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
      <script src="/public/addToCart.js"></script>
      <?php
        require_once('footer.php');
        $ft = new Footer($top_offset - 580 * 2);
        $ft->render();
      ?>
    </div>

    <div id="toast-notification" class="toast-notification"></div>
    <script src="/public/showToast.js"></script>
  </body>
</html>
