<?php
require_once('Model.php');
require_once('MaterialCategoryModel.php');
require_once('Material-MaterialCategoryModel.php');

class Material extends Model {
    private $id;
    private $title;
    private $shortInfo;
    private $description;
    private $categories = [];
    protected $table = "material";

    public function getFromDB(int $id) {
        $id = intval($id);
        if(!$result = $this->getById($id)->fetch_assoc()) {
            return NULL;
        }

        $this->id = $result["id"];
        $this->title = $result["title"];
        $this->shortInfo = $result["shortInfo"];
        $this->description = $result["description"];
        $this->categories = $this->getCategoriesById($id);
    }

    /**
     * Retrieves all records from the database table based on a partial match of the title.
     *
     * @param string $title The partial title to search for.
     * @return mixed Returns the result set containing all matching records on success, or false on failure.
     */
    public function getAllByTitle($title) {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        // In MySQL queries are case insensitive by default, hence no need for LOWER() or UPPER()
        $query = "SELECT * FROM $this->table WHERE title LIKE CONCAT('%', ?, '%')";

        if (!($stmt = $mysqli->prepare($query))) {
            $mysqli->close();
            return false;
        }
        $stmt->bind_param("s", $title);

        if(!$stmt->execute()) {
            return false;
        }

        $result = $stmt->get_result();
        $stmt->close();
        $mysqli->close();
        return $result;
    }

    /**
     * Converts a material object to an associative array, along with each of its categories.
     * @return array
     */
    public function toArray() {
        $categoriesArray = [];
        foreach ($this->categories as $category) {
            array_push($categoriesArray, $category->toArray());
        }

        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "shortInfo"=>$this->shortInfo,
            "description"=>$this->description,
            "categories"=>$categoriesArray
        ];
    }

    /**
     * Insert a new material row from a prepared associative array of data.
     * 
     * @param array $material_data Associative array of Material properties.
     * @param array $material_categories An array of category IDs to associate with the new material.
     * @param string $db Database to be used. Defaults to config value.
     * @return int|bool Returns newly inserted id on success, false on failure.
     */
    protected function insertMaterialFromArray(array $material_data, array $material_categories = [], string $db = __DATABASE__) {
        $inserted_id = Model::insert($material_data, $db);

        if(!$inserted_id) {
            return false;
        }

        // If no categories were provided.
        if (empty($material_categories)) {
            return true;
        }

        $m = new MaterialMaterialCategory;
        //turn into an associative array material_id =>(1, 1, 1, 1, 1), category_id =>(1, 2, 3, 4)
        $categories_assoc = [];
        foreach ($material_categories as $cat_id) {
            array_push($categories_assoc, array("material_id"=>$inserted_id, "category_id"=>$cat_id));
        }

        $res = $m->insert($categories_assoc);
        if($res) {
            return $inserted_id;
        }
        return $res;
    }

    /**
     * Insert a new material row.
     * 
     * @param string $title Title of the material to be inserted.
     * @param string $shortInfo Short info of the material to be inserted.
     * @param string $description Full description of the material to be inserted.
     * @param array $material_categories Array of associative arrays of MaterialCategories ids.
     * @param string $db Database to be used. Defaults to config value.
     * @return int|bool Returns newly inserted id on success, false on failure.
     */
    public function insertMaterial(string $title, string $shortInfo, string $description, array $material_categories = [], string $db = __DATABASE__) {
        $material_data["title"] = $title;
        $material_data["shortInfo"] = $shortInfo;
        $material_data["description"] = $description;
        return $this->insertMaterialFromArray($material_data, $material_categories, $db);
    }

    /**
     * Deletes a material row, as well as all its bindings to any categories.
     * 
     * @param int $material_id Id of the material to be deleted.
     * @param string $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    public function delete(int $material_id, string $db = __DATABASE__) {
        if(!Model::delete($material_id, $db)) {
            return false;
        }
        $m = new MaterialMaterialCategory;
        return $m->deleteByMaterialId($material_id);
    }

    /**
     * Update a material row from a prepared associative array of data.
     * 
     * @param int $material_id Id of the material to be updated.
     * @param array $material_data Associative array of Material properties.
     * @param array $material_categories Array of MaterialCategories ids.
     * @param string $db Database to be used. Defaults to config value.
     * 
     * @return int|bool Returns updated id on success, false on failure.
     */
    protected function updateMaterialFromArray(int $material_id, array $material_data, array $category_ids = [], string $db = __DATABASE__) {
        $inserted_id = Model::update($material_id, $material_data, $db);
        if(!$inserted_id) {
            return false;
        }

        $m = new MaterialMaterialCategory;

        if ($m->updateByMaterialId($material_id, $category_ids)) {
            return $material_id;
        }
        return false;
    }

    /**
     * Update a material row.
     * 
     * @param int $material_id Id of the material to be updated.
     * @param string $new_title The new title of the material to be updated.
     * @param string $new_shortInfo The new short info of the material to be updated.
     * @param string $new_description The new full description of the material to be updated.
     * @param array $new_material_ids Array of associative arrays of MaterialCategories ids.
     * @param string $db Database to be used. Defaults to config value.
     * 
     * @return int|bool Returns updated id on success, false on failure.
     */
    public function updateMaterial(int $material_id, string $new_title, string $new_shortInfo, string $new_description, array $new_category_ids = [], string $db = __DATABASE__) {
        $new_material_data = array("title"=>$new_title, "shortInfo"=>$new_shortInfo, "description"=>$new_description);
        return $this->updateMaterialFromArray($material_id, $new_material_data, $new_category_ids, $db);
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