<?php
require_once '/var/www/html/Config/db-connection.php';

class User 
{
    private $db;

    public function __construct() 
    {
        $this->db = new Database("localhost", "root", "", "aspire");
        $this->db->getConnection();
    }

    public function addUser($username,$hashedPassword,$email)
    {   
        $query = $this->db->conn->prepare("INSERT INTO `emp_users` (username, password, email) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $hashedPassword, $email);
        $result = $query->execute();

        return $result;
    }
  
    public function getUserByEmail($email)
    {
        $query = $this->db->conn->prepare("SELECT email FROM `emp_users` WHERE email = ?");
        $query->bind_param("s",$email);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        return $result;
    }

    public function getUserByEmailPassword($email,$password)
    {
        $query = $this->db->conn->prepare("SELECT username, password FROM `emp_users` WHERE email = ?");
        $query->bind_param("s",$email);
        $query->execute();
        $result = $query->get_result();
       
        if($result->num_rows === 1) 
        {
            $user = $result->fetch_assoc();
           
            if (password_verify($password, $user['password'])) 
            {
                return $user;
            }
        }
        
        return null;
    }

    public function addEmployee($name, $empid, $department, $skills, $status, $resume, $officeEmail, $personalEmail)
    {
        $query = $this->db->conn->prepare("INSERT INTO `employees_table` (name, empid, department, skills, status, resume) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssss", $name, $empid, $department, $skills, $status, $resume);
        $result = $query->execute();
    
        if (!$result) {
            return false; 
        }
        $lastInsertedId = $this->db->conn->insert_id;

        $query = $this->db->conn->prepare("INSERT INTO `employee_email` (office_email, personal_email, employee_id) VALUES (?, ?, ?)");
        $query->bind_param("ssi", $officeEmail, $personalEmail, $lastInsertedId);
        $result_email = $query->execute();
    
        return $result_email;
    }
    
    public function getEmpByEmail($officeEmail)
    {
        $query = $this->db->conn->prepare("SELECT * FROM employee_email WHERE office_email = ?");
        $query->bind_param("s", $officeEmail);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
          $employee = $result->fetch_assoc();
          return $employee; 
        } else {
          return null; 
          }
    }
    
    public function getEmpDetails() 
    {
        $query = "SELECT emp.*, emp_email.* FROM employees_table emp JOIN employee_email emp_email ON emp.id = emp_email.employee_id";
        $result = $this->db->conn->query($query);
        
        $employeeDetails = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employeeDetails[] = $row;
            }
        }
        return $employeeDetails;
    }

    public function getEmployeeDetails($employeeId)
    {
        $query = $this->db->conn->prepare("
            SELECT emp.*, emp_email.office_email, emp_email.personal_email
            FROM employees_table emp
            JOIN employee_email emp_email ON emp.id = emp_email.employee_id
            WHERE emp.id = ?
        ");
        $query->bind_param("i", $employeeId);
        $query->execute();
        $result = $query->get_result();
       
        if ($result->num_rows > 0) {
            $employeeDetails = $result->fetch_assoc();
            return $employeeDetails;
        } else {
            return null;
        }
    }
    
    public function updateEmployee($employeeId, $name, $department, $skills, $status, $officeEmail, $personalEmail)
    {
        $query = $this->db->conn->prepare("
            UPDATE employees_table emp
            JOIN employee_email emp_email ON emp.id = emp_email.employee_id
            SET emp.name = ?, emp.department = ?, emp.skills = ?, emp.status = ?,
            emp_email.office_email = ?, emp_email.personal_email = ?
            WHERE emp.id = ? ");
        $query->bind_param("ssssssi", $name, $department, $skills, $status, $officeEmail, $personalEmail, $employeeId);
        $result = $query->execute();

    return $result;
    }

    public function deleteEmployee($employeeId)
    {
        $query = $this->db->conn->prepare("
            DELETE FROM employee_email
            WHERE employee_id = ?
        ");
        $query->bind_param("i", $employeeId);
        $result = $query->execute();
    
        if ($result) {
            $query = $this->db->conn->prepare("
                DELETE FROM employees_table
                WHERE id = ?");
            $query->bind_param("i", $employeeId);
            $result = $query->execute();
        }
    
        return $result;
    }
    
    public function __destruct() 
    {
        $this->db->closeConnection();
    }  
}
?>