<?php
// Назва файлу XML-документа
$xmlFile = 'users.xml';

// Створення об'єкта DOM
$dom = new DOMDocument('1.0', 'UTF-8');

// Перевірка, чи існує файл XML
if (file_exists($xmlFile)) {
    // Завантаження XML-документа в об'єкт
    $dom->load($xmlFile);
    echo "XML-документ завантажено.\n";
} else {
    // Створення кореневого елемента <users>
    $rootElement = $dom->createElement('users');
    $dom->appendChild($rootElement);
    echo "XML-документ не знайдено, створено новий документ з кореневим елементом <users>.\n";
}

// Отримання кореневого елемента
$rootElement = $dom->documentElement;

// Додавання нового користувача
$newUser = $dom->createElement('user');
$newUser->setAttribute('id', '3');

$firstName = $dom->createElement('firstName', 'Оксана');
$lastName = $dom->createElement('lastName', 'Афанасьєва');
$email = $dom->createElement('email', 'oksana.afanasieva@nure.ua');

$newUser->appendChild($firstName);
$newUser->appendChild($lastName);
$newUser->appendChild($email);

$rootElement->appendChild($newUser);

// Виведення імені кореневого елемента
echo "Кореневий елемент: " . $rootElement->nodeName . "\n";

// Збереження змін в XML-файл
$dom->save($xmlFile);
?>
