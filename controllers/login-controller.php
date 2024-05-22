<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Перевірка, чи дані існують в сесії
    if (isset($_SESSION['registration_data'])) {
        $storedEmail = $_SESSION['registration_data']['email'];
        $storedPassword = $_SESSION['registration_data']['password'];

        // Верифікація введених даних
        if ($email === $storedEmail && $password === $storedPassword) {
            header("Location: homepage.html");
            exit();
        } else {
            echo "Невірна електронна пошта або пароль.";
        }
    } else {
        echo "Користувач не зареєстрований.";
    }
}
?>
