<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
redirectUnauthorized([PermissionCode::Moderator->value, PermissionCode::Admin->value]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet" />
    <link href="../public/teacher_control_panel.css" type="text/css" rel="stylesheet" />
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/showPopUp.js"></script>
    <script src="../public/sendPost.js"></script>
    <title>Control panel</title>
</head>

<body>
    <?php include("moderator-header.php"); ?>
    <div class="main">
        <div class="control_panel">
        <div class="search_panel">
            <div id="loading" class="loading-circle"></div>
            <form class="id_form">
                <label for="limitInput" class="noselect text_default">Enter limit for teachers to be listed:
                    <input type="number" min="0" step="1" required id="limitInput" name="limitInput" class="limit_input"></label>
                <button class="button" type="button" onclick="submitLimit();">Вивести всіх викладачів</button>
            </form>
            <form class="id_form">
                <label for="idInput" class="noselect text_default">Search by teacher ID:
                    <input type="number" min="1" step="1" required id="idInput" name="idInput" class="id_input"></label>
                <button class="search_button" type="button" onclick="submitId();"><img class="search_button_img noselect" src="../public/images/search.png"></button>
            </form>
            <form class="id_form">
                <label for="titleInput" class="noselect text_default">Search by teacher name:
                    <input required id="titleInput" name="titleInput" class="title_input"></label>
                <button class="search_button" type="button" onclick="submitTitle();"><img class="search_button_img noselect" src="../public/images/search.png"></button>
            </form>
        </div>

        <div class="add_button_div">
            <button class="button add_button" onclick="redirectToEdit(-1);">Додати викладача</button>
        </div>
    </div>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <div id="teacher_div" class="teacher_div">

    </div>
    </div>

    <script>
        function renderElements(data) {
            let parentDiv = document.getElementById('teacher_div');
            parentDiv.innerHTML = "";

            data.forEach(teacher => {
                let teacherDiv = document.createElement('div');
                teacherDiv.classList.add("block", "teacher_entry_div");

                let teacherParagraph = document.createElement('p');
                let teacherName = document.createElement('h');
                teacherName.innerHTML = `<b>${teacher.name}</b>`;
                teacherDiv.appendChild(teacherName);
                let teacherId = document.createElement('p');
                teacherId.innerHTML = `ID = ${teacher.id}`;
                teacherDiv.appendChild(teacherId);
                let teacherPhoto = document.createElement('img');
                teacherPhoto.src = teacher.imageURI;
                teacherPhoto.classList.add('teacher_pfp');
                teacherDiv.appendChild(teacherPhoto);

                let buttonDiv = document.createElement('div');
                buttonDiv.classList.add("button_div");

                let editButton = document.createElement('button');
                editButton.classList.add('button', 'button_edit');
                editButton.onclick = function() {redirectToEdit(teacher.id)};
                editButton.innerHTML = "Edit element";
                
                let deleteButton = document.createElement('button');
                deleteButton.classList.add('button', 'button_edit');
                deleteButton.onclick = function() {deleteTeacher(teacher.id)};
                deleteButton.innerHTML = "Delete element";

                buttonDiv.appendChild(editButton);
                buttonDiv.appendChild(deleteButton);
        
                teacherDiv.appendChild(buttonDiv);

                parentDiv.appendChild(teacherDiv);
            });
        }

        function submitLimit() {
            let limit = document.getElementById("limitInput").value;
            if (limit == '') {
                getAll();
                return;
            }
            getAll(limit);
        }

        function getAll(limitValue = 100) {
            showLoading();

            sendPostToRouter('teacher-controller', 'getAllAsJson', {
                    limit: limitValue
                })
                .then(function(response) {
                    let responseJson = JSON.parse(response);
                    renderElements(responseJson);
                    showPopUp(`Retrieved ${responseJson.length} records from the database.`);
                    hideLoading();
                })
                .catch(function(error) {
                    hideLoading();
                    console.error("Error occurred:", error);
                });
        }

        function redirectToEdit(id) {
            if (id > 0) {
                window.location.href = `teacher_edit?id=${id}`;
            } else {
                window.location.href = "teacher_edit";
            }
        }

        function deleteTeacher(id) {
            const userConfirmed = confirm('Are you sure you want to delete this entry? This action CAN NOT be undone.');
            if (!userConfirmed) {
                return;
            }

            showLoading();

            sendPostToRouter('teacher-controller', 'deleteTeacher', {
                    teacher_id: id
                })
                .then(function(response) {
                    showPopUp(`Deleted teacher with id=${id} from the database.`);
                    console.log(`Operation: delete id=${id}`);
                    submitLimit();
                    hideLoading();
                })
                .catch(function(error) {
                    hideLoading();
                    console.error("Error occurred:", error);
                });
        }

        function submitId() {
            let idInput = document.getElementById("idInput");
            let id = parseInt(idInput.value);
            if (id <= 0 || isNaN(id)) {
                showPopUp("Please, enter a valid ID. Valid IDs are integers, greater than zero (0).");
                idInput.value = "";
                return;
            }

            showLoading();
            sendPostToRouter('teacher-controller', 'getTeacherJsonById', {
                    teacher_id: id
                })
                .then(function(response) {
                    idInput.value = "";
                    let responseJson;
                    try {
                        responseJson = JSON.parse(response);
                    } catch (error) {
                        console.log(error);
                        displayError("Couldn't parse JSON sent from server.", response);
                        hideLoading();
                        return;
                    }

                    if (responseJson[0]["id"] === null) {
                        displayError(`Couldn't retrieve teacher with id = ${id} from DB. Teacher with id = ${id} may not exist.`,
                            responseJson);
                        hideLoading();
                        return;
                    }

                    renderElements(responseJson);
                    showPopUp(`Retrieved teacher with id = ${id} from DB.`);
                    console.log(`Operation: getById, id=${id}`);
                    hideLoading();
                })
                .catch(function(error) {
                    hideLoading();
                    console.error("Error occurred:", error);
                });
        }

        function submitTitle() {
            showLoading();
            let titleInput = document.getElementById("titleInput");
            let title = titleInput.value.trim();

            sendPostToRouter('teacher-controller', 'getTeacherJsonByName', {
                    title: title
                })
                .then(function(response) {
                    titleInput.value = "";
                    let responseJson;
                    try {
                        responseJson = JSON.parse(response);
                    } catch (error) {
                        console.log(error);
                        displayError("Couldn't parse JSON sent from server.", response);
                        hideLoading();
                        return;
                    }

                    if (responseJson.length == 0 || responseJson[0]["id"] === null) {
                        displayError(`Couldn't retrieve teacher with name like "${title}" from DB. Try to be more specific or try another query.`,
                            responseJson);
                        hideLoading();
                        return;
                    }

                    renderElements(responseJson);
                    showPopUp(`Retrieved ${responseJson.length} teachers with name like "${title}" from DB.`);
                    console.log(`Operation: getByTitle, title = ${title}`);
                    hideLoading();
                })
                .catch(function(error) {
                    hideLoading();
                    console.error("Error occurred:", error);
                });
        }

        function showLoading() {
            const loadingElement = document.getElementById('loading');
            loadingElement.style.display = 'inline';
        }

        function hideLoading() {
            const loadingElement = document.getElementById('loading');
            loadingElement.style.display = 'none';
        }
    </script>

</body>

</html>