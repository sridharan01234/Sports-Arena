function isValidEmail(email) {
    const emailRegex = /\S+@\S+\.\S+/;
    return emailRegex.test(email);
}
const form = document.getElementById("updatedetails");
form.addEventListener("submit", validateForm);

function validateForm(event) {
    event.preventDefault(); 
    
    const newUsername = document.getElementById("newUsername").value.trim();
    const newEmail = document.getElementById("newEmail").value.trim();

    const usernameError = document.getElementById("usernameError");
    const emailError = document.getElementById("emailError");

    if (newUsername === "" || newEmail === "") {
        usernameError.textContent = newUsername === "" ? "* username must be filled out" : "";
        emailError.textContent = newEmail === "" ? "* email must be filled out" : "";
        return; 
    } else {
        if (!isValidEmail(newEmail)) {
            emailError.textContent = "* Please enter a valid email address";
            return; 
        }
        form.submit();
    }
}