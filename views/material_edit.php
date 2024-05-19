<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    $root = __DIR__ . "/..";
    require_once("$root/controllers/material-controller.php");
    $mc = new LearningMaterialController;
    $all_categories = $mc->getAllCategories();
    
    $isEditing = false;
    // Mode: Editing
    if (isset($mc->getParams()["id"])) {
        $isEditing = true;
        $id = $mc->getParams()["id"];
        $material = $mc->getMaterialById($id);
        $categories = $material->getCategories();
    }
    // Default Mode: Addition
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
    <?php
        if($isEditing) {
            echo "Editing an existing item: ID = {$material->getId()}";
        } else {
            echo "Adding a new item";
        }
    
    ?>
    <form class="edit_form">
        <label for="titleInput">Title:</label>
        <textarea class="input_edit title_edit" type="text" name="titleInput" id="titleInput">
            <?php if($isEditing) echo $material->getTitle(); ?>
        </textarea>
        
        <label for="shortInfoInput">Short Info:</label>
        <textarea class="input_edit shortInfo_edit" type="text" name="shortInfoInput" id="shortInfoInput">
            <?php if($isEditing) echo $material->getShortInfo(); ?>    
        </textarea>
        
        <label for="descriptionInput">Description:</label>
        <textarea class="input_edit description_edit" type="text" name="descriptionInput" id="descriptionInput">
            <?php if($isEditing) echo htmlspecialchars($material->getDescription()); ?>
        </textarea>
        <?php
            // Print checkboxes for categories
            foreach ($all_categories as $category) {
                echo "<label><input type=\"checkbox\" name=\"checkboxes[]\" value=\"{$category->getId()}\"";

                // Mark as checked if the material has the category.
                // If in adding mode, all checkboxes are unchecked.
                if(isset($categories)) {
                    if (in_array($category, $categories)) { echo "checked"; }
                }

                echo '>' . htmlspecialchars($category->getCategoryName()) . '</label><br>';
            }
        ?>
        <button class="button edit_button" type="button"
            onclick="<?php $btnArg = ($isEditing) ? ("sendUpdate({$material->getId()})") : ("sendInsert()"); echo $btnArg; ?>">
            <?php $btnText = ($isEditing) ? ("Update data") : ("Publish new"); echo $btnText; ?>
        </button>
    </form>

    <script>
        function sendUpdate(id) {
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
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
            });
        }

        function sendInsert() {
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
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
            });
        }
    </script>
</body>
</html>