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

                    $redirectLink = '../';
                    switch ($_SESSION['permission']) {
                        case PermissionCode::User->value:
                            $redirectLink = '../';
                            break;
                        case PermissionCode::Moderator->value:
                            $redirectLink = "../moderator-home";
                            break;
                        case PermissionCode::Admin->value:
                            $redirectLink = "../user_control_panel";
                            break;
                    }

                    $this->responseArray['redirect'] = $redirectLink;
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