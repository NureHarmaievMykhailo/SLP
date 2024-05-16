<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/MaterialModel.php");

class LearningMaterialController extends Controller {
    public function __construct() {
        $this->params = $this->parseParams();
    }

    public function getMaterialById($id) {
        $material = new Material;
        $material->getFromDB($id);
        return $material;
    }

    public function getAll($limit) {
        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $result = mysqli_query($conn, "SELECT * FROM material LIMIT $limit");
        mysqli_close($conn); 
        return $result;
    }
}
?>