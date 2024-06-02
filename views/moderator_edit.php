<?php
session_start();
require_once('../session-config.php');
checkSessionTimeout();
//redirectUnauthorized([PermissionCode::Admin->value]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/styles.css" type="text/css" rel="stylesheet"/>
    <link href="../public/material_edit.css" type="text/css" rel="stylesheet"/>
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <title>Edit material</title>
</head>
<body>
    <?php include("admin-header.php"); ?>
    <form class="edit_form">
        <label for="firstNameInput" class="edit_label">First name:</label>
        <textarea class="input_edit title_edit" type="text" name="firstNameInput" id="firstNameInput"></textarea>
        
        <label for="lastNameInput" class="edit_label">Last name:</label>
        <textarea class="input_edit title_edit" type="text" name="lastNameInput" id="lastNameInput"></textarea>
        
        <label for="emailInput" class="edit_label">Email:</label>
        <textarea class="input_edit title_edit" type="text" name="emailInput" id="emailInput"></textarea>

        <label for="passwordInput" class="edit_label">Password:</label>
        <textarea class="input_edit title_edit" type="text" name="passwordInput" id="passwordInput"></textarea>
        
        <button class="button edit_button" type="button" onclick="sendInsert();">Publish new</button>
    </form>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <script>
        function sendInsert() {
            const userConfirmed = confirm('Are you sure you want to add this item to the database?');
            if (!userConfirmed) {
                return;
            }

            let firstName = document.getElementById("firstNameInput").value.trim();
            let lastName = document.getElementById("lastNameInput").value.trim();
            let email = document.getElementById("emailInput").value.trim();
            let password = document.getElementById("passwordInput").value.trim();

            $.ajax({
                url: '/controllers/Router.php',
                type: 'POST',
                data: { 
                    controller: 'user-controller',
                    method: 'insertModerator',
                    params: {
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        password: password
                    }
                },
                success: function(response) {
                    showPopUp(`Successfully inserted a new item. ID = ${response}`);
                    console.log("Operation: insert. Server response: " + response);

                    let firstNameObj = document.getElementById("firstNameInput");
                    let lastNameObj = document.getElementById("lastNameInput");
                    let emailObj = document.getElementById("emailInput");
                    let passwordObj = document.getElementById("passwordInput");

                    // Set all the inputs to default values
                    firstNameObj.value = "";
                    lastNameObj.value = "";
                    emailObj.value = "";
                    passwordObj.value = "";
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
            });
        }
    </script>
    <script src="../public/showPopUp.js"></script>
</body>
</html>