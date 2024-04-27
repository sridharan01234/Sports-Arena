function isValidEmail(email) {
    const emailRegex = /\S+@\S+\.\S+/;
    return emailRegex.test(email);
}

function isValidPassword(password) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;
    return passwordRegex.test(password);
}

const form = document.getElementById("registration");
form.addEventListener("submit", validateForm);

function validateForm(event) {
    event.preventDefault(); 
    
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmpassword").value;

    const usernameError = document.getElementById("usernameError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmpasswordError");

    
    usernameError.textContent = "";
    emailError.textContent = "";
    passwordError.textContent = "";
    confirmPasswordError.textContent = "";

    if (username === "") {
        usernameError.textContent = "* Username must be filled out";
        return;
    }

    if (email === "") {
        emailError.textContent = "* Email must be filled out";
        return;
    } else if (!isValidEmail(email)) {
        emailError.textContent = "* Please enter a valid email address";
        return;
    }

    if (password === "") {
        passwordError.textContent = "* Password must be filled out";
        return;
    } else if (password.length < 8) {
        passwordError.textContent = "* Password must be at least 8 characters";
        return;
    } else if (!isValidPassword(password)) {
        passwordError.textContent = "* Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character";
        return;
    }

   // if(password === "" || password.length < 8 || !isValidPassword(password))
    if (confirmPassword === "") {
        confirmPasswordError.textContent = "* Please confirm your password";
        return;
    } else if (password !== confirmPassword) {
        confirmPasswordError.textContent = "* Passwords do not match";
        return;
    }
        form.submit();
    }



