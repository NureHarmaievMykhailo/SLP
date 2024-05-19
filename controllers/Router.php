<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['controller'] == 'material-controller') {
        require_once "material-controller.php";

        $method = $_POST['method'] ?? '';
        $params = $_POST['params'] ?? [];

        // Instantiate the controller
        $controller = new LearningMaterialController();

        // Check if the method exists in the controller
        if (method_exists($controller, $method)) {
            // Call the method dynamically with parameters
            $result = call_user_func_array([$controller, $method], $params);
            echo $result;
        } else {
            // Bad request
            echo http_response_code(400);

        }
    }
} else {
    // Method not allowed
    echo http_response_code(405);
}
?>
