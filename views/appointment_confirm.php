<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized([PermissionCode::User->value]);

require_once('../controllers/lesson-controller.php');
require_once('../controllers/teacher-controller.php');
$lc = new LessonController;
$teacherId = $_SESSION['lesson']['teacher_id'];
$start_time = $_SESSION['lesson']['start_time'];
$isOnline = $_SESSION['lesson']['isOnline'];
$duration = $_SESSION['lesson']['duration'];
$tc = new TeacherController;
$teacher = $tc->getTeacherById($teacherId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet" />
    <link href="../public/appointment_confirm.css" type="text/css" rel="stylesheet" />
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/make-appointment.js"></script>
    <title>Confirm appointment</title>
</head>

<body>
    <?php include('header-logged-in.html'); ?>
    <div class="main">
        <div class="breadcrumbs_div">
            <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
                &nbsp;&nbsp;>&nbsp;&nbsp;
                <a href="teachers_list" class="link_hidden">Викладачі</a>
                &nbsp;&nbsp;>&nbsp;&nbsp;
                <a href="teacher?id=<?php echo $teacherId; ?>" class="link_hidden"><?php echo $teacher->getName(); ?></a>
                &nbsp;&nbsp;>&nbsp;&nbsp;
                <a href="make_appointment?id=<?php echo $teacherId; ?>" class="link_hidden">Оформлення заняття</a>
                &nbsp;&nbsp;>&nbsp;&nbsp;
                <p1 style="font-weight: bold;">Підтвердження заняття</p1>
            </p>
        </div>

        <div class="topic-header">
            <div class="header-line-row">
                <div class="header-line"></div>
            </div>
            <div class="header topic-header-text">
                <p><span>Перевірка деталей та проведення оплати</span></p>
            </div>
        </div>

        <div class="appointment_confirm_main">
            <div class="block appointment_confirm_div">
                <div class="appointment_confirm_header_div">
                    <h class="header">Деталі заняття:</h>
                </div>
                <div class="price_div_wrapper">
                    <div class="block price_div ">
                        <h class="header">До сплати: <?php echo $lc->getTotalPrice($teacher->getPrice(), $duration); ?>грн</h>
                    </div>
                </div>
                <div class="appointment_confirm_data_wrapper">
                    <div class="appointment_confirm_data_div">
                        <h class="header">Дата проведення:
                            <p1 class="text_default"><?php echo date("d.m.Y", $start_time); ?></p1>
                        </h>
                        <h class="header">Час:
                            <p1 class="text_default"><?php echo date("H:i", $start_time); ?></p1>
                        </h>
                        <h class="header">Формат заняття:
                            <p1 class="text_default">
                                <?php
                                $isOnlineStr = ($isOnline) ? "онлайн" : "офлайн";
                                echo $isOnlineStr;
                                ?>
                            </p1>
                        </h>
                        <h class="header">Тривалість заняття:
                            <p1 class="text_default">
                                <?php
                                $durationStr = match ($duration) {
                                    1800 => "30 хвилин",
                                    3600 => "1 година",
                                    5400 => "1 година 30 хвилин",
                                };
                                echo $durationStr;
                                ?>
                            </p1>
                        </h>
                    </div>
                </div>
                <div class="appointment_confirm_btn_cancel">
                    <button class="button" onclick="deleteLessonDetails(<?php echo $teacherId?>)">Скасувати</button>
                </div>
                <div class="appointment_confirm_btn_confirm">
                    <a class="button" href="payment_invoice">Оплатити</a>
                </div>
            </div>
        </div>

    </div>
    <?php include('footer.html'); ?>
</body>

</html>