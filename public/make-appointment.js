function checkDateAvailability(id) {
    let date = document.getElementById('dateInput').value.trim();
    if (!date) {
        return;
    }
    hideTimeBlock();
    let dateTimestamp = Math.floor(new Date(date).getTime() / 1000);
    console.log(dateTimestamp);
    sendPostToRouter('lesson-controller', 'checkIfDateInSchedule', { dateTimestamp: dateTimestamp, teacher_id: id })
    .then(function(response){
        let jsonResponse;
        try {
            jsonResponse = JSON.parse(response);
            if (jsonResponse.status == 'error') {
                console.error('Error occurred: ', jsonResponse.error);
                console.log(response);
                console.log(jsonResponse);
                return;
            }
            if (jsonResponse.status == 'success') {
                if (jsonResponse.isAvailable === 0) {
                    console.log('No lessons available. Pick another date.');
                    showCalendarError();
                    hideTimeBlock();
                    return;
                }
                else {
                    console.log('ok');
                    showCalendarOk();
                    showTimeBlock();
                    return;
                }
            }
            console.log("Unexpected response.", response)
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

function showTimeSlots(id) {

}

function showCalendarOk() {
    let responseDiv = document.getElementById('date_confirm_div');
    responseDiv.innerHTML = "";

    let okImg = document.createElement('img');
    okImg.setAttribute('src', '../public/images/calendar_ok.svg');
    responseDiv.appendChild(okImg);

    let okMsg = document.createElement('p');
    okMsg.innerHTML = 'Оберіть час заняття.';
    okMsg.classList.add('text_default');
    responseDiv.appendChild(okMsg);
}

function showCalendarError() {
    let responseDiv = document.getElementById('date_confirm_div');
    responseDiv.innerHTML = "";

    let errImg = document.createElement('img');
    errImg.setAttribute('src', '../public/images/calendar_error.png');
    errImg.style.width = '50px';
    errImg.style.height = 'auto';
    responseDiv.appendChild(errImg);

    let errMsg = document.createElement('p');
    errMsg.innerHTML = 'У обрану дату немає занять.';
    errMsg.classList.add('text_default');
    responseDiv.appendChild(errMsg);
}

function showTimeBlock() {
    let timeDiv = document.getElementById('timeBlock');
    timeDiv.style.display = 'flex';
}

function hideTimeBlock() {
    let timeDiv = document.getElementById('timeBlock');
    timeDiv.style.display = 'none';
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'none';
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'none';
}

function showFormatBlock() {
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'flex';
}

function hideFormatBlock() {
    let timeDiv = document.getElementById('timeBlock');
    timeDiv.style.display = 'flex';
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'none';
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'none';
}

function showDurationBlock() {
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'flex';
}