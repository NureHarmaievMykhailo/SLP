function showToast(message) {
    var toast = document.getElementById("toast-notification");
    toast.textContent = message;
    toast.style.display = "block";

    // Trigger reflow to apply CSS transition
    void toast.offsetWidth;

    // Remove the toast element from the DOM after the fade-out animation completes
    toast.addEventListener("transitionend", function() {
        toast.style.display = "none";
        toast.classList.remove("fade-out");
    }, { once: true });

    // Add fade-out class after a short delay
    setTimeout(function() {
        toast.classList.add("fade-out");
    }, 2500); // Start fading out after 2.5 seconds
}
