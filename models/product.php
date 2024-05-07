<?php
    /**
     * Represents a product to be sold at the online store.
     * 
     */
    abstract class Product {
        protected $id;
        protected $title;
        protected $price;
        protected $description;

        public function __construct(int $id, string $title, float $price, string $description) {
            $this->id = $id;
            $this->title = $title;
            $this->price = $price;
            $this->description = $description;
        }

        public function set_id(int $id) {
            $this->id = $id;
        }

        public function get_id() {
            return $this->id;
        }

        public function set_title(string $title) {
            $this->title = htmlspecialchars(stripslashes(trim($title)));
        }

        public function get_title() {
            return $this->title;
        }

        public function set_description(string $description) {
            $this->description = htmlspecialchars(stripslashes(trim($description)));
        }

        public function get_description() {
            return $this->description;
        }

        public function set_price(float $price) {
            $this->price = (float) $price;
        }

        public function get_price() {
            return $this->price;
        }
    }
?>