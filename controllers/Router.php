<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1);*/

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
    $controllerName = $_POST['controller'];

    if($controllerName == 'material-controller') {
        require_once "material-controller.php";

        $controller = new LearningMaterialController();

        echo callControllerMethod($controller, $method, $params);
    }
    else if($controllerName == 'teacher-controller') {
        require_once('teacher-controller.php');

        $controller = new TeacherController;
        if ($method == 'insertTeacher' || $method == 'updateTeacher') {
            if ($_FILES['file']['size'] > 5242880) {
                echo json_encode(["error"=>"Error. File must be less than 5Mb."]);
                die();
            }
            $params = json_decode($params, true);
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $uploadDir = '../public/images/';
                $uploadFile = $uploadDir . time() . basename($_FILES['file']['name']);
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                    $params['imageURI'] = $uploadFile ?? $params['imageURI'];
                }
            }
        }
        echo callControllerMethod($controller, $method, $params);
    }
    else if($controllerName == 'sign-up-controller') {
        require_once("sign-up-controller.php");
    
        $controller = new SignUpController;
    
        echo callControllerMethod($controller, $method, $params);
    }
    else if($controllerName == 'log-in-controller') {
        require_once('log-in-controller.php');

        $controller = new LogInController;

        echo callControllerMethod($controller, $method, $params);
    }
    else if($controllerName == 'upload_changes') {
        require_once('upload_changes.php');

        $controller = new UploadController;

        echo callControllerMethod($controller, $method, $params);
    }
    else if($controllerName == 'user-controller') {
        require_once "user-controller.php";

        $controller = new UserController();

        echo callControllerMethod($controller, $method, $params);
    }
    else {
        // Not Acceptable
        echo http_response_code(406);
    }
} else {
    // Method not allowed
    echo http_response_code(405);
}
?>
