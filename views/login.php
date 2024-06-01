<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'models/PermissionCode.php';

use Google\Client;

// init configuration
$clientID = '787075876039-1ephfrid8rg86q1aql0d56lc0n70nnbh.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:3000/views/login.php';

// create Client Request to access Google API
$client = new Google\Client();
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => 'C:\OSPanel\modules\PHP-8.2\PHP\cacert.pem',
]));
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
$email = '';
$name = '';
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
    $name = $google_account_info->name;
    $firstName = $google_account_info->givenName;
    $lastName = $google_account_info->familyName;
    $googlePwd = $token['access_token'];

    try {
        $dsn = "mysql:host=" . __HOSTNAME__ . ";dbname=" . __DATABASE__ . ";charset=utf8mb4";
        $pdo = new PDO($dsn, __USERNAME__, __PASSWORD__, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM user WHERE email = ?');
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            header('Location: homepage.php');
            exit;
        } else {
            $stmt = $pdo->prepare('INSERT INTO user (email, firstName, lastName, pwd, permission) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$email, $firstName, $lastName, $googlePwd, PermissionCode::User->value]);
            header('Location: homepage.php');
            exit;
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="../public/log_in.css" type="text/css" rel="stylesheet"/>
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/logIn.js"></script>
</head>
<body>
    <div class="sign_up_main">
        <div class="log_in_block">
            <div class="header" style="font-size: 38px;">
                <h>Увійти</h>
            </div>
            <div id="errorDiv" class="sign_up_error_div text_default"></div>
            <input id="emailInput" class="sign_up_input" placeholder=" Електронна пошта" style="padding: 6px;" value="<?php echo htmlspecialchars($email); ?>">
            <input id="pwdInput" class="sign_up_input" type="password" placeholder=" Пароль" style="padding: 6px;" value="<?php echo htmlspecialchars($googlePwd); ?>">
            <a href="" class="link_hidden noselect text_default">Забули пароль?</a>
            <button class="sign_up_btn text_default noselect" style="width: 100% !important;" onclick="logIn();">Увійти</button>
            <a href="<?php echo $client->createAuthUrl() ?>" class="sign_up_btn text_default noselect" style="width: 100% !important;">
                <img style="padding-right:10px;height: 50%; width: auto;" src="../pages/images/sign_up/u89.png">
                <p>Увійти з допомогою Google</p>
            </a>
            <p class="text_default">Не маєте акаунт? <a href="sign_up" class="link_hidden noselect" style="font-weight: bolder;">Зареєструватися</a></p>
        </div>
    </div>
</body>
</html>
