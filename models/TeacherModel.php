<?php
require_once('Model.php');
class Teacher extends Model{
    private $id;
    private $name;
    private $price;
    private $shortInfo;
    private $description;
    private $imageURI;
    protected $table = "teacher";

    public function __construct() {

    }

    public function getFromDB($id) {
        $result = $this->getById($id)->fetch_assoc();
        $this->id = $result["id"];
        $this->name = $result["teacher_name"];
        $this->price = $result["price"];
        $this->shortInfo = $result["shortInfo"];
        $this->description = $result["description"];
        $this->imageURI = $result["imageURI"];
    }

    // Getter and Setter for $id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for $name
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    // Getter and Setter for $price
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    // Getter and Setter for $shortInfo
    public function getShortInfo() {
        return $this->shortInfo;
    }

    public function setShortInfo($shortInfo) {
        $this->shortInfo = $shortInfo;
    }

    // Getter and Setter for $description
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    // Getter and Setter for $imageURI
    public function getImageURI() {
        return $this->imageURI;
    }

    public function setImageURI($imageURI) {
        $this->imageURI = $imageURI;
    }
}
?>