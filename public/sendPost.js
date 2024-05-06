$(document).ready(function() {
    // Event delegation to handle click on inner divs within outer divs
    $(document).on("click", "#book_order_btn", function() {
        // Access data from the parent outer div
        var outerData = $(this).closest("#book_block").find("#book_title").text();
        
        // Make an AJAX POST request
        $.ajax({
            //TODO:send data to cart controller
            url: "/views/process.php", // URL of the PHP script to handle the request
            type: "POST", // Method type
            data: { outerData: outerData }, // Data to be sent
            success: function(response) {
                // Handle the response from the PHP script
                console.log("Request sent successfully");
                console.log("Response:", response);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error("Error occurred while sending the request");
                console.error("Status:", status);
                console.error("Error:", error);
            }
        });
    });
});
