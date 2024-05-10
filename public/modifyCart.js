function sendPost(id, requestType) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: `/controllers/cart-controller.php/${requestType}`,
            type: "POST",
            data: { productId: id },
            success: function(response) {
                resolve(response); // Resolve the promise with the response
            },
            error: function(xhr, status, error) {
                reject(error); // Reject the promise with the error
            }
        });
    });
}

function deleteItem(id) {
    sendPost(id, "delete")
    .then(function(response) {
        if(response) {
            location.reload();
            console.log(JSON.parse(response).log);
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}

function incrementItem(id) {
    sendPost(id, "increment")
    .then(function(response) {
        if(response) {
            let result = JSON.parse(response);
            console.log(result.log + " quantity:" + result.data);
            if (result.data > 0) {
                let btn = document.getElementById("stepper_dec" + id);
                btn.classList.remove("inactive");
            }
            let text = document.getElementById("quantity_number" + id);
            text.textContent = result.data;
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}

function decrementItem(id) {
    sendPost(id, "decrement")
    .then(function(response) {
        if(response) {
            let result = JSON.parse(response);
            console.log(result.log + " quantity:" + result.data);
            if (result.data == 0) {
                let btn = document.getElementById("stepper_dec" + id);
                btn.classList.add("inactive");
            }
            let text = document.getElementById("quantity_number" + id);
            text.textContent = result.data;
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}

function refreshTotalSum() {
    sendPost(id = -1, "totalPrice")
    .then(function(response) {
        if(response) {
            let result = JSON.parse(response);
            console.log(result.log + " sum:" + result.data);
            let text = document.getElementById("total_cost");
            text.textContent = result.data;
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}