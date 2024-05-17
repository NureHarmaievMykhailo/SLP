<?php
    class CartBlock {
        private $offset;
        private $id;
        private $book_author;
        private $book_title;
        private $book_image_uri;
        private $book_price;
        private $style_path = "/public/shopping-cart.css";
        private $quantity;

        function __construct($offset, $id, $author, $title, $image_uri, $price, $quantity) {
            $this->offset = $offset;
            $this->id = $id;
            $this->book_author = $author;
            $this->book_title = $title;
            $this->book_image_uri = $image_uri;
            $this->book_price = $price;
            $this->quantity = $quantity;
        }

        function render() {
          echo "<link href=$this->style_path type=\"text/css\" rel=\"stylesheet\"/>";
          echo "      <!-- Main block-->
          <div class=\"cart_block\" style=\"$this->offset\">
              <img class=\"image image_cart \" src=\"$this->book_image_uri\"/>
    
              <div class=\"paragraph paragraph_author\">
                  <p><span>$this->book_author</span></p>
              </div>
              <div class=\"header header_title\">
                <p><span>$this->book_title</span></p>
              </div>
              <button class =\"button button_delete\" onclick=\"deleteItem($this->id); showToast('Товар видалено з кошику')\"></button>
    
              <div class=\"header header_price\">
                <p><span>$this->book_price</span></p>
              </div>
    
              <div class=\"quantity_stepper\">
                <div class=\"paragraph paragraph_quantity_label\">
                  <p><span>Кількість:</span></p>
                </div>
    
                <button id=\"stepper_inc$this->id\" class=\"button stepper_button stepper_inc\" onclick=\"incrementItem($this->id);\"><span>+</span></button>
    
                <div class=\"paragraph paragraph_quantity\">
                  <p id=\"quantity_number$this->id\" class=\"quantity_number\"><span>$this->quantity</span></p>
                </div>
    
                <button id=\"stepper_dec$this->id\" class=\"button stepper_button stepper_dec\" onclick=\"decrementItem($this->id);\"><p class=\"stepper_dec_text\">—</p></button>
              </div>
          </div>";
        }
    }
?>