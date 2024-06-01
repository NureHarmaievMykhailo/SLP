<?php
    require_once('Model.php');
    require_once('PermissionCode.php');

    class UserModel extends Model {
        private $id;
        private $firstName;
        private $lastName;
        private $email;
        private $sex;
        private $birthdate;
        private $registrationDate;
        private $country;
        private $city;
        private $phoneNumber;
        private $pwd;
        private $permissionCode;
        protected $table = "user";

        public function getFromDB(int $id) {
            $id = intval($id);
            if (!$result = $this->getById($id)->fetch_assoc()){
                return NULL;
            }

                $this->id = $result["id"];
                $this->firstName = $result["firstName"] ?? '';
                $this->lastName = $result["lastName"] ?? '';
                $this->email = $result["email"] ?? '';
                $this->sex = $result["sex"] ?? '';
                // YY-MM-DD format
                $this->birthdate = $result["birthdate"] ? new DateTime($result["birthdate"]) : null;
                $this->registrationDate = $result["registrationDate"] ? new DateTime($result["registrationDate"]) : null;
                $this->country = $result["country"] ?? '';
                $this->city = $result["city"] ?? '';
                $this->phoneNumber = $result["phoneNumber"] ?? '';
                $this->pwd = $result["pwd"] ?? '';
                $this->permissionCode = $result["permission"] ?? '';
        }

        public function getAllByQuery($title) {
            $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
    
            $query = "SELECT * FROM $this->table WHERE (lastName LIKE CONCAT('%', ?, '%') OR firstName LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%')) AND permission = 1";
    
            if (!($stmt = $mysqli->prepare($query))) {
                $mysqli->close();
                return false;
            }
    
            $stmt->bind_param("sss", $title, $title, $title);
    
            if(!$stmt->execute()) {
                return false;
            }
    
            $result = $stmt->get_result();
            $stmt->close();
            $mysqli->close();
            return $result;
        }

        public function getAllByEmail($title) {
            $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
    
            $query = "SELECT * FROM $this->table WHERE (lastName LIKE CONCAT('%', ?, '%') OR firstName LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%')) AND permission = 2";
    
            if (!($stmt = $mysqli->prepare($query))) {
                $mysqli->close();
                return false;
            }
    
            $stmt->bind_param("sss", $title, $title, $title);
    
            if(!$stmt->execute()) {
                return false;
            }
    
            $result = $stmt->get_result();
            $stmt->close();
            $mysqli->close();
            return $result;
        }

        public function toAdminArray() {
            $result = [
                'id'                => $this->id,
                'firstName'         => $this->firstName,
                'lastName'          => $this->lastName,
                'email'             => $this->email,
                'sex'               => $this->sex,
                'birthdate'         => $this->birthdate instanceof DateTime ? $this->birthdate->format('d-m-Y') : $this->birthdate,
                'registrationDate'  => $this->registrationDate instanceof DateTime ? $this->registrationDate->format('d-m-Y') : $this->registrationDate,
                'country'           => $this->country,
                'city'              => $this->city,
                'phoneNumber'       => $this->phoneNumber,
                'pwd'               => $this->pwd,
                'permissionCode'    => $this->permissionCode,
            ];
            return $result;
        }

        public function deleteUser(int $user_id, $db = __DATABASE__) {
            return Model::delete($user_id, $db);
        }

        public function getId(){
            return $this->id;
        }

        public function setId(int $id){
            $this->id = $id;
        }

        public function getFirstName(){
            return $this->firstName;
        }

        public function setFirstName(string $firstName){
            $this->firstName = $firstName;
        }

        public function getLastName(){
            return $this->lastName;
        }

        public function setLastName(string $lastName){
            $this->lastName = $lastName;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setEmail(string $email){
            $this->email = $email;
        }

        public function getSex(){
            return $this->sex;
        }

        public function setSex(string $sex){
            $this->sex = $sex;
        }

        public function getBirthdate(){
            return $this->birthdate;
        }

        public function setBirthdate(string $birthdate){
            $this->birthdate = $birthdate;
        }

        public function getRegistrationDate(){
            return $this->registrationDate;
        }

        public function setRegistrationDate(string $registrationDate){
            $this->registrationDate = $registrationDate;
        }

        public function getCountry(){
            return $this->country;
        }

        public function setCountry(string $country){
            $this->country = $country;
        }

        public function getCity(){
            return $this->city;
        }

        public function setCity(string $city){
            $this->city = $city;
        }

        public function getPhoneNumber(){
            return $this->phoneNumber;
        }

        public function setPhoneNumber(string $phoneNumber){
            $this->phoneNumber = $phoneNumber;
        }

        public function getPassword(){
            return $this->pwd;
        }

        public function setPassword(string $pwd){
            $this->pwd = $pwd;
        }

        public function getPermission(){
            return $this->permissionCode;
        }

        public function setPermission(string $permission){
            $this->permissionCode = $permission;
        }
    }
?>