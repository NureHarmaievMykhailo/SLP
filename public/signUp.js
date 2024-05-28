function signUp() {
    let firstName = document.getElementById('firstNameInput').value.trim();
    let lastName = document.getElementById('lastNameInput').value.trim();
    let email = document.getElementById('emailInput').value.trim();
    let sex = document.getElementById('sexInput').value.trim();
    let country = document.getElementById('countryInput').value.trim();
    let birthdate = document.getElementById('birthdateInput').value.trim();
    let city = document.getElementById('cityInput').value.trim();
    let phoneNumber = document.getElementById('phoneNumberInput').value.trim();
    let pwd = document.getElementById('pwdInput').value.trim();
    let pwdConfirm = document.getElementById('pwdConfirmInput').value.trim();

    sendPostToRouter('sign-up-controller', 'signUpUser', 
        {
            firstName: firstName,
            lastName: lastName,
            email: email,
            sex: sex,
            birthdate: birthdate,
            country: country,
            city: city,
            phoneNumber: phoneNumber,
            pwd: pwd,
            pwdConfirm: pwdConfirm
        }
    ).then(function(response) {
        let responseJSON;
        try {
            responseJSON = JSON.parse(response);
        }
        catch (error) {
            console.log(response);
            console.error(error);
        }
        if (responseJSON.redirect) {
            window.location.href = responseJSON.redirect;
        }
        let msg;
        if (responseJSON.errors.emailError) {
            msg = responseJSON.errors.emailError;
        }
        if (responseJSON.errors.passwordError) {
            msg = responseJSON.errors.passwordError;
        }
        if (responseJSON.errors.queryError) {
            msg = responseJSON.errors.queryError;
        }
        displaySignUpError(msg);
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}

function displaySignUpError(message) {
    let errorDiv = document.getElementById('errorDiv');
    errorDiv.innerHTML = "";
    let errorParagraph = document.createElement('p');
    errorParagraph.innerHTML = message;
    errorDiv.appendChild(errorParagraph);
}