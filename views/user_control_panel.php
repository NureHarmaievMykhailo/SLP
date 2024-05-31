<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/material_control_panel.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <title>Control panel</title>
</head>
<body>
    <?php include("admin-header.php"); ?>
    <div class="control_panel">
        <div class="search_panel">
        <div id="loading" class="loading-circle"></div>
            <form class="id_form">
                <label for="limitInput" class="noselect text_default" >Enter limit for materials to be listed:
                <input type="number" min="0" step="1" required id="limitInput" name="limitInput" class="limit_input"></label>
                <button class="button" type="button" onclick="submitLimit();">Вивести всі матеріали</button>
            </form>
        </div>

        <div class="search_panel">
        <div id="loading" class="loading-circle"></div>
            <form class="id_form">
                <label for="search" class="noselect text_default" >Search by Name or Email:
                <input type="text" id="search" name="search" class="search_input"></label>
                <button class="search_button" type="button" onclick="submitName();"><img class="search_button_img noselect"src="../public/images/search.png"></button>
            </form>
        </div>
    </div>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <div id="users_div" class="users_div">

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

        function submitName(){
            let limit = document.getElementById("search").value;
            if(limit == ''){
                getAll();
                return;
            }
        }

        function getAll(limitValue = 100) {
            showLoading();

            sendPostToRouter('user-controller', 'getAllAsJson', { limit: limitValue })
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
            let parentDiv = document.getElementById("users_div");
            // Reset div contents
            parentDiv.innerHTML = "";

            // Create table
            let table = document.createElement('table');
            table.classList.add('user-table');

            // Create table header
            let headerRow = document.createElement('tr');
            let headers = ['ID', 'First Name', 'Last Name', 'Email', 'Sex', 'Birthdate', 'Registration Date', 'Country', 'City', 'Phone', ''];
            headers.forEach(headerText => {
                let header = document.createElement('th');
                header.innerText = headerText;
                headerRow.appendChild(header);
            });
            table.appendChild(headerRow);

            // Populate table with user data
            userArray.forEach(user => {
                let row = document.createElement('tr');

                Object.values(user).forEach(value => {
                    let cell = document.createElement('td');
                    cell.innerText = value;
                    row.appendChild(cell);
                });

                // Create actions cell
                let actionsCell = document.createElement('td');

                let deleteButton = document.createElement('button');
                deleteButton.classList.add('button', 'button_delete');
                deleteButton.onclick = function() { deleteUser(user.id) };
                deleteButton.innerText = "Delete";

                actionsCell.appendChild(deleteButton);
                row.appendChild(actionsCell);

                table.appendChild(row);
            });
    
            parentDiv.appendChild(table);
        }

        function deleteUser(userId) {
            const userConfirmed = confirm('Are you sure you want to delete this user? This action CANNOT be undone.');
            if (!userConfirmed) {
                return;
            }

            showLoading();

            sendPostToRouter('user-controller', 'deleteUser', { userId: id })
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
