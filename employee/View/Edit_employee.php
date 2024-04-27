<?php
require_once '/var/www/html/employee/Controller/Controller.php';

$employeeDetails = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeController = new EmployeeController();
    $updateEmployee = $employeeController->updateEmployee();
}
$userModel = new User(); 
if(isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $employeeDetails = $userModel->getEmployeeDetails($employeeId);
    if ($employeeDetails) {
       // echo "employee details found";
    } else {
        echo "Employee details not found.";
    }
} else {
    echo "Employee ID is missing.";
    exit;
}
?>

<html>
<head>
    <title>Edit Employee Details</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <nav>
        <a href="Dashboard.php">Dashboard</a>
    </nav>
    <h2>Edit Employee Details</h2>
    <form action="../Controller/Controller.php" method="post">

        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $employeeDetails['id']; ?>">
 
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $employeeDetails['name']; ?>"><br>

        <label>Employee ID:</label>
        <input type="text" name="empid" value="<?php echo $employeeDetails['empid']; ?>" readonly><br>
        
        <label for="email">Office Email:</label>
        <input type="email" id="email" name="office_email" value="<?php echo $employeeDetails['office_email']; ?>"><br>

        <label for="email">Personal Email:</label>
        <input type="email" id="email" name="personal_email" value="<?php echo $employeeDetails['personal_email']; ?>"><br>
        
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" value="<?php echo $employeeDetails['department']; ?>"><br>
        
        <label for="skills">Skills:</label>
        <input type="text" id="skills" name="skills" value="<?php echo $employeeDetails['skills']; ?>"><br>
        
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo $employeeDetails['status']; ?>"><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>


