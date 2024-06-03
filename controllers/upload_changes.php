<?php
session_start();?>
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
        $registrationDate = $_SESSION['userData']['registrationDate'];
        $birthdate = new DateTime($birthdate);
        $u = new UserModel;
        $currentPassword = $_SESSION['userData']['pwd'];
        $res = $u->updateUser($id, $firstName, $lastName, $email, $sex, $birthdate, $country, $city, $phoneNumber, $currentPassword);

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
                'phoneNumber' => $phoneNumber,
                'pwd' => $currentPassword,
                'registrationDate' => $registrationDate
            ];
            $this->responseArray['success'] = true;
            return json_encode($this->responseArray);
        }

        $this->responseArray['errors']['queryError'] = "Failed to update profile.";
        return json_encode($this->responseArray);
    }
}
?>



