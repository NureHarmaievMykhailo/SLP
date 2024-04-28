<?php
    class BookBlock {
        private $offset;
        private $book_author;
        private $book_title;
        private $book_image_uri;
        private $book_price;
        private $style_path = "/public/book-block.css";

        function __construct($offset, $author, $title, $image_uri, $price) {
            $this->offset = $offset;
            $this->book_author = $author;
            $this->book_title = $title;
            $this->book_image_uri = $image_uri;
            $this->book_price = $price;
        }

        function render() {
            echo "<link href=" . $this->style_path . " type=\"text/css\" rel=\"stylesheet\"/>" .
            "<body>
                <!-- block for a book -->
                <div id=\"book_block\" class=\"\" style=" . $this->offset . ">
            
                    <!-- image for the book -->
                    <img id=\"book_image\" class=\"img \" src=" . $this->book_image_uri . ">
            
                    <!-- book details -->
                    <div id=\"book_details_div\">
                        <!-- Book title -->
                        <div id=\"book_title\" class=\"text details_header\">
                                <p><span>" . $this->book_title . "</span></p>
                        </div>
            
                        <!-- author -->
                        <div id=\"book_author\" class=\"text \">
                            <p><span>" . $this->book_author . "</span></p>
                        </div>
            
                        <!-- separator line-->
                        <img id=\"separator_line\" class=\"img \" src=\"../pages/images/shop_list/u52.svg\">
            
                        <!-- price tag-->
                        <div id=\"price_tag\" class=\"text details_header\">
                            <p><span>" . $this->book_price . " грн" . "</span></p>
                        </div>
                    </div>
            
                    <!-- button for placing an order-->
                    <div id=\"book_order_div\">
                        <img id=\"book_order_btn\" class=\"img \" src=\"../pages/images/shop_list/u43.svg\">
                        <div id=\"book_order_text\" class=\"text \">
                            <p><span>У кошик</span></p>
                        </div>
                    </div>
            
                </div>
            </body>";
        }
    }
?>
