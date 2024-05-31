<?php
require_once('Controller.php');
require_once('../models/UserModel.php');

class UploadController extends Controller {
    private $responseArray = [
        'errors' => [
            'emailError' => NULL,
            'queryError' => NULL
        ]
    ];

    public function getResponseArray() {
        return $this->responseArray;
    }

    private function validateEmail($email) {
        $u = new UserModel;
        if (!$u->isValidEmail($email)) {
            $this->responseArray['errors']['emailError'] = "Invalid email.";
            return false;
        }
        return true;
    }

    public function updateProfile($id, $firstName, $lastName, $email, $sex, $birthdate, $country, $city, $phoneNumber) {
        if (!$this->validateEmail($email)) {
            return json_encode($this->responseArray);
        }

        $birthdate = new DateTime($birthdate);
        $u = new UserModel;

        $res = $u->updateUser($id, $firstName, $lastName, $email, $sex, $birthdate->format('Y-m-d'), $country, $city, $phoneNumber);

        if ($res) {
            $_SESSION['userData'] = [
                'id' => $id,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'sex' => $sex,
                'country' => $country,
                'birthdate' => $birthdate->format('Y-m-d'),
                'city' => $city,
                'phoneNumber' => $phoneNumber
            ];
            $this->responseArray['success'] = true;
            return json_encode($this->responseArray);
        }

        $this->responseArray['errors']['queryError'] = "Failed to update profile.";
        return json_encode($this->responseArray);
    }
}
?>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['userData']['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $country = $_POST['country'];
    $birthdate = $_POST['birthdate'];
    $city = $_POST['city'];
    $phoneNumber = $_POST['phoneNumber'];

    $controller = new UploadController();
    echo $controller->updateProfile($id, $firstName, $lastName, $email, $sex, $birthdate, $country, $city, $phoneNumber);
}
?>

