<?php
// Завдання 1: створити клас Users з властивостями name, login, password та три об’єкти цього класу з довільними значеннями
class Users {
    public $name;
    public $login;
    public $password;

    // Завдання 2: описати метод getInfo()
    public function getInfo() {
        echo "Name: $this->name, Login: $this->login, Password: $this->password\n";
    }

    // Завдання 3: конструктор класу Users
    public function __construct($name, $login, $password) {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
    }

    // Завдання 4: метод __clone()
    public function __clone() {
        $this->name = "User";
        $this->login = "User";
        $this->password = "qwerty";
    }
}

$user1 = new Users("Alice", "alice123", "pass123");
$user2 = new Users("Bob", "bob456", "pass456");
$user3 = new Users("Charlie", "charlie789", "pass789");

// Виклик методу getInfo() для кожного об’єкта
$user1->getInfo();
$user2->getInfo();
$user3->getInfo();

$user4 = clone $user1;
$user4->getInfo(); // Значення за замовчуванням: User, User, qwerty

// Завдання 5: описати клас SuperUsers, успадкований від класу Users
class SuperUsers extends Users {
    public $character;

    // Завдання 6: описати конструктор класу SuperUsers
    public function __construct($name, $login, $password, $character) {
        parent::__construct($name, $login, $password);
        $this->character = $character;
    }

    // Перевизначення методу getInfo() для класу SuperUsers
    public function getInfo() {
        parent::getInfo();
        echo "Character: $this->character\n";
    }
}

// Створення об’єкта класу SuperUsers та задання значення властивості character
$superUser = new SuperUsers("Dave", "daveAdmin", "adminPass", "admin");

$superUser->getInfo();

// Окремий вивід значення властивості character
echo "Character: $superUser->character\n";
?>
