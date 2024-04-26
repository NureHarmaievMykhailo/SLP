<?php
    require_once('product.php');
    class listItem extends product {
        public $quantity;

        public function __construct($title, $price, $description, $quantity = 0) {
            parent::__construct($title, $price, $description);
            $this->quantity = $quantity;
        }
    }
?>