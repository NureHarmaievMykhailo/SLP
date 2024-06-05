<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'MySqlDatabaseAdapter.php';
require_once 'PostgreSqlDatabaseAdapter.php';
require_once 'models/PermissionCode.php';

use Google\Client;

// init configuration
$clientID = '787075876039-m4at4ijb4327vu9n4phb0oicfopnji05.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:3000/lb_4_smp/sign_up_smp.php';

// create Client Request to access Google API
$client = new Google\Client();
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => 'C:\Misha NURE\PHP\cacert.pem',
]));
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
$email = '';
$firstName = '';
$lastName = '';
$googlePwd = '';
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        echo 'Error fetching token: ' . htmlspecialchars($token['error']);
        file_put_contents('log.txt', print_r($token, true));
        exit;
    }
    $client->setAccessToken($token['access_token']);

    // get profile info
    $googleService = new Google\Service\Oauth2($client);
    $google_account_info = $googleService->userinfo->get();
    $email = $google_account_info->email;
    $firstName = $google_account_info->givenName;
    $lastName = $google_account_info->familyName;
    $googlePwd = $token['access_token'];

    // Instantiate the database adapter
    $dbAdapter = new MySqlDatabaseAdapter(); // Change to PostgreSqlDatabaseAdapter() for PostgreSQL
    $dbAdapter->connect();

    // Check if the email already exists
    $result = $dbAdapter->query('SELECT COUNT(*) FROM user WHERE email = ?', [$email]);
    $emailExists = $result[0]['COUNT(*)'];

    if ($emailExists) {
        header('Location: homepage.php');
        exit;
    } else {
        $dbAdapter->query('INSERT INTO user (email, firstName, lastName, pwd, permission) VALUES (?, ?, ?, ?, ?)', [$email, $firstName, $lastName, $googlePwd, PermissionCode::User->value]);
        header('Location: homepage.php');
        exit;
    }

    $dbAdapter->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/sign_up.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/signUp.js"></script>
</head>
<body>
    <div class="sign_up_main">
        <div class="sign_up_block">
            <div class="header_div">
                <h class="header">Зареєструватися</h>
            </div>
            <div class="profile_pic_div">
                <img src="../public/images/user_profile.svg" style="width: 100%; height: auto;">
            </div>
            <div class="first_block_div">
                <input id="firstNameInput" class="sign_up_input" placeholder=" Ім'я" value="<?php echo htmlspecialchars($firstName); ?>">
                <input id="lastNameInput" class="sign_up_input" placeholder=" Прізвище" value="<?php echo htmlspecialchars($lastName); ?>">
                <input id="emailInput" required class="sign_up_input" type="email" placeholder=" Електронна пошта" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="second_block_left">
                <select id="sexInput" class="sign_up_input">
                    <option value="" disabled selected>Стать</option>
                    <option value="male">Чоловіча</option>
                    <option value="female">Жіноча</option>
                    <option value="other">Інше</option>
                </select>
                <input id="countryInput" class="sign_up_input" placeholder=" Країна">
            </div>
            <div class="second_block_right">
                <input id="birthdateInput" type="date" class="sign_up_input" placeholder=" Дата народження">
                <input id="cityInput" class="sign_up_input" placeholder=" Місто">
            </div>
            <div class="third_block_div">
                <div id="errorDiv" class="text_default sign_up_error_div"></div>
                <input id="phoneNumberInput" type="tel" class="sign_up_input" placeholder=" Номер телефону">
                <input id="pwdInput" required type="password" class="sign_up_input" placeholder=" Пароль" value="<?php echo htmlspecialchars($googlePwd); ?>">
                <input id="pwdConfirmInput" required type="password" class="sign_up_input" placeholder=" Повторити пароль" value="<?php echo htmlspecialchars($googlePwd); ?>">
            </div>
            <div class="sign_up_btn_div">
                <a href="<?php echo $client->createAuthUrl() ?>" class="sign_up_btn text_default noselect sign_up_google_btn">
                    <img style="padding-left:10px;height: 50%; width: auto;" src="../pages/images/sign_up/u89.png">
                    <p>Зареєструватися з допомогою Google</p>
                </a>
                <button class="sign_up_btn text_default noselect" onclick="signUp();">Зареєструватися</button>
            </div>
            <div class="log_in_btn_div">
                <p class="text_default noselect">Вже маєте акаунт? <a href="login" class="link_hidden" style="font-weight: bold;">Увійти</a></p>
            </div>
        </div>
    </div>
</body>
</html>
