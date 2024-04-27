function isValidEmail(email) {
    const emailRegex = /\S+@\S+\.\S+/;
    return emailRegex.test(email);
}

function isValidEmpId(empid) {
    const empidRegex = /^ACE\d{5}$/;
    return empidRegex.test(empid);
}

const form = document.getElementById("employee");
form.addEventListener("submit", validateForm);

function validateForm(event) {
    event.preventDefault();
    
    const name = document.getElementById("name").value.trim();
    const empid = document.getElementById("empid").value.trim();
    const department = document.getElementById("department").value;
    const skills = document.getElementById("skills").value.trim();
    const status = document.getElementById("status").value;
    const email = document.getElementById("email").value.trim();
    const resume = document.getElementById("resume").value; 

    const nameError = document.getElementById("nameError");
    const empidError = document.getElementById("empidError");
    const departmentError = document.getElementById("departmentError");
    const skillsError = document.getElementById("skillsError");
    const statusError = document.getElementById("statusError");
    const emailError = document.getElementById("emailError");
  
    nameError.textContent = "";
    empidError.textContent = "";
    departmentError.textContent = "";
    skillsError.textContent = "";
    statusError.textContent = "";
    emailError.textContent = "";

    if (name === "") {
        nameError.textContent = "* Name must be filled out";
        event.preventDefault();
        return;
    }

    if (empid === "") {
        empidError.textContent = "* Employee ID must be filled out";
        event.preventDefault();
        return;
    } else if (!isValidEmpId(empid)) {
        empidError.textContent = "* Please enter a valid employee ID (Format: ACE*****)";
        event.preventDefault();
        return;
    }

    if (department === "") {
        departmentError.textContent = "* Please select the department";
        event.preventDefault();
        return;
    }

    if (skills === "") {
        skillsError.textContent = "* Skills must be filled out";
        event.preventDefault();
        return;
    }

    if (status === "") {
        statusError.textContent = "* Please select the status";
        event.preventDefault();
        return;
    }

    if (email === "") {
        emailError.textContent = "* Email must be filled out";
        event.preventDefault();
        return;
    } else if (!isValidEmail(email)) {
        emailError.textContent = "* Please enter a valid email address";
        event.preventDefault();
        return;
    }
    if (resume === "") {
        resumeError.textContent = "* Please select the resume";
        event.preventDefault();
        return;
    }
    form.submit();
}



