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
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/material_control_panel.css" type="text/css" rel="stylesheet"/>
    <script src="../public/jquery-3.7.1.min.js"></script>
    <title>Control panel</title>
</head>
<body>
    <?php include("moderator-header.php"); ?>
    <div class="control_panel">
        <div class="search_panel">
        <div id="loading" class="loading-circle"></div>
            <form class="id_form">
                <label for="limitInput" class="noselect text_default" >Enter limit for materials to be listed:
                <input type="number" min="0" step="1" required id="limitInput" name="limitInput" class="limit_input"></label>
                <button class="button" type="button" onclick="submitLimit();">Вивести всі матеріали</button>
            </form>
            <form class="id_form">
                <label for="idInput" class="noselect text_default">Search by material ID:
                <input type="number" min="1" step="1" required id="idInput" name="idInput" class="id_input"></label>
                <button class="search_button" type="button" onclick="submitId();"><img class="search_button_img noselect"src="../public/images/search.png"></button>
            </form>
            <form class="id_form">
                <label for="titleInput" class="noselect text_default">Search by material title:
                <input required id="titleInput" name="titleInput" class="title_input"></label>
                <button class="search_button" type="button" onclick="submitTitle();"><img class="search_button_img noselect"src="../public/images/search.png"></button>
            </form>
        </div>

        <div class="add_button_div">
            <button class="button add_button" onclick="redirectToEdit(-1);">Додати матеріал</button>
        </div>
    </div>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <div id="materials_div" class="materials_div">

    </div>

    <script>
        function sendPostToRouter(controller, method, data) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: '/controllers/Router.php',
                    type: 'POST',
                    data: { 
                        controller: controller,
                        method: method,
                        params: data
                    },
                    success: function(response) {
                        resolve(response); // Resolve the promise with the response
                    },
                    error: function(xhr, status, error) {
                        reject(error); // Reject the promise with the error
                    }
                });
            });
        }

        function submitLimit() {
            let limit = document.getElementById("limitInput").value;
            if(limit == '') {
                getAll();
                return;
            }
            getAll(limit);
        }

        function getAll(limitValue = 100) {
            showLoading();

            sendPostToRouter('material-controller', 'getAllAsJson', { limit: limitValue })
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

        function renderElements(objectArray) {
            let parentDiv = document.getElementById("materials_div");
            // Reset div contents
            parentDiv.innerHTML = "";

            objectArray.forEach(material => {
                let materialDiv = document.createElement('div');
                materialDiv.classList.add("block", "material");

                let materialParagraph = document.createElement('p');
                let materialTitle = document.createElement('h');
                materialTitle.innerHTML = `<b>title</b> =<br>${material.title}`;
                materialDiv.appendChild(materialTitle);
                materialParagraph.innerHTML = `<b>id</b> = ${material.id}<br><b>shortInfo</b> =<br>${material.shortInfo}<br><br>`;
                materialDiv.appendChild(materialParagraph);

                let categoryDiv = document.createElement('div');
                categoryDiv.classList.add("categories_div");
                material.categories.forEach(category => {
                    let categoryParagraph = document.createElement('p');
                    categoryParagraph.innerHTML = `<b>Category<br>id</b> = ${category.id}<br><b>category_name</b> = ${category.category_name}<br><br>`;
                    categoryDiv.appendChild(categoryParagraph);
                });
                materialDiv.appendChild(categoryDiv);
                
                let buttonDiv = document.createElement('div');
                buttonDiv.classList.add("button_div");

                let editButton = document.createElement('button');
                editButton.classList.add('button', 'button_edit');
                editButton.onclick = function() {redirectToEdit(material.id)};
                editButton.innerHTML = "Edit element";
                
                let deleteButton = document.createElement('button');
                deleteButton.classList.add('button', 'button_edit');
                deleteButton.onclick = function() {deleteMaterial(material.id)};
                deleteButton.innerHTML = "Delete element";

                buttonDiv.appendChild(editButton);
                buttonDiv.appendChild(deleteButton);
        
                materialDiv.appendChild(buttonDiv);

                parentDiv.appendChild(materialDiv);
            });
        }

        function redirectToEdit(id) {
            if (id > 0) {
                window.location.href = `material_edit?id=${id}`;
            }
            else {
                window.location.href = "material_edit";
            }
        }

        function deleteMaterial(id) {
            const userConfirmed = confirm('Are you sure you want to delete this item? This action CAN NOT be undone.');
            if (!userConfirmed) {
                return;
            }

            showLoading();

            sendPostToRouter('material-controller', 'deleteMaterial', { material_id: id })
            .then(function(response) {
                showPopUp(`Deleted material with id=${id} from the database.`);
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
            sendPostToRouter('material-controller', 'getMaterialJsonById', { material_id: id })
            .then(function(response) {
                idInput.value = "";
                let responseJson;
                try {
                    responseJson = JSON.parse(response);
                }
                catch (error) {
                    console.log(error);
                    displayError("Couldn't parse JSON sent from server.", response);
                    hideLoading();
                    return;
                }

                if (responseJson[0]["id"] === null) {
                    displayError(`Couldn't retrieve material with id = ${id} from DB. Material with id = ${id} may not exist.`,
                        responseJson);
                    hideLoading();
                    return;
                }

                renderElements(responseJson);
                showPopUp(`Retrieved material with id = ${id} from DB.`);
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

            sendPostToRouter('material-controller', 'getMaterialsJsonByTitle', { title: title })
            .then(function(response) {
                titleInput.value = "";
                let responseJson;
                try {
                    responseJson = JSON.parse(response);
                }
                catch (error) {
                    console.log(error);
                    displayError("Couldn't parse JSON sent from server.", response);
                    hideLoading();
                    return;
                }

                if (responseJson.length == 0 || responseJson[0]["id"] === null) {
                    displayError(`Couldn't retrieve material with title like "${title}" from DB. Try to be more specific or try another query.`,
                        responseJson);
                    hideLoading();
                    return;
                }

                renderElements(responseJson);
                showPopUp(`Retrieved ${responseJson.length} materials with title like "${title}" from DB.`);
                console.log(`Operation: getByTitle, title = ${title}`);
                hideLoading();
            })
            .catch(function(error) {
                hideLoading();
                console.error("Error occurred:", error);
            });
        }

        function displayError(additionalInfo, fullData) {
            showPopUp(`ERROR. ${additionalInfo} Check console for details.`);
            console.log("Server response dump:\n", fullData);
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
    <script src="../public/showPopUp.js"></script>
</body>
</html>
