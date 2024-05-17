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

    /**
     * Gets a specified number of Materials 
     * @param int $limit the number of Material to be gotten.
     * @return mysql response
     */
    public function getAll(int $limit) {
        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $result = mysqli_query($conn, "SELECT * FROM material LIMIT $limit");
        mysqli_close($conn); 
        return $result;
    }

    /**
     * Gets all categories that a Material has, provided with its id.
     * @param int $id id of the Material instance.
     * @return array an array of MaterialCategory objects.
     */
    public function getCategoriesByMaterialId(int $id) {
        $material = new Material;
        return $material->getCategoriesById($id);
    }

    /**
     * Gets an array of Material objects provided with a category id.
     * @param int $category_id id of the category that every Material MUST have.
     * @return array of Material objects.
     */
    public function getMaterialsByCategoryId(int $category_id) {
        $result = array();
        // Initialize Material instance. Solely to call getAllByCategoryId().
        $m = new Material;
        $materials = $m->getAllByCategoryId($category_id);

        while($row = $materials->fetch_assoc()) {
            $material = new Material;
            $id = $row["id"];

            $material->setId($id);
            $material->setTitle($row["title"]);
            $material->setShortInfo($row["shortInfo"]);
            $material->setDescription($row["description"]);
            // Set array of categories for the material
            $material->setCategories($material->getCategoriesById($id));
            array_push($result, $material);
        }
        return $result;
    }
}
?>