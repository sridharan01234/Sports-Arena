<?php
require_once '/var/www/html/employee/Controller/Authenticate.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../View/Signin_user.php");
    exit();
}
$userModel = new User();
$employeeDetails = $userModel->getEmpDetails();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
</head>
<body>
    <nav>
        <a href="Add_employee.php">Add Employee</a>
        <a href="../Controller/logout.php">Logout</a>
    </nav>
    <h2>Welcome to Employee Dashboard</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Employee ID</th>
                <th>Office Email</th>
                <th>Personal Email</th>
                <th>Department</th>
                <th>Skills</th>
                <th>Status</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($employeeDetails as $employee): ?>
            <tr>
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['empid']; ?></td>
                <td><?php echo $employee['office_email']; ?></td>
                <td><?php echo $employee['personal_email']; ?></td>
                <td><?php echo $employee['department']; ?></td>
                <td><?php echo $employee['skills']; ?></td>
                <td><?php echo $employee['status']; ?></td>
               
                <td>
                <form action="View_employee.php" method="get">
                        <input type="hidden" name="id" value="<?php echo $employee['employee_id']; ?>">
                        <input class='view' type="submit" value="View">
                </form>
                </td>
                <td>
                <form action="Edit_employee.php" method="get">
                        <input type="hidden" name="id" value="<?php echo $employee['employee_id']; ?>">
                        <input class='edit' type="submit" value="Edit">
                </form>
                </td>
                <td>
                <form action="../Controller/Controller.php" method="get">
                <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="empid" value="<?php echo $employee['employee_id']; ?>">
                        <input class='delete' type="submit" value="delete">
                </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>