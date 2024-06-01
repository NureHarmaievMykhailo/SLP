<?php
$root = __DIR__ . "/..";
require_once('Controller.php');
require_once("$root/models/Admin-UserModel.php");

class UserController extends Controller {
    public function __construct() {
        $this->params = $this->parseParams();
    }

    private function mapSQLResponseToUser($sql_response) {
        $result = array();
        while($row = $sql_response->fetch_assoc()) {
            $user = new UserModel;
            $id = $row["id"];

            $user->setId($id);
            $user->setFirstName($row["firstName"] ?? '');
            $user->setLastName($row["lastName"] ?? '');
            $user->setEmail($row["email"] ?? '');
            $user->setSex($row["sex"] ?? '');
            $user->setBirthdate($row["birthdate"] ?? '');
            $user->setRegistrationDate($row["registrationDate"] ?? '');
            $user->setCountry($row["country"] ?? '');
            $user->setCity($row["city"] ?? '');
            $user->setPhoneNumber($row["phoneNumber"] ?? '');
            $user->setPassword($row["pwd"] ?? '');
            $user->setPermission($row["permission"] ?? '');
            array_push($result, $user);
        }
        return $result;
    }

    public function getUserById($id) {
        $user = new UserModel;
        $user->getFromDB(intval($id));
        return $user;
    }

    public function getMaterialJsonById($id) {
        $user = $this->getUserById($id);
        return json_encode([$user->toAdminArray()], JSON_UNESCAPED_UNICODE);
    }

    protected function getUsersByQuery($query) {
        $u = new UserModel;
        $sql_result = $u->getAllByQuery($query);
        return $this->mapSQLResponseToUser($sql_result);
    }

    public function getUsersJsonByQuery($query) {
        $users = $this->getUsersByQuery($query);

        if($users == []){
            return json_encode($users);
        }
        
        $users_array = array_map(function($u) {
            return $u->toAdminArray();
        }, $users);
        return json_encode($users_array, JSON_UNESCAPED_UNICODE);
    }

    protected function getUsersByEmail($query) {
        $u = new UserModel;
        $sql_result = $u->getAllByEmail($query);
        return $this->mapSQLResponseToUser($sql_result);
    }

    public function getUsersJsonByEmail($query) {
        $users = $this->getUsersByEmail($query);

        if($users == []){
            return json_encode($users);
        }
        
        $users_array = array_map(function($u) {
            return $u->toAdminArray();
        }, $users);
        return json_encode($users_array, JSON_UNESCAPED_UNICODE);
    }

    public function deleteUser(int $user_id) {
        $u = new UserModel;
        try {
            return $u->deleteUser($user_id);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllAsJson(int $limit) {
        $data = $this->getAll($limit);

        // Create an array toAdminArray results of each material
        $result = array_map(function($user) {
            return $user->toAdminArray();
        }, $data);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getAll(int $limit) {
        $result = array();

        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $sql_result = mysqli_query($conn, "SELECT * FROM user WHERE permission = 1 LIMIT $limit");
        mysqli_close($conn); 
        $result = $this->mapSQLResponseToUser($sql_result);
        return $result;
    }

    public function getAllModeratorAsJson(int $limit) {
        $data = $this->getAllModerator($limit);

        // Create an array toAdminArray results of each material
        $result = array_map(function($user) {
            return $user->toAdminArray();
        }, $data);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getAllModerator(int $limit) {
        $result = array();

        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE fu_db;");
        $sql_result = mysqli_query($conn, "SELECT * FROM user WHERE permission = 2 LIMIT $limit");
        mysqli_close($conn); 
        $result = $this->mapSQLResponseToUser($sql_result);
        return $result;
    }

    public function insertModerator(string $firstName, string $lastName, string $email, string $password) {
        $u = new UserModel;
        try {
            return $u->insertModerator($firstName, $lastName, $email, '', '', '', '', '', $password);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
?>