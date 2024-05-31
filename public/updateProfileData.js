function updateProfileData() {
    let firstName = document.getElementById('firstNameInput').value.trim();
    let lastName = document.getElementById('lastNameInput').value.trim();
    let email = document.getElementById('emailInput').value.trim();
    let sex = document.getElementById('sexInput').value.trim();
    let country = document.getElementById('countryInput').value.trim();
    let birthdate = document.getElementById('birthdateInput').value.trim();
    let city = document.getElementById('cityInput').value.trim();
    let phoneNumber = document.getElementById('phoneNumberInput').value.trim();

    sendPostToRouter('upload_changes.php', 'updateProfile', 
        {
            firstName: firstName,
            lastName: lastName,
            email: email,
            sex: sex,
            birthdate: birthdate,
            country: country,
            city: city,
            phoneNumber: phoneNumber
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
        if (responseJSON.success) {
            window.location.href = '../views/user_profile'; // Перенаправлення на сторінку профілю
        } else {
            displaySignUpError(responseJSON.error);
        }
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