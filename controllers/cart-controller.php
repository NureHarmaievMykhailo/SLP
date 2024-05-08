<?php
session_start();
require_once('../models/shopping-cart.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cart = unserialize($_SESSION['shoppingCart']);
    $productId = $_POST["productId"];

    if ($_POST["cartRequestType"] == "delete") {
        $cart->delete_item($productId);
        $res = array("status"=>"deleted id=$productId", "data"=>var_dump($cart->get_items()));
        echo json_encode($res);
        $_SESSION['shoppingCart'] = serialize($cart);
        exit();
    }
    if ($_POST["cartRequestType"] == "increment") {
        $cart->increment_item($productId);
        $res = array("status"=>"incremented id=$productId", "quantity"=>$cart->get_items()[$productId]->get_quantity());
        echo json_encode($res);
        $_SESSION['shoppingCart'] = serialize($cart);
        exit();
    }
    if ($_POST["cartRequestType"] == "decrement") {
        $cart->decrement_item($productId);
        $res = array("status"=>"decremented id=$productId", "quantity"=>$cart->get_items()[$productId]->get_quantity());
        echo json_encode($res);
        $_SESSION['shoppingCart'] = serialize($cart);
        exit();
    }
    if ($_POST["cartRequestType"] == "getTotalSum") {
        $sum = $cart->calculate_total_price();
        $res = array("status"=>"total sum = $sum", "sum"=>$sum);
        echo json_encode($res);
        exit();
    }
    // Not acceptable
    http_response_code(406);
} else {
    // Handle other HTTP request methods (GET, PUT, DELETE, etc.) if necessary
    // method not allowed
    http_response_code(405);
}
?>