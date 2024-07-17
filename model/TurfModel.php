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
}