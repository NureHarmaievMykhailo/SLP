<?php
session_start();
require_once('../models/shopping-cart.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if (count($uri_segments) > 4) {
        // Bad request
        http_response_code(400);
        exit();
    }

    $productId = $_POST["productId"];

    // URI structure:
    // [0]      [1]             [2]           [3]
    // host/controllers/cart-controller.php/request
    switch($uri_segments[3]) {
        case 'increment':
            echo increment($productId);
            exit();

        case 'decrement':
            echo decrement($productId);
            exit();

        case 'delete':
            echo deleteItem($productId);
            exit();

        case 'totalPrice':
            echo totalPrice();
            exit();
        
        default:
            // Not acceptable
            http_response_code(406);
            exit();
    }
}
else {
    // Handle other HTTP request methods (GET, PUT, DELETE, etc.) if necessary
    // Method not allowed
    http_response_code(405);
}

function deleteItem($id) {
    $cart = unserialize($_SESSION['shoppingCart']);
    $title = $cart->get_items()[$id]->get_title();
    $cart->delete_item($id);
    $res = array("log"=>"Deleted id=$id, title=$title");
    $_SESSION['shoppingCart'] = serialize($cart);
    return json_encode($res);
}

function increment($id) {
    $cart = unserialize($_SESSION['shoppingCart']);
    $title = $cart->get_items()[$id]->get_title();
    $cart->increment_item($id);
    $res = array("log"=>"Incremented id=$id, title=$title", "data"=>$cart->get_items()[$id]->get_quantity());
    $_SESSION['shoppingCart'] = serialize($cart);
    return json_encode($res);
}

function decrement($id) {
    $cart = unserialize($_SESSION['shoppingCart']);
    $title = $cart->get_items()[$id]->get_title();
    $cart->decrement_item($id);
    $res = array("log"=>"Decremented id=$id, title=$title", "data"=>$cart->get_items()[$id]->get_quantity());
    $_SESSION['shoppingCart'] = serialize($cart);
    return json_encode($res);
}

function totalPrice() {
    $cart = unserialize($_SESSION['shoppingCart']);
    $sum = $cart->calculate_total_price();
    $res = array("log"=>"Total sum = $sum", "data"=>$sum);
    return json_encode($res);
}
?>