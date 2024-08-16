<?php

require_once './interface/BaseInterface.php';
require_once './database/Database.php';

class TurfModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllTurf()
    {
        return $this->db->getAll('turf', [], []);
    }

    public function getTurf(int $id)
    {
        return $this->db->get('turf', ['turf_id' => $id], []);
    }

    public function bookTurf(array $details)
    {
        return $this->db->insert('turf_registrations', $details);
    }

    public function getTurfSlots(int $id, string $date)
    {
        return $this->db->getAll('turf_registrations', ['turf_id' => $id, 'turf_date' => $date], ['slot_start', 'slot_end']);
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