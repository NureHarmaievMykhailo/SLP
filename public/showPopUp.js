function showPopUp(data) {
    // Get the modal element
    const modal = document.getElementsByClassName('modal')[0];

    // Set its content
    let modalText = document.getElementsByClassName('modal-content-text')[0];
    modalText.innerHTML = data;

    // Display the modal
    modal.style.display = 'block';

    // Get the <span> element that closes the modal
    const closeModalBtn = document.getElementsByClassName('close')[0];

    // When the user clicks on <span> (x), close the modal
    closeModalBtn.onclick = function() {
	modal.style.display = 'none';
    }
}

function displayError(additionalInfo, fullData) {
    showPopUp(`ERROR. ${additionalInfo} Check console for details.`);
    console.log("Server response dump:\n", fullData);
}