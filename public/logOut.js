function logOut() {
    sendPostToRouter('log-in-controller', 'logOut', { })
        .then(function(response){
            window.location.href = "/";
        })
        .catch(function(error){
            console.error("Error occurred:", error);
        });
}