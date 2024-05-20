<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    $root = __DIR__ . "/..";
    require_once("$root/controllers/material-controller.php");
    $mc = new LearningMaterialController;

    // Retrieve all categories from DB as an array of MaterialCategory objects,
    // cast them to object(stdClass) for compatibility with json_decode() data.
    $all_categories = array_map(fn($cat) => (object) $cat->toArray(), $mc->getAllCategories());
    // Default Mode: Addition
    $isEditing = false;
    // Mode: Editing
    if (isset($mc->getParams()["id"])) {
        $isEditing = true;
        $id = $mc->getParams()["id"];
        $material = json_decode($mc->getMaterialJsonById($id))[0];

        // Redirect to the "Add new material" page if ID is not valid.
        if (is_null($material->id)) {
            header('Location: material_edit');
            die();
        }

        //Set material categories
        $categories = $material->categories;
    }
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
    <?php include("moderator-header.html"); ?>
    <div class="action_header_div">
        <h>Welcome to the editing panel. Current mode: 
        <?php
            if($isEditing) {
                echo "Editing an existing item: ID = {$material->id}";
            } else {
                echo "Adding a new item";
            }
        ?>
        </h>
    </div>
    <form class="edit_form">
        <label for="titleInput" class="edit_label">Title:</label>
        <textarea class="input_edit title_edit" type="text" name="titleInput" id="titleInput">
            <?php if($isEditing) echo $material->title; ?>
        </textarea>
        
        <label for="shortInfoInput" class="edit_label">Short Info:</label>
        <textarea class="input_edit shortInfo_edit" type="text" name="shortInfoInput" id="shortInfoInput">
            <?php if($isEditing) echo $material->shortInfo; ?>    
        </textarea>
        
        <label for="descriptionInput" class="edit_label">Description:</label>
        <textarea class="input_edit description_edit" type="text" name="descriptionInput" id="descriptionInput">
            <?php 
                if($isEditing) {
                    $description = (is_null($material->description)) ? $material->description : htmlspecialchars($material->description);
                    echo $description;
                }
            ?>
        </textarea>
        <label class="edit_label">Categories:</label>
        <?php
            // Print checkboxes for categories
            foreach ($all_categories as $category) {
                echo "<label class=\"category_label noselect\"><input type=\"checkbox\" name=\"checkboxes[]\" value=\"{$category->id}\"";

                // Mark as checked if the material has the category.
                // If in adding mode, all checkboxes are unchecked.
                if(isset($categories)) {
                    if (in_array($category, $categories)) { echo "checked"; }
                }

                echo '>' . $category->category_name . '</label><br>';
            }
        ?>
        <button class="button edit_button" type="button"
            onclick="<?php $btnArg = ($isEditing) ? ("sendUpdate({$material->id})") : ("sendInsert()"); echo $btnArg; ?>">
            <?php $btnText = ($isEditing) ? ("Update data") : ("Publish new"); echo $btnText; ?>
        </button>
    </form>

    <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="modal-content-text"></p>
        </div>
    </div>

    <script>
        function sendUpdate(id) {
            const userConfirmed = confirm('Are you sure you want to update this item?');
            if (!userConfirmed) {
                return;
            }

            let title = document.getElementById("titleInput").value.trim();
            let shortInfo = document.getElementById("shortInfoInput").value.trim();
            let description = document.getElementById("descriptionInput").value.trim();

            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let checkedCategoryIds = [];

            // Check if each checkbox is checked
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    checkedCategoryIds.push(parseInt(checkbox.value));
                }
            });

            $.ajax({
                url: '/controllers/Router.php',
                type: 'POST',
                data: { 
                    controller: 'material-controller',
                    method: 'updateMaterial',
                    params: {
                        material_id: id,
                        new_title: title,
                        new_shortInfo: shortInfo,
                        new_description: description,
                        material_categories: checkedCategoryIds
                    }
                },
                success: function(response) {
                    showPopUp(`Successfully updated the item. ID = ${response}`);
                    console.log("Operation: update. Server response: " + response);
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
            });
        }

        function sendInsert() {
            const userConfirmed = confirm('Are you sure you want to add this item to the database?');
            if (!userConfirmed) {
                return;
            }

            let title = document.getElementById("titleInput").value.trim();
            let shortInfo = document.getElementById("shortInfoInput").value.trim();
            let description = document.getElementById("descriptionInput").value.trim();

            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let checkedCategoryIds = [];

            // Check if each checkbox is checked
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    checkedCategoryIds.push(parseInt(checkbox.value));
                }
            });

            $.ajax({
                url: '/controllers/Router.php',
                type: 'POST',
                data: { 
                    controller: 'material-controller',
                    method: 'insertMaterial',
                    params: {
                        title: title,
                        shortInfo: shortInfo,
                        description: description,
                        material_categories: checkedCategoryIds
                    }
                },
                success: function(response) {
                    showPopUp(`Successfully inserted a new item. ID = ${response}`);
                    console.log("Operation: insert. Server response: " + response);

                    let titleObj = document.getElementById("titleInput");
                    let shortInfoObj = document.getElementById("shortInfoInput");
                    let descriptionObj = document.getElementById("descriptionInput");
                    let checkboxesObj = document.querySelectorAll('input[type="checkbox"]');

                    // Set all the inputs to default values
                    titleObj.value = "";
                    shortInfoObj.value = "";
                    descriptionObj.value = "";
                    checkboxesObj.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
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