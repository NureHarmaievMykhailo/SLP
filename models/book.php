<?php
    /**
     * Represents a book to be sold at the online store.
     */
    require_once('product.php');
    class Book extends Product {
        protected $image_URI;
        protected $author;

        public function __construct(string $title, float $price, string $description, string $image_URI, string $author) {
            parent::__construct($title, $price, $description);
            $this->image_URI = $image_URI;
            $this->author = $author;
        }

        public function get_image_URI(){
            return $this->image_URI;
        }

        public function set_image_URI($uri){
            $this->image_URI = $uri;
        }

        public function get_author(){
            return $this->author;
        }

        public function set_author($author){
            $this->author = $author;
        }
    }
?>