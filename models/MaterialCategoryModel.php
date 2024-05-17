<?php
require_once('Model.php');
class MaterialCategory extends Model{
    private $id;
    private $category_name;

    public function getFromDB($id) {
        $result = $this->getById("fu_db", $id, "material_category")->fetch_assoc();
        $this->id = $result["id"];
        $this->category_name = $result["category_name"];
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