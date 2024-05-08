function addToCart(id){
    $.ajax({
        url: "/controllers/shop-controller.php", // URL of the PHP script to handle the request
        type: "POST", // Method type
        data: { productId: id }, // Data to be sent
        success: function(response) {
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
}