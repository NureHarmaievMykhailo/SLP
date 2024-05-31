<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/TeacherModel.php");

class TeacherController extends Controller {

    public function __construct() {
        $this->params = $this->parseParams();
    }

    /**
     * Maps the SQL query result to an array of Teacher objects.
     *
     * @param mysqli_result $sql_response The result set obtained from executing a SQL query.
     * @return string An array of Teacher objects representing the mapped SQL response.
     */
    private function sqlResponseToJson($sql_response) {
        $res = [];
        while($row = $sql_response->fetch_assoc()) {
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

    public function getTeacherById($id) {
        $teacher = new Teacher;
        $teacher->getFromDB($id);
        return $teacher;
    }

    public function insertTeacher(string $name, string $shortInfo, int $price, string $description, string $education, string $experience, mixed $imageURI) {
        $t = new Teacher;
        if ($res = $t->insertTeacher($name, $shortInfo, intval($price), $description, $education, $experience, $imageURI)) {
            return json_encode(['status' => 'success', 'id' => $res]);
        } else {
            return json_encode(['status' => 'error']);
        }
    }


    public function updateTeacher(int $id, string $name, string $shortInfo, int $price, string $description, string $education, string $experience, mixed $imageURI) {
        $t = new Teacher;
        if ($t->updateTeacher(intval($id), $name, $shortInfo, intval($price), $description, $education, $experience, $imageURI)) {
            return json_encode(['status' => 'success', 'id' => $id]);
        } else {
            return json_encode(['status' => 'error']);
        }
    }

    public function deleteTeacher(int $id)
    {
        $t = new Teacher;
        if ($t->deleteTeacher(intval($id))) {
            return json_encode(['status' => 'success', 'id' => $id]);
        } else {
            return json_encode(['status' => 'error']);
        }
    }

    public function getTeacherJsonByName($name) {
        $t = new Teacher;
        return $t->getJsonByName($name);
    }
    
    public function getTeacherJsonById($id) {
        $t = new Teacher;
        return $t->getByIdAsJson($id);
    }

    public function getAll($limit) {
        $t = new Teacher;
        return $t->getAllTeachers($limit);
    }

    public function getAllAsJson($limit) {
        $t = new Teacher;
        return $t->getAllAsJson($limit);
    }

    public function getByPriceAsc($limit) {
        $t = new Teacher;
        $rawData = $t->sortByPrice($limit, __DATABASE__);
        return $this->sqlResponseToJson($rawData);
    }

    public function getByPriceDesc($limit) {
        $t = new Teacher;
        $rawData = $t->sortByPrice($limit, __DATABASE__, false);
        return $this->sqlResponseToJson($rawData);
    }
}
?>