<?php
require_once '/var/www/html/employee/Controller/Employee.php';
if(isset($_SESSION['error'])) {
    echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeController = new EmployeeController();
    $addEmployee = $employeeController->addEmployee();
}
?>
<html>
<head>
    <title>Add Employee</title>
    <link rel="stylesheet" href="css/add_emp.css"> 
</head>
<body>
    <nav>
    <a id ="dashboard" href="Dashboard.php">Dashboard</a>
    </nav>
    <div class="container">
    <form id="employee" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    
        <label for="name">Name:</label>
        <input id="name" type="text" name="name">
        <span id="nameError" class="error"></span>
        <br>
        <label for="empid">EmpID:</label>
        <input id="empid" type="text" name="empid">
        <span id="empidError" class="error"></span>
        <br>
        <label for="department">Department:</label>
        <select id="department" name="department">
            <option value="IT">.NET</option>
            <option value="HR">HR</option>
            <option value="LAMP">LAMP</option>
            <option value="TESTING">TESTING</option>
            <option value="MANAGER">MANAGER</option>
            <option value="TRAINEE">TRAINEE</option>
            <option value="MULTIMEDIA">MULTIMEDIA</option>
            <option value="CLOUD COMPUTING">CLOUD COMPUTING</option>
            <option value="JAVA">JAVA</option>
            <option value="BUSINESS APPLICATION">BUSINESS APPLICATION</option>
        </select>
        <span id="departmentError" class="error"></span>
        <br>
        <label for="skills">Skills:</label>
        <input id="skills" type="text" name="skills">
        <span id="skillsError" class="error"></span>
        <br>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <span id="statusError" class="error"></span>
        <br>
        <label for="email">Office Email:</label>
        <input id="email" type="email" name="office_email">
        <span id="emailError" class="error"></span>
        <br>
        <label for="email">personal Email:</label>
        <input id="email" type="email" name="personal_email">
        <span id="emailError" class="error"></span>
        <br>
        <label for="resume">Resume:</label>
        <input id="resume" type="file" name="resume">
        <span id="resumeError" class="error"></span>
        <br>
        <button type="submit">Add Employee</button>
        <script src="../javascript/addemp.js"></script> 
    </form>
    </div>
</body>
</html>
