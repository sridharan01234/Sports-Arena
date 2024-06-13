<?php

require './database/Database.php';

class Tournament_Model {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Create a new tournament.
     *
     * @param array $details The details of the tournament to be created
     * @return bool Whether the operation was successful
     */
    public function createTournament(array $details): bool {
        return $this->db->insert('tournaments', $details);
    }

    /**
     * Get tournament details by tournament_id.
     *
     * @param int|null $tournament_id The ID of the tournament to fetch (optional)
     * @return array|null The tournament details as an array or null if not found
     */
    public function getTournament(array $params = []): ?array {
        if (isset($params['tournament_id'])) {
            return $this->db->getAll('tournaments', ['tournament_id' => $params['tournament_id']], []);
        } else {
            return $this->db->getAll('tournaments', [], []);
        }
    }

   /**
     * Check if a tournament with the given title and location exists.
     *
     * @param string $title The title of the tournament
     * @param string $location The location of the tournament
     * @return array|null The tournament details as an array or null if not found
     */
    public function isTournamentExists(string $title, string $location){
        return $this->db->get('tournaments', ['title' => $title, 'tournament_location' => $location], []);
    }

    /**
     * Add a player to a tournament.
     *
     * @param array $details The details of the player registration
     * @return bool Whether the operation was successful
     */
    public function addPlayer(array $details): bool {
        return $this->db->insert('tournament_registrations', $details);
    }

    /**
     * Check if a player is already registered for a tournament.
     *
     * @param array $params The parameters containing registration_id and details
     * @return array|null The player registration details as an array or null if not found
     */
    public function isPlayerRegistered(array $params = []) {
        if (isset($params['registration_id'])) {
            return $this->db->get('tournament_registrations', ['registration_id' => $params['registration_id']] + $params['details'], []);
        } else {
            return $this->db->get('tournament_registrations', $params['details'], []);
        }
    }

    /**
     * Get upcoming tournaments based on the current date and time compared to end date.
     *
     * @return array The list of upcoming tournaments
     */
    public function getUpcomingTournaments(): array {
        $currentDate = date('Y-m-d H:i:s'); // Current date and time
        $query = "SELECT * FROM tournaments WHERE end_date > :currentDate";
        $this->db->query($query);
        $this->db->bind(':currentDate', $currentDate);
        return $this->db->resultSet();
    }

     /**
     * Check if the user has a tournament with the same title and location for overlapping dates.
     *
     * @param array $params Parameters including user_id, title, location, start_date, and end_date
     * @return bool Whether a conflicting tournament exists
     */
    public function userHasTournamentWithConflictingDate(array $params): bool {
        $query = "SELECT COUNT(*) AS count 
                  FROM tournaments 
                  WHERE user_id = :user_id 
                  AND title = :title 
                  AND tournament_location = :location 
                  AND NOT (end_date < :start_date OR start_date > :end_date)";
        
        $this->db->query($query);
        $this->db->bind(':user_id', $params['user_id']);
        $this->db->bind(':title', $params['title']);
        $this->db->bind(':location', $params['location']);
        $this->db->bind(':start_date', $params['start_date']);
        $this->db->bind(':end_date', $params['end_date']);
        
        $result = $this->db->single();

        return $result->count > 0;
    }
}

?>
