<?php
    require_once('product.php');
    /**
     * Represents an item in a list to be added to a shopping cart.
     */
    class ListItem extends product {
        private $quantity;

        public function __construct(string $title, float $price, string $description, int $quantity = 0) {
            parent::__construct($title, $price, $description);
            $this->quantity = (int) $quantity;
        }

        public function get_quantity() {
            return $this->quantity;
        }

        public function set_quantity(int $quantity) {
            if ($quantity >= 0) {
                $this->quantity = $quantity;
                return;
            }
            throw new Exception("Invalid argument: quantity cannot be negative.");
        }
    }
?>