<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/TeacherModel.php");

class TeacherController extends Controller {

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
}
?>