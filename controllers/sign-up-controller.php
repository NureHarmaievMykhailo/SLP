<?php
    require_once('Controller.php');
    require_once __DIR__ .'/../models/UserModel.php' ;

    class SignUpController extends Controller {

        private $responseArray = [
            'errors'=> [
                'emailError'=>NULL,
                'passwordError'=>NULL,
                'queryError'=>NULL
            ]
        ];

        public function getResponseArray() {
            return $this->responseArray;
        }

        private function validateEmail(string $email) {
            $u = new UserModel;
            if (!$u->isValidEmail($email)) {
                $this->responseArray['errors']['emailError'] = "Invalid email.";
                return false;
            }
            if($u->getByEmail($email)) {
                $this->responseArray['errors']['emailError'] = "Email is already taken.";
                return false;
            }
            return true;
        }

        private function validatePassword(string $pwd, string $pwdConfirm) {
            if ($pwd != $pwdConfirm) {
                $this->responseArray['errors']['passwordError'] =  "Passwords don't match.";
                return false;
            }
            if (strlen($pwd) < 10) {
                $this->responseArray['errors']['passwordError'] =  "Password is too short. Password must have at least 10 symbols.";
                return false;
            }
            $u = new UserModel;
            if (!$u->isValidPassword($pwd)) {
                $this->responseArray['errors']['passwordError'] =  "Password must have at least 10 symbols, an uppercase letter, "
                    ."a lowercase letter, a digit and a special symbol (i.e. !, =, $, @).";
                return false;
            }
            return true;
        }

        public function signUpUser(string $firstName, string $lastName, string $email,
            string $sex, string $birthdate, string $country, string $city,
            string $phoneNumber, string $pwd, string $pwdConfirm, PermissionCode $permission = PermissionCode::User)
        {
            //TODO: find a better way to handle responseArray
            if (!$this->validateEmail(trim($email)) || !$this->validatePassword(trim($pwd), trim($pwdConfirm))) {
                $msg =  json_encode($this->responseArray);
                // Clear responseArray array to avoid getting wrong positives later.
                unset($responseArray['errors']);
                return $msg;
            }

            $birthdate = new DateTime($birthdate);
            // Set gender to default if nothing provided
            if (empty(trim($sex))) {
                $sex = 'Other';
            }

            $u = new UserModel;
            
            $res = $u->insertUser(trim($firstName), trim($lastName), trim($email), trim($sex), $birthdate, trim($country), trim($city),
            trim($phoneNumber), trim($pwd), $permission);

            if ($res) {
                $this->responseArray['redirect'] = '../login';
                $msg = json_encode($this->responseArray);
                unset($this->responseArray['errors']);
                return $msg;
            }

            $this->responseArray['errors']['queryError'] = "Query error. Couldn't sign up a new user.";
            $msg = json_encode($this->responseArray);
            unset($responseArray['errors']);
            return $msg;
        }
    }
?>
