<?php
interface PaymentStrategy {
    public function pay($amount, $description);
}

class WayForPayStrategy implements PaymentStrategy {
    private $merchantAccount;
    private $merchantDomainName;
    private $merchantSecretKey;
    private $wayForPayUrl;

    public function __construct($merchantAccount, $merchantDomainName, $merchantSecretKey) {
        $this->merchantAccount = $merchantAccount;
        $this->merchantDomainName = $merchantDomainName;
        $this->merchantSecretKey = $merchantSecretKey;
        $this->wayForPayUrl = 'https://secure.wayforpay.com/pay';
    }

    public function pay($amount, $description) {
        $orderReference = uniqid('ORDER_');
        $orderDate = time();
        $currency = 'UAH';
        $productName = [$description];
        $productPrice = [$amount];
        $productCount = [1];

        $signString = $this->merchantAccount . ';' .
                      $this->merchantDomainName . ';' .
                      $orderReference . ';' .
                      $orderDate . ';' .
                      $amount . ';' .
                      $currency . ';' .
                      implode(';', $productName) . ';' .
                      implode(';', $productCount) . ';' .
                      implode(';', $productPrice);

        $signature = hash_hmac('md5', $signString, $this->merchantSecretKey);

        echo '<form id="wayforpay" action="' . $this->wayForPayUrl . '" method="POST">
                <input type="hidden" name="merchantAccount" value="' . $this->merchantAccount . '">
                <input type="hidden" name="merchantDomainName" value="' . $this->merchantDomainName . '">
                <input type="hidden" name="orderReference" value="' . $orderReference . '">
                <input type="hidden" name="orderDate" value="' . $orderDate . '">
                <input type="hidden" name="amount" value="' . $amount . '">
                <input type="hidden" name="currency" value="' . $currency . '">
                <input type="hidden" name="productName[]" value="' . $productName[0] . '">
                <input type="hidden" name="productPrice[]" value="' . $productPrice[0] . '">
                <input type="hidden" name="productCount[]" value="' . $productCount[0] . '">
                <input type="hidden" name="merchantSignature" value="' . $signature . '">
              </form>';
        echo '<script>document.getElementById("wayforpay").submit();</script>';
    }
}

class PaymentContext {
    private $strategy;

    public function __construct(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function executePayment($amount, $description) {
        $this->strategy->pay($amount, $description);
    }
}

$merchantAccount = '52_149_65_84'; 
$merchantDomainName = '52_149_65_84';
$merchantSecretKey = '2abd9286fc2e494c82820b47251b289f419473b7'; 

$wayForPay = new WayForPayStrategy($merchantAccount, $merchantDomainName, $merchantSecretKey);
$paymentContext = new PaymentContext($wayForPay);

session_start();
require_once('../controllers/lesson-controller.php');
require_once('../controllers/teacher-controller.php');
$lc = new LessonController;
$tc = new TeacherController;
$teacherId = $_SESSION['lesson']['teacher_id'];
$duration = $_SESSION['lesson']['duration'];
$teacher = $tc->getTeacherById($teacherId);
$amount = $lc->getTotalPrice($teacher->getPrice(), $duration);

$description = 'Lesson with tutor';
$paymentContext->executePayment($amount, $description);

?>