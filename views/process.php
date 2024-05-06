<?php
// Check if the POST request contains any data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Access POST data
    $outerData = $_POST['outerData']; // Data sent from the outer div
    // Process the data or perform any necessary actions
    echo "Outer data received: " . $outerData;
} else {
    // Handle other HTTP request methods (GET, PUT, DELETE, etc.) if necessary
    echo "Invalid request method";
}
?>
