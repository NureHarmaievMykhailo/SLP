<?php
session_start();
require_once('../models/shopping-cart.php');

//book_list
include('../public/book-data.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST['productId'];
    $selection;
    foreach ($book_list as $book) {
        if ($book->get_id() == $productId) {
            $selection = $book;
        }
    }
    if (!isset($selection)) {
        echo "Id not found\n";
        exit();
    }

    if (isset($_SESSION['shoppingCart'])) {
        echo "Cart already set\n";
        $cart = unserialize($_SESSION['shoppingCart']);
        if ($cart->has_id($productId)) {
            $cart->increment_item($productId);
            echo "Updated existing instance\n";
        }
        else {
            $cart->add_item(
            new ListItem($selection->get_id(),
                $selection->get_title(),
                $selection->get_price(),
                $selection->get_description(),
                $selection->get_image_URI(),
                $selection->get_author(), 1)
            );
            echo "Added new instance\n";
        }
        var_dump($cart);
        $_SESSION['shoppingCart'] = serialize($cart);
    }
    else {
        echo "Cart not set\n";
        var_dump($cart);
        $_SESSION['shoppingCart'] = serialize(new Cart([new ListItem($selection->get_id(),
                                                            $selection->get_title(),
                                                            $selection->get_price(),
                                                            $selection->get_description(),
                                                            $selection->get_image_URI(),
                                                            $selection->get_author(), 1)
                                                        ]));
    }
} else {
    // Handle other HTTP request methods (GET, PUT, DELETE, etc.) if necessary
    echo "Invalid request method";
}
?>
