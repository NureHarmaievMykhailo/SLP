<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/MaterialModel.php");

class LearningMaterialController extends Controller {
    public function __construct() {
        $this->params = $this->parseParams();
    }

    private function mapSQLResponseToMaterial($sql_response) {
        $result = array();
        while($row = $sql_response->fetch_assoc()) {
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

    public function getMaterialById($id) {
        $material = new Material;
        $material->getFromDB(intval($id));
        return $material;
    }

    /**
     * Gets a material with a specified ID from the DB and returns it as a JSON.
     * @return json
     */
    public function getMaterialJsonById($id) {
        $material = $this->getMaterialById($id);
        return json_encode([$material->toArray()], JSON_UNESCAPED_UNICODE);
    }

    public function getMaterialsByTitle($title) {
        $m = new Material;
        $sql_result = $m->getAllByTitle($title);
        return $this->mapSQLResponseToMaterial($sql_result);
    }

    public function getMaterialsJsonByTitle($title) {
        $materials = $this->getMaterialsByTitle($title);

        // If response if empty, return empty array.
        if ($materials == []) {
            return json_encode($materials);
        }

        $materials_array = array_map(function($m) {
                return $m->toArray();
            }, $materials);

        // Return as is
        return json_encode($materials_array, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Updates the details of a material and updates its associated categories.
     *
     * This function updates the title, short information, and description of a material identified by its ID.
     * Additionally, it updates the categories associated with the material.
     * If no category IDs are provided, all material's categories will be deleted.
     *
     * @param int $material_id The ID of the material to be updated.
     * @param string $new_title The new title for the material.
     * @param string $new_shortInfo The new short information for the material.
     * @param string $new_description The new description for the material.
     * @param array $new_category_ids An array of new category IDs to associate with the material. Defaults to an empty array.
     *
     * @return int|bool Returns updated id on success, false on failure.
     */
    public function updateMaterial(int $material_id, string $new_title, string $new_shortInfo, string $new_description, array $new_category_ids = []) {
        $m = new Material;
        try {
            return $m->updateMaterial($material_id, $new_title, $new_shortInfo, $new_description, $new_category_ids);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Inserts a new material into the DB along with its associated categories.
     *
     * This function inserts a new material with the provided title, short information, and description.
     * It can associate the new material with specified categories if category IDs are provided.
     *
     * @param string $title The title of the new material.
     * @param string $shortInfo The short information about the new material.
     * @param string $description The description of the new material.
     * @param array $material_categories Optional. An array of category IDs to associate with the new material. Defaults to an empty array.
     *
     * @return int|bool Returns newly inserted id on success, false on failure.
     */
    public function insertMaterial(string $title, string $shortInfo, string $description, array $material_categories = []) {
        $m = new Material;
        try {
            return $m->insertMaterial($title, $shortInfo, $description, $material_categories);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Deletes a material from the DB, as well as all its bindings to any categories.
     *
     * @param int $material_id The ID of the material to be deleted.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function deleteMaterial(int $material_id) {
        $m = new Material;
        try {
            return $m->delete($material_id);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Gets a specified number of Materials and outputs them as a JSON.
     * @param int $limit the number of Material to be gotten.
     * @return json an array of Material objects
     */
    public function getAllAsJson(int $limit) {
        $data = $this->getAll($limit);

        // Create an array toArray results of each material
        $result = array_map(function($material) {
            return $material->toArray();
        }, $data);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Gets a specified number of Materials 
     * @param int $limit the number of Material to be gotten.
     * @return array an array of Material objects
     */
    public function getAll(int $limit) {
        $result = array();

        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $sql_result = mysqli_query($conn, "SELECT * FROM material LIMIT $limit");
        mysqli_close($conn); 
        $result = $this->mapSQLResponseToMaterial($sql_result);
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
     * @return array Array of Material objects.
     */
    public function getMaterialsByCategoryId(int $category_id) {
        $result = array();
        // Initialize Material instance. Solely to call getAllByCategoryId().
        $m = new Material;
        $sql_result_materials = $m->getAllByCategoryId($category_id);
        $result = $this->mapSQLResponseToMaterial($sql_result_materials);
        return $result;
    }

    public function getCategoryName(int $category_id) {
        $cat = new MaterialCategory;
        $cat->getFromDB($category_id);
        return $cat->getCategoryName();
    }

    /** Retrieves all categories from DB until a limit.
     * @param int $limit limit of categories to be retrieved from DB.
     * @return array Array of MaterialCategory objects
     */
    public function getAllCategories(int $limit = 100) {
        $result = [];

        $c = new MaterialCategory;
        $categories = $c->getAllCategories($limit);
        while ($row = $categories->fetch_assoc()) {
            $category = new MaterialCategory;
            $category->setId($row["id"]);
            $category->setCategoryName($row["category_name"]);
            array_push($result, $category);
        }

        return $result;
    }
}
?>