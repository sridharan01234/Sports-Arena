<?php
require_once './database/Database.php';

class TurfModel extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addTurf(array $details) {
        return $this->db->insert('turf', $details);
    }

    public function isAdmin($user_id) {
        if ($user_id !== null) {
            $user = $this->db->getAll('users', ['user_id' => $user_id, 'is_admin' => '1'], []);
            return $user;
        } else {
            return false;
        }
    }
}
?>
