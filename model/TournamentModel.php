<?php

require_once './database/Database.php';

class Tournament_Model extends Database {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create a new tournament in the database.
     * 
     * @param array $details Details of the tournament to be created.
     * @return bool Returns true on successful insertion, false otherwise.
     */
    public function createTournament(array $details): bool
    {
        $this->db->insert('tournaments', $details);
        return $this->db->get('tournaments', ['title' => $details['title'], 'tournament_location' => $details['tournament_location']], [])->tournament_id;
    }

    /**
     * Get tournament details by tournament ID.
     * If no ID provided, fetch all tournaments.
     * 
     * @param int|null $tournament_id (Optional) ID of the tournament to fetch.
     * @return array|null Returns an array of tournament details if found, null otherwise.
     */
    public function getTournament($tournament_id = null): ?array {
        if ($tournament_id !== null) {
            return $this->db->getAll('tournaments', ['tournament_id' => $tournament_id], []);
        } else {
            return $this->db->getAll('tournaments', [], []);
        }
    }

     /**
     * Get tournament details by tournament ID.
     * If no ID provided, fetch all tournaments.
     * 
     * @param int|null $tournament_id (Optional) ID of the tournament to fetch.
     * @return object|null Returns an object of tournament details if found, null otherwise.
     */
    public function fetchTournament($tournament_id = null): ?object {
        if ($tournament_id !== null) {
            $this->db->query("SELECT * FROM tournaments WHERE tournament_id = :tournament_id");
            $this->db->bind(':tournament_id', $tournament_id);
            return $this->db->single(); // Assuming single returns an object
        } else {
            $this->db->query("SELECT * FROM tournaments");
            return $this->db->resultSet(); // Assuming resultSet returns an array of objects
        }
    }

    /**
     * Check if a tournament with the given title and location already exists.
     * 
     * @param string $title Title of the tournament.
     * @param string $location Location of the tournament.
     * @return array|null Returns an array of tournament details if found, null otherwise.
     */
    public function isTournamentExists(string $title, string $location): ?array {
        return $this->db->getAll('tournaments', ['title' => $title, 'tournament_location' => $location], []);
    }

    /**
     * Add a player to a tournament.
     * 
     * @param array $details Details of the player registration.
     * @return bool Returns true on successful insertion, false otherwise.
     */
    public function addPlayer(array $details): bool
    {
        return $this->db->insert('tournament_registrations', $details);
    }

    /**
     * Check if a player is already registered for a tournament.
     * 
     * @param int|null $registration_id (Optional) ID of the player registration.
     * @param array $details Details to match against for player registration.
     * @return object|null Returns player registration details if found, null otherwise.
     */
    public function isPlayerRegistered($userId, $tournamentId): bool {
        $query = "SELECT COUNT(*) as count FROM tournament_registrations WHERE user_id = :user_id AND tournament_id = :tournament_id";
        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':tournament_id', $tournamentId);
        $result = $this->db->single();
        return $result->count > 0;
    }

    /**
     * Get all upcoming tournaments where the end date is after the current date.
     * 
     * @return array Returns an array of upcoming tournament details.
     */
    public function getUpcomingTournaments(): array {
        $currentDate = date('Y-m-d H:i:s');
        $query = "SELECT * FROM tournaments WHERE end_date > :currentDate";
        $this->db->query($query);
        $this->db->bind(':currentDate', $currentDate);
        return $this->db->resultSet();
    }

    /**
     * Check if there is a date conflict for a new tournament with existing tournaments.
     * 
     * @param string $title Title of the tournament to check.
     * @param string $location Location of the tournament to check.
     * @param string $start_date Start date of the tournament to check.
     * @param string $end_date End date of the tournament to check.
     * @return bool Returns true if there is a date conflict, false otherwise.
     */
    public function isDateConflict($title, $location, $start_date, $end_date): bool {
        $query = "SELECT COUNT(*) as count FROM tournaments WHERE title = :title AND tournament_location = :location AND (start_date <= :end_date AND end_date >= :start_date)";
        $this->db->query($query);
        $this->db->bind(':title', $title);
        $this->db->bind(':location', $location);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $result = $this->db->single();
        return $result->count > 0;
    }

    /**
     * Update the tournament image path.
     *
     * @param int $tournament_id The ID of the tournament
     * @param string $image_path The path to the tournament image
     * @return bool Whether the operation was successful
     */
    public function updateTournamentImage($tournament_id, $image_path): bool
    {
        return $this->db->update('tournaments', ['image_path' => $image_path], ['tournament_id' => $tournament_id]);
    }

     /**
     * Get tournament details by tournament ID.
     * If no ID provided, fetch all tournaments.
     * 
     * @param int|null $tournament_id (Optional) ID of the tournament to fetch.
     * @return array|null Returns an array of tournament details if found, null otherwise.
     */
    public function getRegister($user_id): array {
        $query = "
            SELECT 
                t.title,
                t.start_date as startDate,
                t.end_date as endDate, 
                t.organizer_name as organizerName,
                t.email,
                t.tournament_location tournamentLocation,
                t.phone_number as phoneNumber,
                tr.player_name as playerName,
                tr.team_name as teamName,
                tr.email,
                tr.phone_number as phoneNumber
            FROM tournaments t
            INNER JOIN tournament_registrations tr ON t.tournament_id = tr.tournament_id
            WHERE tr.user_id = :user_id
        ";
    
        $this->db->query($query);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }

    public function getRegisteredUsers(int $tournament_id): array {
        $query = "SELECT user_id FROM tournament_registrations WHERE tournament_id = :tournament_id";
        $this->db->query($query);
        $this->db->bind(':tournament_id', $tournament_id);
        return $this->db->resultSet();
    }
}