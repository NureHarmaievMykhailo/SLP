<?php
    require_once('Model.php');
    require_once('PermissionCode.php');

    class UserModel extends Model {
        protected $table = "user";
        private $id;
        public $permissionCode;

        public $firstName;
        public $lastName;
        public $email;
        public $sex;
        public $birthdate;
        public $registrationDate;
        public $country;
        public $city;
        public $phoneNumber;
        public $pwd;

        public function getId() {
            return $this->id;
        }

        public function toArray() {
            $result = [
                'id'                => $this->id,
                'firstName'         => $this->firstName,
                'lastName'          => $this->lastName,
                'email'             => $this->email,
                'sex'               => $this->sex,
                'birthdate'         => $this->birthdate instanceof DateTime ? $this->birthdate->format('Y-m-d') : $this->birthdate,
                'registrationDate'  => $this->registrationDate instanceof DateTime ? $this->registrationDate->format('Y-m-d') : $this->registrationDate,
                'country'           => $this->country,
                'city'              => $this->city,
                'phoneNumber'       => $this->phoneNumber,
                'pwd'               => $this->pwd,
                'permissionCode'    => $this->permissionCode,
            ];
            return $result;
        }

        public function getFromDBByEmail(string $email, $db = __DATABASE__) {
            $result = $this->getByEmail($email, $db);
            if ($result) {
                $this->id = $result["id"];
                $this->firstName = $result["firstName"];
                $this->lastName = $result["lastName"];
                $this->email = $result["email"];
                $this->sex = $result["sex"];
                // YY-MM-DD format
                $this->birthdate = new DateTime($result["birthdate"]);
                $this->registrationDate = new DateTime($result["registrationDate"]);
                $this->country = $result["country"];
                $this->city = $result["city"];
                $this->phoneNumber = $result["phoneNumber"];
                $this->pwd = $result["pwd"];
                $this->permissionCode = $result["permission"];

                return true;
            }
            return false;
        }

        /**
         * Gets user's data from the database given an email.
         * 
         * @param string $email User's email to be searched for in the DB
         * @return array|false Returns an associative array of values on success and false on failure
         */
        public function getByEmail(string $email, $db = __DATABASE__) {
            $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);

            if ($mysqli->connect_error) {
                return false;
            }

            $sql = "SELECT * FROM $this->table WHERE email = ?";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                $mysqli->close();
                return false;
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $mysqli->close();

            $data = $result ? $result->fetch_assoc() : false;

            return $data;
        }

        //TODO: Allowing calling this method from outside can be a security issue due to permission code.
        //TODO: Look into the problem and work out a solution. Create separated methods for creating moderators?
        /**
         * Inserts a new user into the database.
         *
         * @param string $firstName The first name of the user.
         * @param string $lastName The last name of the user.
         * @param string $email The email address of the user.
         * @param string $sex The sex of the user (e.g., 'male', 'female', 'other').
         * @param DateTime $birthdate The birthdate of the user.
         * @param string $country The country of the user.
         * @param string $city The city of the user.
         * @param string $phoneNumber The phone number of the user.
         * @param string $pwd The password of the user, which will be hashed before storage.
         * @param PermissionCode $permission The permission level of the user. Defaults to PermissionCode::User.
         * @param mixed $db The database name. Defaults to config value.
         * 
         * @return int|false The ID of the newly inserted user on success, or false on failure.
         * 
         */
        public function insertUser(string $firstName, string $lastName, string $email,
            string $sex, DateTime $birthdate, string $country, string $city,
            string $phoneNumber, string $pwd,
            PermissionCode $permission = PermissionCode::User, $db = __DATABASE__)
        {
            $data['firstName'] = $firstName;
            $data['lastName'] = $lastName;
            $data['email'] = $email;
            $data['sex'] = $sex;
            $data['birthdate'] = $birthdate->format('y-m-d');
            $data['country'] = $country;
            $data['city'] = $city;
            $data['phoneNumber'] = $phoneNumber;

            // Hash the password
            $options = [ 'cost' => 12 ];
            $data['pwd'] = password_hash($pwd, PASSWORD_DEFAULT, $options);

            $data['permission'] = $permission->value;

            // Set the registration data as today
            $data['registrationDate'] = date('Y-m-d');

            $inserted_id = Model::insert($data, $db);

            if(!$inserted_id) {
                return false;
            }
            return $inserted_id;
        }

        /**
         * Updates existing user data in the database.
         *
         * @param int $id ID of the user to be updated.
         * @param string $firstName The first name of the user.
         * @param string $lastName The last name of the user.
         * @param string $email The email address of the user.
         * @param string $sex The sex of the user (e.g., 'male', 'female', 'other').
         * @param DateTime $birthdate The birthdate of the user.
         * @param string $country The country of the user.
         * @param string $city The city of the user.
         * @param string $phoneNumber The phone number of the user.
         * @param string $pwd The password of the user, which will be hashed before storage.
         * @param PermissionCode $permission The permission level of the user. Defaults to PermissionCode::User.
         * @param mixed $db The database name. Defaults to config value.
         * 
         * @return bool True on success, false on failure.
         * 
         */
        public function updateUser(int $id, string $firstName, string $lastName, string $email,
            string $sex, DateTime $birthdate, string $country, string $city,
            string $phoneNumber, string $pwd,
            PermissionCode $permission = PermissionCode::User, $db = __DATABASE__)
        {
            $data['firstName'] = $firstName;
            $data['lastName'] = $lastName;
            $data['email'] = $email;
            $data['sex'] = $sex;
            $data['birthdate'] = $birthdate->format('y-m-d');
            $data['country'] = $country;
            $data['city'] = $city;
            $data['phoneNumber'] = $phoneNumber;
            $data['pwd'] = $pwd;
            $data['permission'] = $permission->value;

            return Model::update($id, $data, $db);
        }

        /**
         * Deletes a user from the database.
         * 
         * @param int $id ID of the user to be deleted.
         * @param mixed $db The database name. Defaults to config value.
         * 
         * @return bool True on success, false on failure.
         */
        public function deleteUser(int $id, $db = __DATABASE__) {
            return Model::delete($id, $db);
        }

        public function isValidEmail(string $email) {
            if (!isset($email) || empty($email)) {
                return false;
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
            return false;
        }

        public function isValidPassword(string $password) {
            // Pattern ensures that pwd has uppercase letters, lowercase letters,
            // digits and special characters specified
            // ^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!()*?&`@\/`"'=+$#])$
            $pattern = "~^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!()*?&`@/\"'=+$#]).+$~";
            if (preg_match($pattern, $password)) {
                return true;
            }
            return false;
        }
    }
?>
