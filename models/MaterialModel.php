<?php
require_once('Model.php');
require_once('MaterialCategoryModel.php');

class Material extends Model{
    private $id;
    private $title;
    private $shortInfo;
    private $description;
    private $categories;

    public function getFromDB(int $id) {
        $id = intval($id);
        $result = $this->getById("fu_db", $id, "material")->fetch_assoc();
        $this->id = $result["id"];
        $this->title = $result["title"];
        $this->shortInfo = $result["shortInfo"];
        $this->description = $result["description"];
        $this->categories = $this->getCategoriesById($id);
    }

    public function getCategoriesById(int $material_id) {
        // Ensure that $material_id is an integer;
        $material_id = intval($material_id);

        $query = "SELECT DISTINCT material_category.id, category_name FROM material_category
        JOIN material_material_category
        ON material_material_category.category_id = material_category.id
        JOIN material
        ON material_material_category.material_id = material.id
        WHERE material.id = $material_id;";

        $sql_result = $this->executeSQL("fu_db", $query);
        $result = array();
        while($row = $sql_result->fetch_assoc()) {
            $category = new MaterialCategory();
            $category->setId($row["id"]);
            $category->setCategoryName($row["category_name"]);
            array_push($result, $category);
        }

        return $result;
    }

    /** Gets all Material objects that have a certain category.
     * @param int $category_id the category id 
     * @return mysql mysql response with Materials found.
     */
    public function getAllByCategoryId(int $category_id) {
        $category_id = intval($category_id);

        $query = "SELECT distinct material.id, title, shortInfo, description from material
        join material_material_category
        ON material_material_category.material_id = material.id
        where material_material_category.category_id = $category_id;";

        return $this->executeSQL("fu_db", $query);
    }

    // Getter and setter for $id
    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    // Getter and setter for $title
    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    // Getter and setter for $shortInfo
    public function getShortInfo() {
        return $this->shortInfo;
    }

    public function setShortInfo(string $shortInfo) {
        $this->shortInfo = $shortInfo;
    }

    // Getter and setter for $description
    public function getDescription() {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }

    public function getCategories() {
        return $this->categories;
    }

    /** Sets private property $categories.
     * @param array $categories an array of MaterialCategory objects.
     * @return void
     */
    public function setCategories(array $categories) {
        $this->categories = $categories;
    }
}
?>