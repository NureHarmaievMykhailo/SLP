<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */

function callControllerMethod($controller, $method, $params) {
    // Check if the method exists in the controller
    if (method_exists($controller, $method)) {
        // Call the method dynamically with parameters
        $result = call_user_func_array([$controller, $method], array_values($params));
        echo $result;
    } else {
        // Bad request
        echo http_response_code(400);

    }
}

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? '';
    $params = $_POST['params'] ?? [];

    if($_POST['controller'] == 'material-controller') {
        require_once "material-controller.php";

        $controller = new LearningMaterialController();

        echo callControllerMethod($controller, $method, $params);
    }
    else if($_POST['controller'] == 'sign-up-controller') {
        require_once("sign-up-controller.php");
    
        $controller = new SignUpController;
    
        echo callControllerMethod($controller, $method, $params);
    }
} else {
    // Method not allowed
    echo http_response_code(405);
}
?>
