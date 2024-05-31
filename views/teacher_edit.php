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
    <link href="../public/material_edit.css" type="text/css" rel="stylesheet" />
    <script src="../pages/resources/scripts/jquery-3.7.1.min.js"></script>
    <script src="../public/sendPostImage.js"></script>
    <script src="../public/showPopUp.js"></script>
    <title>Edit teacher</title>
</head>

<body>
    <?php include('moderator-header.php'); ?>
    <?php
    $root = __DIR__ . "/..";
    require_once("$root/controllers/teacher-controller.php");
    $mc = new TeacherController;

    $isEditing = false;
    // Mode: Editing
    if (isset($mc->getParams()["id"])) {
        $isEditing = true;
        $id = $mc->getParams()["id"];
        $teacher = json_decode($mc->getTeacherJsonById($id))[0];

        // Redirect to the "Add new teacher" page if ID is not valid.
        if (is_null($teacher->id)) {
            header('Location: teacher_edit');
            die();
        }
    }
    ?>

    <div class="action_header_div">
        <h>Welcome to the editing panel. Current mode:
            <?php
            if ($isEditing) {
                echo "Editing an existing entry: ID = {$teacher->id}";
            } else {
                echo "Adding a new entry";
            }
            ?>
        </h>
    </div>
    <form class="edit_form">
        <label for="titleInput" class="edit_label">Teacher name:</label>
        <textarea class="input_edit title_edit" type="text" name="titleInput" id="nameInput">
            <?php if ($isEditing) echo $teacher->name; ?>
        </textarea>

        <div class="img-upload">
            <div id="imgPreview" class="img-contain">
                <img id="previewImg" style="width: 100%; height:auto; display:<?php $display = ($isEditing) ? "block" : "none";
                                                                                echo $display; ?>" src="<?php if ($isEditing) echo $teacher->imageURI; ?>">
            </div>
            <input type="file" onchange="previewUploaded();" id="fileupload" name="fileupload" accept="image/*" required>
            <button type="button" id="clearUploadBtn" onclick="clearUpload();" class="button">Clear</button>
        </div>

        <label for="shortInfoInput" class="edit_label">Short Info:</label>
        <textarea class="input_edit shortInfo_edit" type="text" name="shortInfoInput" id="shortInfoInput">
            <?php if ($isEditing) echo htmlspecialchars($teacher->shortInfo); ?>
        </textarea>

        <label for="priceInput" class="edit_label">Price:</label>
        <input class="input_edit " step="1" min="1" type="number" name="shortInfoInput" value="<?php if ($isEditing) echo $teacher->price; ?>" id="priceInfoInput"></input>

        <label for="descriptionInput" class="edit_label">Description:</label>
        <textarea class="input_edit description_edit" type="text" name="descriptionInput" id="descriptionInput">
            <?php
            if ($isEditing) {
                $description = (is_null($teacher->description)) ? $teacher->description : htmlspecialchars($teacher->description);
                echo $description;
            }
            ?>
        </textarea>
        <label for="educationInput" class="edit_label">Education:</label>
        <textarea class="input_edit shortInfo_edit" type="text" name="educationInput" id="educationInput">
            <?php
            if ($isEditing) {
                $education = (is_null($teacher->education)) ? $teacher->education : htmlspecialchars($teacher->education);
                echo $education;
            }
            ?>
        </textarea>
        <label for="experienceInput" class="edit_label">Experience:</label>
        <textarea class="input_edit shortInfo_edit" type="text" name="experienceInput" id="experienceInput">
            <?php
            if ($isEditing) {
                $experience = (is_null($teacher->experience)) ? $teacher->experience : htmlspecialchars($teacher->experience);
                echo $experience;
            }
            ?>
        </textarea>
        <button class="button edit_button" type="button" onclick="<?php $btnArg = ($isEditing) ? ("sendUpdate({$teacher->id}, '" . htmlspecialchars($teacher->imageURI) . "')") : ("sendInsert()");
                                                                    echo $btnArg; ?>">
            <?php $btnText = ($isEditing) ? ("Update data") : ("Publish new");
            echo $btnText; ?>
        </button>
    </form>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <script>
        function sendUpdate(id, initialURL) {
            let fileupload = document.getElementById('fileupload');
            let name = document.getElementById('nameInput').value.trim();
            let shortInfo = document.getElementById('shortInfoInput').value.trim();
            let price = document.getElementById('priceInfoInput').value.trim();
            let description = document.getElementById('descriptionInput').value.trim();
            let education = document.getElementById('educationInput').value.trim();
            let experience = document.getElementById('experienceInput').value.trim();

            let formData = new FormData();
            formData.append("method", "updateTeacher");
            formData.append("controller", "teacher-controller");

            let teacherDetails = {
                id: parseInt(id),
                teacher_name: document.getElementById('nameInput').value.trim(),
                shortInfo: document.getElementById('shortInfoInput').value.trim(),
                price: document.getElementById('priceInfoInput').value.trim(),
                description: document.getElementById('descriptionInput').value.trim(),
                education: document.getElementById('educationInput').value.trim(),
                experience: document.getElementById('experienceInput').value.trim()
            };

            if (fileupload.files.length > 0) {
                formData.append("file", fileupload.files[0]);

            } else {
                teacherDetails.imageURI = initialURL;
            }

            formData.append("params", JSON.stringify(teacherDetails));
            sendPostImageToRouter(formData)
                .then(function(response) {
                    let responseJson;
                    try {
                        responseJson = JSON.parse(response);
                    } catch (error) {
                        console.log(error);
                    }

                    if (responseJson.error) {
                        displayError(responseJson.error, response);
                    } else {
                        showPopUp(`Successfully inserted a new entry with ID=${responseJson.id}`)
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        function sendInsert() {
            let fileupload = document.getElementById('fileupload');
            let name = document.getElementById('nameInput').value.trim();
            let shortInfo = document.getElementById('shortInfoInput').value.trim();
            let price = document.getElementById('priceInfoInput').value.trim();
            let description = document.getElementById('descriptionInput').value.trim();
            let education = document.getElementById('educationInput').value.trim();
            let experience = document.getElementById('experienceInput').value.trim();

            let formData = new FormData();
            formData.append("method", "insertTeacher");
            formData.append("controller", "teacher-controller");

            let teacherDetails = {
                teacher_name: document.getElementById('nameInput').value.trim(),
                shortInfo: document.getElementById('shortInfoInput').value.trim(),
                price: document.getElementById('priceInfoInput').value.trim(),
                description: document.getElementById('descriptionInput').value.trim(),
                education: document.getElementById('educationInput').value.trim(),
                experience: document.getElementById('experienceInput').value.trim()
            };

            formData.append("params", JSON.stringify(teacherDetails));

            if (fileupload.files.length > 0) {
                formData.append("file", fileupload.files[0]);

                sendPostImageToRouter(formData)
                    .then(function(response) {
                        let responseJson;
                        try {
                            responseJson = JSON.parse(response);
                        } catch (error) {
                            console.log(error);
                        }

                        if (responseJson.error) {
                            displayError(responseJson.error, response);
                        } else {
                            showPopUp(`Successfully inserted a new entry with ID=${responseJson.id}`)
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            } else {
                console.error("No file selected");
            }
        }


        function clearUpload() {
            let input = document.getElementById('fileupload');
            let image = document.getElementById('previewImg');
            input.value = "";
            image.src = "";
            image.display = 'none';
        }

        function previewUploaded() {
            let input = document.getElementById('fileupload');
            let file = input.files[0]; // access the first file

            if (file) {
                let fileReader = new FileReader();
                let image = document.getElementById('previewImg');

                fileReader.onload = event => {
                    image.setAttribute('src', event.target.result);
                    image.style.display = 'block';
                }

                // Initiate reading the file as a Data URL
                fileReader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>