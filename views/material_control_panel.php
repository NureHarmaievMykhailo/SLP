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
    <div class="control_panel">
        <form>
            <label for="limitInput">Enter limit for materials to be listed:</label>
            <input type="number" id="dataInput" name="dataInput">
            <button class="button" type="button" onclick="submitLimit()">Вивести всі матеріали</button>
        </form>
        <button class="button" onclick="redirectToEdit(-1);">Додати матеріал</button>
    </div>

    <div id="materials_div" class="materials_div">

    </div>

    <script>
        function submitLimit() {
            let limit = document.getElementById("dataInput").value;
            if(limit == '') {
                getAll();
                return;
            }
            getAll(limit);
        }

        function getAll(limitValue = 100) {
            $.ajax({
                url: '/controllers/Router.php',
                type: 'POST',
                data: { 
                    controller: 'material-controller',
                    method: 'sendJSONAll',
                    params: {
                        limit: limitValue
                    }
                },
                success: function(response) {
                    renderElements(JSON.parse(response));
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
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
            $.ajax({
                url: '/controllers/Router.php',
                type: 'POST',
                data: { 
                    controller: 'material-controller',
                    method: 'deleteMaterial',
                    params: {
                        material_id: id
                    }
                },
                success: function(response) {
                    submitLimit();
                },
                error: function(xhr, status, error) {
                    $('#result').html('An error occurred: ' + error);
                }
            });
        }
    </script>
</body>
</html>
