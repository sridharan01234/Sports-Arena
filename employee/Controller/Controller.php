<?php
require_once '/var/www/html/employee/Model/UserModel.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class EmployeeController 
{
    private $uploadDir = "/var/www/html/employee/resume/";
    private $maxFileSize = 5*1024*1024; 

    public function addEmployee() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $name = $_POST['name'];
            $empid = $_POST['empid'];
            $officeEmail = $_POST['office_email'];
            $personalEmail = $_POST['personal_email'];
            $department = $_POST['department'];
            $skills = $_POST['skills'];
            $status = $_POST['status'];

            if ($this->isEmployeeExist($officeEmail)) 
            {
                $message = "Employee with this email already exists.";
                $_SESSION['error'] = $message;
                header("Location: ../View/Add_employee.php");
                exit();
            } 

            $resumeFileName = $this->uploadResume();
            if (!$resumeFileName) {
                $message = "Failed to upload resume file";
                $_SESSION['error'] = $message;
                header("Location: ../View/Add_employee.php");
                exit();
            }

            $employeeObj = new User();
            $result = $employeeObj->addEmployee($name, $empid, $department, $skills, $status, $resumeFileName, $officeEmail, $personalEmail);

            if($result) 
            {  
                header("Location: ../View/Dashboard.php");
                exit;
            } 
            else 
            {
                $message = "Failed to add employee";
                $_SESSION['error'] = $message;
                header("Location: ../View/Add_employee.php");
                exit();
            }
        }
    }

    public function deleteEmployee($employeeId)
    {
        $userModel = new User();
        $result = $userModel->deleteEmployee($employeeId);

        if ($result) {
            echo "Employee deleted successfully.";
            header("Location: ../View/Dashboard.php");
            exit;
        } else {
            echo "Failed to delete employee.";
            header("Location: ../View/Dashboard.php");
            exit;
        }
    }

    public function updateEmployee()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['id'])) {
                $name = $_POST['name'];
                $employeeId = $_POST['id']; 
                $officeEmail = $_POST['office_email'];
                $personalEmail = $_POST['personal_email'];
                $department = $_POST['department'];
                $skills = $_POST['skills'];
                $status = $_POST['status'];

                $userModel = new User();
                $result = $userModel->updateEmployee($employeeId, $name, $department, $skills, $status, $officeEmail, $personalEmail);

                if ($result) {
                    $employeeDetails = $userModel->getEmpDetails();
                    $_SESSION['employeeDetails'] = $employeeDetails;
                    include_once '../View/Dashboard.php';
                    exit;
                } else {
                    $message = "Failed to update employee details.";
                    $_SESSION['error'] = $message;
                    exit;
                }
            } else {
                $message = "Employee ID is missing.";
                $_SESSION['error'] = $message;
                exit;
            }
        }
    }

    public function viewEmployee($employeeId)
    {
        $userModel = new User();
        $employeeDetails = $userModel->getEmployeeDetails($employeeId);
        return $employeeDetails; 
    }

    private function isEmployeeExist($officeEmail)
    {
        $userModel = new User();
        $result = $userModel->getEmpByEmail($officeEmail);

        return ($result !== null) ? true : false;
    }

    private function uploadResume()
    {
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
            $message = "Resume file upload failed: " . $_FILES['resume']['error'];
            $_SESSION['error'] = $message;
            return false;
        }

        $resumeFile = $_FILES['resume'];
        $resumeFileName = $resumeFile['name'];
        $resumeTempName = $resumeFile['tmp_name'];
        $resumeFileSize = $resumeFile['size'];
        $resumeFileType = strtolower(pathinfo($resumeFileName, PATHINFO_EXTENSION));

        if (!in_array($resumeFileType, ["pdf", "docx", "doc"])) {
            $message = "Invalid resume file type: " . $resumeFileType;
            $_SESSION['error'] = $message;
            return false;
        }

        if ($resumeFileSize > $this->maxFileSize) {
            $message = "Resume file size exceeds the limit: " . $resumeFileSize;
            $_SESSION['error'] = $message;
            return false;
        }

        $resumeUploadFile = $this->uploadDir . $resumeFileName;

        if (!move_uploaded_file($resumeTempName, $resumeUploadFile)) {
            $message = "Failed to move uploaded resume file";
            $_SESSION['error'] = $message;
            return false;
        }

        return $resumeFileName;
    }
}

$controller = new EmployeeController();

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'add':
            $controller->addEmployee();
            break;
        case 'update':
            $controller->updateEmployee();
            break;
        default:
            echo "Invalid action.";
    }
} elseif(isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'delete':
            if(isset($_GET['empid'])) {
                $controller->deleteEmployee($_GET['empid']);
            } else {
                echo "Employee ID is missing.";
            }
            break;
        case 'view':
            if(isset($_GET['id'])) {
               $controller->viewEmployee($_GET['id']);
            } else {
                echo "Employee ID is missing.";
            }
            break;
        default:
            echo "Invalid action.";
    }
} else {
  //  echo "Action not specified.";
}
?>
