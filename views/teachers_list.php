<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet" />
    <link href="../public/teachers_list.css" type="text/css" rel="stylesheet" />
    <script src="../public/teachersSort.js"></script>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <title>Teachers</title>
</head>

<body>
    <?php
    $headerFile = 'header.html';
    if (isset($_SESSION['permission']) && $_SESSION['permission'] == PermissionCode::User->value) {
        $headerFile = 'header-logged-in.html';
    }
    include($headerFile);
    ?>
    <div class="main">
        <div class="breadcrumbs_div">
            <p class="paragraph breadcrumbs_text"><a href="homepage" class="link_hidden">Головна</a>
                &nbsp;&nbsp;>&nbsp;&nbsp;
                <p1 style="font-weight: bold;">Викладачі</p1>
            </p>
        </div>

        <div class="topic-header">
            <div class="header-line-row">
                <div class="header-line"></div>
            </div>

            <div class="header topic-header-text">
                <p><span>Знайдіть найкращого репетитора для вивчення української мови!</span></p>
            </div>
        </div>
        <div class="category_sort_div">
            <select id="priceInput" onchange="sortByPrice();" class="category_sort">
                <option value="" disabled selected>Відсортувати за ціною</option>
                <option value="a" >За зростанням</option>
                <option value="d" >За спаданням</option>
            </select>
        </div>
        <div class="teacher_div">
            <?php
            $root = __DIR__ . "/..";
            require_once("$root/controllers/teacher-controller.php");
            require_once("teacher-block.php");
            $tc = new TeacherController;
            $params = $tc->getParams();
            $pageLim = 8;

            $teachers;
            if (isset($params['price'])) {
                switch ($params['price']) {
                    case 'a':
                        $teachers = json_decode($tc->getByPriceAsc($pageLim));
                        break;

                    case 'd':
                        $teachers = json_decode($tc->getByPriceDesc($pageLim));
                        break;
                }
            } else {
                $teachers = json_decode($tc->getAllAsJson($pageLim));
            }

            foreach ($teachers as $row) {
                $offset = "margin-right: 80px; margin-bottom:100px;";
                $block = new TeacherBlock(
                    $offset,
                    $row->id,
                    $row->name,
                    $row->shortInfo,
                    $row->price,
                    $row->imageURI
                );

                $block->render();
            }
            ?>
        </div>
    </div>
    <?php include('footer.html'); ?>
</body>

</html>