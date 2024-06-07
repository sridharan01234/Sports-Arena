<?php

require './database/Database.php';

class Tournament_Model extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createTournament(array $details)
    {
        $this->db->insert('tournaments', $details);
    }

    public function getTournament($tournament_id = null) {
        if ($tournament_id !== null) {
            return $this->db->getAll('tournaments', ['tournament_id' => $tournament_id], []);
        } else {
            return $this->db->getAll('tournaments', [], []);
        }
    }

    public function addPlayer(array $details)
    {
        $this->db->insert('tournament_registrations', $details);
    }
      
    public function isPlayerRegistered($registration_id = null,$details) {
        if ($registration_id!== null) {
            return $this->db->get('tournament_registrations', ['registration_id' => $registration_id, $details], []);
        } else {
            return $this->db->get('tournament_registrations', [], []);
        }
    }
}