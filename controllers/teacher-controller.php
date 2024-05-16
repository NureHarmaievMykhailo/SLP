<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/TeacherModel.php");

class TeacherController extends Controller {
    private $params;

    private function parseParams() {
        $query_params = [];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        if (!empty($uri)) {
            parse_str($uri, $query_params);
        }
        return $query_params;
    }

    public function __construct() {
        $this->params = $this->parseParams();
    }

    public function getTeacherById($id) {
        $teacher = new Teacher;
        $teacher->getFromDB($id);
        return $teacher;
    }

    public function getAll($limit) {
        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $result = mysqli_query($conn, "SELECT * FROM teacher LIMIT $limit");
        mysqli_close($conn); 
        return $result;
    }

    public function getParams() {
        return $this->params;
    }
}
?>