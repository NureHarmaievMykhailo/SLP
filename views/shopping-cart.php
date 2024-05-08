<!DOCTYPE html>
<html>
  <head>
    <title>Shopping Cart</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <link href="../pages/files/shopping_cart/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
  </head>
  <body>
    <div id="base" class="">
      <?php
        session_start();
        include('header.html');
      ?>
      <!-- Unnamed (Line) -->
      <div id="u90" class="ax_default line2">
        <img id="u90_img" class="img " src="../pages/images/shop_list/u7.svg"/>
        <div id="u90_text" class="text " style="display:none; visibility: hidden">
          <p></p>
        </div>
      </div>

      <!-- Page header -->
      <div id="u125" class="ax_default heading_1 page_header">
        <div id="u125_div" class=""></div>
        <div id="u125_text" class="text ">
          <p><span>Перегляньте товари у вашому кошику!</span></p>
        </div>
      </div>
      <?php
            require_once('../models/shopping-cart.php');
            require_once('cart-block.php');
            $default_left_offset = 70;
            $default_top_offset = 250;

            $left_offset = $default_left_offset;
            $top_offset = $default_top_offset;

            $cart = unserialize($_SESSION['shoppingCart']);
            // we assign i to 1 to make limitation to 2 blocks per row work
            $i = 1;
            $items = $cart->get_items();

            foreach ($items as $item) {
              $id = $item->get_id();
              $title = $item->get_title();
              $author = $item->get_author();
              $price = $item->get_price();
              $image_uri = $item->get_image_uri();
              $quantity = $item->get_quantity();
              $offset = "top:" . $top_offset . "px;left:" . $left_offset . "px;";

              $block = new CartBlock($offset, $id, $author, $title, $image_uri, $price, $quantity);
              $block->render();

              if ($i % 2 != 0) {
                $left_offset += 730;
              }
              else {
                $left_offset = $default_left_offset;
                $top_offset += 380;
              }
              $i++;
            }
      ?>
      <script src="../public/modifyCart.js"></script>
      <?php 
        include('footer.html');
      ?>
    </div>
    <div id="toast-notification" class="toast-notification"></div>
    <script src="/public/showToast.js"></script>
  </body>
</html>
