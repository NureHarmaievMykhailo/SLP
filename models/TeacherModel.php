<?php
require_once('Model.php');
class Teacher extends Model
{
    private $id;
    private $name;
    private $price;
    private $shortInfo;
    private $description;
    private $experience;
    private $education;
    private $imageURI;
    protected $table = "teacher";

    private function sqlResponseToJson($sql_response)
    {
        $res = [];
        while ($row = $sql_response->fetch_assoc()) {
            array_push($res, array(
                'id' => $row["id"],
                'name' => $row["teacher_name"],
                'price' => $row["price"],
                'shortInfo' => $row["shortInfo"],
                'description' => $row["description"],
                'experience' => $row["experience"],
                'education' => $row["education"],
                'imageURI' => $row["imageURI"]
            ));
        }
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'shortInfo' => $this->shortInfo,
            'description' => $this->description,
            'experience' => $this->experience,
            'education' => $this->education,
            'imageURI' => $this->imageURI
        ];
    }

    public function getFromDB($id)
    {
        $result = $this->getById($id)->fetch_assoc();
        $this->id = $result["id"];
        $this->name = $result["teacher_name"];
        $this->price = $result["price"];
        $this->shortInfo = $result["shortInfo"];
        $this->description = $result["description"];
        $this->experience = $result['experience'];
        $this->education = $result['education'];
        $this->imageURI = $result["imageURI"];
    }

    public function getByName($name) {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }
        $sql = "SELECT * FROM $this->table WHERE teacher_name LIKE CONCAT('%', ?, '%')";
        $stmt = $mysqli->prepare($sql);
        if(!$stmt->bind_param("s", $name)) {
            return false;
        }
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    public function insertTeacher(string $name, string $shortInfo, int $price, string $description, string $education, string $experience, string $imageURI, $db = __DATABASE__) {
        $teacherData = [
            'teacher_name'=>$name,
            'shortInfo'=>$shortInfo,
            'price'=>$price,
            'description'=>$description,
            'education'=>$education,
            'experience'=>$experience,
            'imageURI'=>$imageURI
        ];

        $inserted_id = Model::insert($teacherData, $db);
        return $inserted_id;
    }

    public function deleteTeacher(int $id) {
        return Model::delete($id);
    }

    public function updateTeacher(int $id, string $name, string $shortInfo, int $price, string $description, string $education, string $experience, string $imageURI, $db = __DATABASE__) {
        $teacherData = [
            'teacher_name'=>$name,
            'shortInfo'=>$shortInfo,
            'price'=>$price,
            'description'=>$description,
            'education'=>$education,
            'experience'=>$experience,
            'imageURI'=>$imageURI
        ];

        $inserted_id = Model::update($id, $teacherData, $db);
        return $inserted_id;
    }

    public function getJsonByName($name) {
        return $this->sqlResponseToJson($this->getByName($name));
    }

    public function getByIdAsJson($id) {
        return $this->sqlResponseToJson($this->getById($id));
    }

    public function getAllAsJson($limit)
    {
        return $this->sqlResponseToJson($this->getAllTeachers($limit));
    }

    public function getAllTeachers($limit)
    {
        return Model::getAll(__DATABASE__, $this->table, $limit);
    }

    public function sortByPrice($limit, $db, $ascending = true)
    {
        $order = ($ascending) ? "ASC" : "DESC";
        $sql = "SELECT * FROM $this->table ORDER BY price $order LIMIT ? ;";

        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);
        if ($mysqli->connect_error) {
            throw new Exception("Failed to connect to the database.");
        }
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $limit);
        if (!$stmt->execute()) {
            return false;
        }
        $res = $stmt->get_result();
        $stmt->close();
        $mysqli->close();

        return $res;
    }

    // Getter and Setter for $id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter and Setter for $name
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // Getter and Setter for $price
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    // Getter and Setter for $shortInfo
    public function getShortInfo()
    {
        return $this->shortInfo;
    }

    public function setShortInfo($shortInfo)
    {
        $this->shortInfo = $shortInfo;
    }

    // Getter and Setter for $description
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    // Getter and Setter for $imageURI
    public function getImageURI()
    {
        return $this->imageURI;
    }

    public function setImageURI($imageURI)
    {
        $this->imageURI = $imageURI;
    }

    public function getExperience()
    {
        return $this->experience;
    }

    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    public function getEducation()
    {
        return $this->education;
    }

    public function setEducation($education)
    {
        $this->education = $education;
    }
}
