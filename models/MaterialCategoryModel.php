<?php
require_once('Model.php');
class MaterialCategory extends Model {
    private $id;
    private $category_name;
    protected $table = "material_category";

    public function getFromDB($id) {
        $result = $this->getById($id)->fetch_assoc();
        $this->id = $result["id"];
        $this->category_name = $result["category_name"];
    }

    public function getById($id, $db = __DATABASE__) {
        return Model::getById($id, $db);
    }

    public function toArray() {
        return [
            "id"=>$this->id,
            "category_name"=>$this->category_name
        ];
    }

    public function insert(array $data, string $db = __DATABASE__) {
        return Model::insert($data, $db);
    }

    public function delete(int $category_id, string $db = __DATABASE__) {
        return Model::delete($category_id, $db);
    }

    public function update(int $category_id, array $data, string $db = __DATABASE__) {
        return Model::update($category_id, $data, $db);
    }

    public function getAllCategories($limit) {
        return Model::getAll($this->table, $limit);
    }

    // Getter and setter for $id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and setter for $category_name
    public function getCategoryName() {
        return $this->category_name;
    }

    public function setCategoryName($name) {
        $this->category_name = $name;
    }
}
?>