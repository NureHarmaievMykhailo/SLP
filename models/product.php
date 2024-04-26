<?php
    class product {
        private $title;
        private $price;
        private $description;

        public function __construct($title, $price, $description) {
            $this->title = $title;
            $this->price = $price;
            $this->description = $description;
        }

        public function set_title($title) {
            $this->title = htmlspecialchars(stripslashes(trim($title)));
        }

        public function get_title() {
            return $this->title;
        }

        public function set_description($description) {
            $this->description = htmlspecialchars(stripslashes(trim($description)));
        }

        public function get_description() {
            return $this->description;
        }

        public function set_price($price) {
            $this->price = (float) $price;
        }

        public function get_price() {
            return $this->price;
        }
    }
?>