function isValidEmail(email) {
    const emailRegex = /\S+@\S+\.\S+/;
    return emailRegex.test(email);
}

function isValidPassword(password) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;
    return passwordRegex.test(password);
}

const form = document.getElementById("formlogin");
form.addEventListener("submit", validateForm);

function validateForm(event) {
    event.preventDefault();

    const email = document.getElementById("loginemail").value.trim();
    const password = document.getElementById("loginpassword").value;

    const emailError = document.getElementById("loginemailError");
    const passwordError = document.getElementById("loginpasswordError");


    if (email === "" || password === "") {
        emailError.textContent = email === "" ? "* Email must be filled out" : "";
        passwordError.textContent = password === "" ? "* Password must be filled out" : "";
    return;
    } else {
        if (!isValidEmail(email)) {
            emailError.textContent = "* Please enter a valid email address";
            return;
        }
        if (password.length < 8) {
            passwordError.textContent = "* Password must be at least 8 characters";
            return; 
        } else if (!isValidPassword(password)) {
            passwordError.textContent = "* Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character";
            return;
        } 
    }
        form.submit();
}
