function sortByPrice() {
    let price = document.getElementById('priceInput').value.trim();
    let url = window.location.href.split('?')[0];
    window.location.href = `${url}?price=${price}`;
}