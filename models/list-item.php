<?php
    require_once('book.php');
    /**
     * Represents an item in a list to be added to a shopping cart.
     */
    class ListItem extends Book {
        private $quantity;

        public function __construct(string $title, float $price, string $description, string $image_URI, string $author, int $quantity = 0) {
            parent::__construct($title, $price, $description, $image_URI, $author);
            $this->quantity = (int) $quantity;
        }

        public function get_quantity() {
            return $this->quantity;
        }

        /**
         * Sets the quantity property.
         * @param int $quantity
         * @throws InvalidArgumentException If the input is less than 0 or not an intiger.
         */
        public function set_quantity(int $quantity) {
            if ($quantity >= 0) {
                $this->quantity = $quantity;
                return;
            }
            throw new InvalidArgumentException("set_quantity only accepts positive integers and 0. Input was: ".$quantity);
        }
    }
?>