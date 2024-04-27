<?php
require_once '/var/www/html/employee/Model/UserModel.php';

if(isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $userModel = new User();
    $employeeDetails = $userModel->getEmployeeDetails($employeeId);
    
    if($employeeDetails && !empty($employeeDetails['resume'])) {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $employeeDetails['resume'] . '"');
        readfile('/var/www/html/employee/resume/' . $employeeDetails['resume']);
        exit;
    } else {
        exit("Resume not available or employee details not found.");
    }
} else {
    exit("Employee ID is missing.");
}
?>
