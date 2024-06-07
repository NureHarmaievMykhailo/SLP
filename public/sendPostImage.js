function sendPostImageToRouter(payload) {
    return new Promise((resolve, reject) => {
        fetch('/controllers/Router.php', {
            method: 'POST',
            body: payload
        })
        .then(response => response.text())
        .then(data => resolve(data))
        .catch(error => reject(error));
    });
}