function sendPostToRouter(controller, method, payload) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '/controllers/Router.php',
            type: 'POST',
            data: {
                controller: controller,
                method: method,
                params: payload
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