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
            $user->setFirstName($row["firstName"]);
            $user->setLastName($row["lastName"]);
            $user->setEmail($row["email"]);
            $user->setSex($row["sex"]);
            $user->setBirthdate($row["birthdate"]);
            $user->setRegistrationDate($row["registrationDate"]);
            $user->setCountry($row["country"]);
            $user->setCity($row["city"]);
            $user->setPhoneNumber($row["phoneNumber"]);
            $user->setPermission($row["permission"]);
            array_push($result, $user);
        }
        return $result;
    }

    protected function getUsersByQuery($query) {
        $u = new UserModel;
        $sql_result = $u->getAllByQuery($query);
        return $this->mapSQLResponseToUser($sql_result);
    }

    public function getAllUsers() {
        $u = new UserModel;
        $sql_result = $u->getAllUsers();
        return $this->mapSQLResponseToUser($sql_result);
    }

    public function getUsersJsonByQuery($query) {
        $users = $this->getUsersByQuery($query);
        $users_array = array_map(function($u) {
            return $u->toArray();
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
            return false;
        }
    }
}
?>