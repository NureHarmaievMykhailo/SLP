<?php
    require_once('Controller.php');
    require_once('../models/UserModel.php');

    class LogInController extends Controller {
        private $responseArray = [
            'errors'=> [
                'logInError'=>NULL,
                'queryError'=>NULL
            ]
        ];

        public function getResponseArray() {
            return $this->responseArray;
        }

        public function logInUser(string $email, string $pwd) {
            $u = new UserModel;
            if($userData = $u->getByEmail($email)) {
                if(password_verify($pwd, $userData['pwd'])) {
                    // Generate and set new session ID
                    $newSessionId = session_create_id() . "-" . $userData['id'];
                    session_id($newSessionId);

                    session_start();

                    $_SESSION['loggedIn'] = true;
                    $_SESSION['last_ping'] = time();
                    $_SESSION['permission'] = intval($userData['permission']);
                    $_SESSION['userData'] = $userData;

                    $this->responseArray['redirect'] = '../';
                    return json_encode($this->responseArray);
                }
                else {
                    $this->responseArray['errors']['logInError'] = 'Email or password incorrect';
                    return json_encode($this->responseArray);
                }
            }
            else {
                $this->responseArray['errors']['logInError'] = 'Email not found.';
                return  json_encode($this->responseArray);
            }
        }

        public function logOut() {
            session_start();
            session_unset();
            session_destroy();
        }
    }
?>