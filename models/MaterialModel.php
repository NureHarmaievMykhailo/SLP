<?php
require_once('Model.php');
class Material extends Model{
    private $id;
    private $title;
    private $shortInfo;
    private $description;

    public function getFromDB($id) {
        $result = $this->getById("fu_db", $id, "material")->fetch_assoc();
        $this->id = $result["id"];
        $this->title = $result["title"];
        $this->shortInfo = $result["shortInfo"];
        $this->description = $result["description"];
    }

    // Getter and setter for $id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and setter for $title
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    // Getter and setter for $shortInfo
    public function getShortInfo() {
        return $this->shortInfo;
    }

    public function setShortInfo($shortInfo) {
        $this->shortInfo = $shortInfo;
    }

    // Getter and setter for $description
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}
?>