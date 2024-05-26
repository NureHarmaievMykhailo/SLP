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

        /* private function regenerateSessionIdLoggedIn() {
            session_regenerate_id(true);

            $userId = $_SESSION['userData']['id'];
            $newSessionId = session_create_id();
            $sessionId = $newSessionId . "_" . $userId;
            session_id($sessionId);

            $_SESSION['lastRegeneration'] = time();
        }

        private function regenerateSessionId() {
            session_regenerate_id();
            $_SESSION['lastRegeneration'] = time();
        } */

        public function logInUser(string $email, string $pwd) {
            session_start();

            $u = new UserModel;
            if($userData = $u->getByEmail($email)) {
                if(password_verify($pwd, $userData['pwd'])) {
                    // Generate and set new session ID
                    $newSessionId = session_create_id() . "_" . $userData['id'];
                    session_id($newSessionId);
    
                    $_SESSION['loggedIn'] = true;
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
    }
?>