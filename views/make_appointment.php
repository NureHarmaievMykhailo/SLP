<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized([PermissionCode::User->value]);

require_once('../controllers/lesson-controller.php');
require_once('../controllers/teacher-controller.php');
$ac = new LessonController;
$teacherId = $ac->getParams()["id"];
$tc = new TeacherController;
$teacher = $tc->getTeacherById($teacherId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet" />
    <link href="../public/make_appointment.css" type="text/css" rel="stylesheet" />
    <script src="../public/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPost.js"></script>
    <script src="../public/make-appointment.js"></script>
    <title>Make an appointment</title>
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
                <p1 style="font-weight: bold;">Оформлення заняття</p1>
            </p>
        </div>

        <div class="topic-header">
            <div class="header-line-row">
                <div class="header-line"></div>
            </div>

            <div class="header topic-header-text">
                <p><span>Оформлення та оплата заняття у декілька кроків!</span></p>
            </div>

        </div>

        <div class="appointment_form">
            <div id="dateBlock" class="block appointment_div">
                <div class="appointment_div_number header">1</div>
                <h class="header">Дата проведення</h>
                <label for="dateInput" class="text_default dateInput_div text_larger ">Оберіть дату
                    <input id="dateInput" onchange="checkDateAvailability(<?php echo $teacherId; ?>);" class="sign_up_input input_dark" type="date">
                </label>
                <div id="date_confirm_div" class="date_confirm_div">

                </div>
            </div>
            <div id="timeBlock" class="block appointment_div" style="display: none;">
                <div class="appointment_div_number header">2</div>
                <h class="header">Час проведення</h>
                <div id="time_slot_div" class="time_slot_div">

                </div>
            </div>
            <div id="formatBlock" class="block appointment_div" style="display: none;">
                <div class="appointment_div_number header">3</div>
                <h class="header">Форма проведення</h>
                <label for="radioOnline" class="text_default text_larger " style="width: 80%;">
                    <input type="radio" id="radioOnline" onchange="showDurationBlock()" name="radioIsOnline" value="online">&nbsp;Онлайн</label>
                <label for="radioOffline" class="text_default text_larger " style="width: 80%;">
                    <input type="radio" id="radioOffline" onchange="showDurationBlock()" name="radioIsOnline" value="offline">&nbsp;Офлайн</label>
            </div>
            <div id="durationBlock" class="block appointment_div" style="display: none;">
                <div class="appointment_div_number header">4</div>
                <h class="header">Тривалість заняття</h>
                <label for="radio30" class="text_default text_larger " style="width: 80%;">
                    <input type="radio" id="radio30" onchange="showContinueButton()" name="radioDuration" value="1800">&nbsp;30 хвилин
                </label>
                <label for="radio60" class="text_default text_larger " style="width: 80%;">
                    <input type="radio" id="radio60" onchange="showContinueButton()" name="radioDuration" value="3600">&nbsp;60 хвилин
                </label>
                <label for="radio90" class="text_default text_larger " style="width: 80%;">
                    <input type="radio" id="radio90" onchange="showContinueButton()" name="radioDuration" value="5400">&nbsp;90 хвилин
                </label>
            </div>
        </div>
        <div class="continue_btn_div"><button id="continueBtn" onclick="submitLesson(<?php echo $teacherId; ?>)" class="button continue_btn">Далі &#x2192</button></div>
    </div>
    <?php include('footer.html'); ?>
</body>

</html>