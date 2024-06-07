function logIn() {
    let email = document.getElementById('emailInput').value.trim();
    let pwd = document.getElementById('pwdInput').value.trim();

    sendPostToRouter('log-in-controller', 'logInUser', {email: email, pwd: pwd})
    .then(function(response){
        let jsonResponse;
        try {
            jsonResponse = JSON.parse(response);

            if (jsonResponse.redirect) {
                window.location.href = jsonResponse.redirect;
            }

            if (jsonResponse.errors.logInError) {
                let errorDiv = document.getElementById('errorDiv');
                errorDiv.innerHTML = "";
                let errorText = document.createElement('p');
                errorText.innerHTML = jsonResponse.errors.logInError;
                errorDiv.appendChild(errorText);
            }
        }
        catch(error) {
            console.log(response);
            console.error(error);
        }
    })
    .catch(function(error){
        console.error("Error occurred:", error);
    });
}