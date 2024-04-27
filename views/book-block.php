<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../pages/resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="../pages/data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="book-block.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <!-- block for a book -->
    <div id="book_block" class="" style=<?php echo $offset;?>>

        <!-- image for the book -->
        <img id="book_image" class="img " src=<?php echo $book_image_uri?>>

        <!-- book details -->
        <div id="book_details_div">
            <!-- Book title -->
            <div id="book_title" class="text details_header">
                    <p><span><?php echo $book_title ?></span></p>
            </div>

            <!-- author -->
            <div id="book_author" class="text ">
                <p><span><?php echo $book_author ?></span></p>
            </div>

            <!-- separator line-->
            <img id="separator_line" class="img " src="../pages/images/shop_list/u52.svg">

            <!-- price tag-->
            <div id="price_tag" class="text details_header">
                <p><span><?php echo $book_price." грн" ?></span></p>
            </div>
        </div>

        <!-- button for placing an order-->
        <div id="book_order_div">
            <img id="book_order_btn" class="img " src="../pages/images/shop_list/u43.svg">
            <div id="book_order_text" class="text ">
                <p><span>У кошик</span></p>
            </div>
        </div>

    </div>
</body>
</html>