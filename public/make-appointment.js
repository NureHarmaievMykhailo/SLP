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
                    showTimeSlots(id);
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
    let timeSlotDiv = document.getElementById('time_slot_div');
    timeSlotDiv.innerHTML = "";
    let date = document.getElementById('dateInput').value.trim();
    if (!date) {
        return;
    }
    let dateTimestamp = Math.floor(new Date(date).getTime() / 1000);
    sendPostToRouter('lesson-controller', 'getTimeSlots', { date: dateTimestamp, teacher_id: id })
    .then(function(response){
        let jsonResponse;
        try {
            jsonResponse = JSON.parse(response);
            console.log(jsonResponse);
            console.log(response);

            jsonResponse.forEach(lesson => {
                let timeSlot = document.createElement('button');
                let timestamp = new Date(lesson.start_time * 1000);
                timeSlot.innerHTML = `${addZero(timestamp.getHours())}:${addZero(timestamp.getMinutes())}`
                if (lesson.isTaken) {
                    timeSlot.classList.add('time_slot_disabled');
                } else {
                    timeSlot.classList.add('time_slot');
                    timeSlot.value = lesson.start_time;
                    timeSlot.addEventListener('click', pickDate);
                }
                timeSlotDiv.appendChild(timeSlot);
            });
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

function pickDate() {
    let timeSlotBtns = document.querySelectorAll('.time_slot');
    timeSlotBtns.forEach(btn => {
        btn.classList.remove('time_slot_selected')
    })
    this.classList.add('time_slot_selected');
    showFormatBlock();
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
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'none';
}

function hideTimeBlock() {
    let timeDiv = document.getElementById('timeBlock');
    timeDiv.style.display = 'none';
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'none';
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'none';
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'none';
}

function showFormatBlock() {
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'flex';
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'none';
}

function hideFormatBlock() {
    let timeDiv = document.getElementById('timeBlock');
    timeDiv.style.display = 'flex';
    let formatBlock = document.getElementById('formatBlock');
    formatBlock.style.display = 'none';
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'none';
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'none';
}

function showDurationBlock() {
    let durationDiv = document.getElementById('durationBlock');
    durationDiv.style.display = 'flex';
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'none';
}

function showContinueButton() {
    let continueBtn = document.getElementById('continueBtn');
    continueBtn.style.display = 'block';
}

function submitLesson(id) {
    let time = document.querySelector('.time_slot_selected').value.trim();
    let isOnlineGroup = document.getElementsByName('radioIsOnline');
    let durationGroup = document.getElementsByName('radioDuration');
    let isOnline;
    let duration;
    isOnlineGroup.forEach(btn => {
        if(btn.checked){
            isOnline = (btn.value.trim() == 'online') ? true : false;
        }
    });

    durationGroup.forEach(btn => {
        if(btn.checked){
            duration = btn.value.trim();
        }
    })
    console.log(time, " ", isOnline, " ", duration)
    sendPostToRouter('lesson-controller', 'saveLessonDetails',
        {
            teacher_id: id,
            start_time: time,
            isOnline: isOnline,
            duration: duration
        }
    ).then(function(response){
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
                window.location.href = jsonResponse.redirect;
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

function deleteLessonDetails(id) {
    sendPostToRouter('lesson-controller', 'deleteLessonDetails')
    .then(function(response){
        console.log(response);
        window.location.href = `make_appointment?id=${id}`
    })
    .catch(function(error){
        console.error(error);
    })
}

function addZero(i) {
    if (i < 10) {i = "0" + i}
    return i;
}