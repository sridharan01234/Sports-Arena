<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $database;
    public $conn;

    public function __construct($servername, $username, $password, $database) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function getConnection() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
        if (!$this->conn) {
            throw new Exception("Sorry, Connection Failed: " . mysqli_connect_error());
        }
    }

    public function closeConnection() {
        mysqli_close($this->conn);
    }
}

$db = new Database("localhost", "root", "", "aspire");
try {
    $db->getConnection();
    //echo "database connect successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// class Database {
//     private $servername;
//     private $username;
//     private $password;
//     private $database;
//     public $conn;

//     public function __construct($servername, $username, $password, $database) {
//         $this->servername = $servername;
//         $this->username = $username;
//         $this->password = $password;
//         $this->database = $database;
//     }

//     public function getConnection() {
//         // Enable error reporting and display
//         ini_set('display_errors', 1);
//         error_reporting(E_ALL);

//         $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
//         if ($this->conn) {
//             echo "Connected";
//         } else {
//             $errorMessage = "Connection failed: " . mysqli_connect_error();
//             echo $errorMessage;
//         }
//     }

//     public function closeConnection() {
//         mysqli_close($this->conn);
//     }
// }

// $obj = new Database("localhost", "roott", "", "aspire");
// $obj->getConnection();
?>
