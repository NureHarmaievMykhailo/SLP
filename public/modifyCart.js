function sendPost(id, requestTypeString) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/controllers/cart-controller.php",
            type: "POST",
            data: { productId: id, cartRequestType: requestTypeString},
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
            console.log(response);
            location.reload();
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
            console.log(result.status + " quantity:" + result.quantity);
            if (result.quantity > 0) {
                let btn = document.getElementById("stepper_dec" + id);
                btn.classList.remove("inactive");
            }
            let text = document.getElementById("quantity_number" + id);
            text.textContent = result.quantity;
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
            console.log(result.status + " quantity:" + result.quantity);
            if (result.quantity == 0) {
                let btn = document.getElementById("stepper_dec" + id);
                btn.classList.add("inactive");
            }
            let text = document.getElementById("quantity_number" + id);
            text.textContent = result.quantity;
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}

function refreshTotalSum() {
    sendPost(id = 0, "getTotalSum")
    .then(function(response) {
        if(response) {
            let result = JSON.parse(response);
            console.log(result.status + " sum:" + result.sum);
            let text = document.getElementById("total_cost");
            text.textContent = result.sum;
        }
    })
    .catch(function(error) {
        console.error("Error occurred:", error);
    });
}