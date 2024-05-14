<?php
require_once '/var/www/html/employee/Controller/Employee.php';

if (isset($_GET['id'])){
    $employeeId = $_GET['id'];
    $employeeController = new EmployeeController();
    $employeeDetails = $employeeController->viewEmployee($employeeId);
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
    <title>Employee Details</title>
    <link rel="stylesheet" href="css/view.css">
</head>
<body>
    <nav>
        <a id ="dashboard" href="Dashboard.php">Dashboard</a>
    </nav>
    <form>
        <h2>Employee Details</h2>
        <input type="hidden" name="action" value="view">
    
        <p><span class="bold">Name:</span> <?php echo $employeeDetails['name']; ?></p>
        <p><span class="bold">Employee ID:</span> <?php echo $employeeDetails['empid']; ?></p>
        <p><span class="bold">Office Email:</span> <?php echo $employeeDetails['office_email']; ?></p>
        <p><span class="bold">Personal Email:</span> <?php echo $employeeDetails['personal_email']; ?></p>
        <p><span class="bold">Department:</span> <?php echo $employeeDetails['department']; ?></p>
        <p><span class="bold">Skills:</span> <?php echo $employeeDetails['skills']; ?></p>
        <p><span class="bold">Status:</span> <?php echo $employeeDetails['status']; ?></p>
        
        <?php
        $resume = $employeeDetails['resume'];
        if(!empty($resume)) {
            echo "<a href='../Controller/Download.php?id=$employeeId'>Download Resume</a>";
        } else {
            echo "<p>Resume not available.</p>";
        }
        ?>
    </form>
</body>
</html>

