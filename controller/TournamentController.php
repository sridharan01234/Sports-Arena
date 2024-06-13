<?php

require_once './model/TournamentModel.php';
require_once 'BaseController.php';
require_once './helper/JWTHelper.php';

class TournamentController extends BaseController {
    private $tournamentModel;

    public function __construct()
    {
        $this->tournamentModel = new Tournament_Model();
    }

    public function addTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();
            $response = [];
            try {
                // Check if user_id is set in session
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception("User session not found.");
                }

                $required_fields = ['title', 'description', 'start_date', 'end_date', 'tournament_location', 'tournament_image', 'organizer_name', 'phone_number', 'email'];
                foreach ($required_fields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("Field '$field' is required");
                    }
                }
    
                // Extract only date part from start_date and end_date
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                $end_date = date('Y-m-d', strtotime($data['end_date']));
    
                $details = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'tournament_location' => $data['tournament_location'],
                    'tournament_image' => $data['tournament_image'],
                    'organizer_name' => $data['organizer_name'],
                    'phone_number' => $data['phone_number'],
                    'email' => $data['email'],
                ];
    
                // Check if tournament with the same title and location already exists
                $conflictingTournamentExists = $this->tournamentModel->userHasTournamentWithConflictingDate($details);
                if ($conflictingTournamentExists) {
                    throw new Exception("You already have a tournament with the same title and location for overlapping dates.");
                }
    
                $this->tournamentModel->createTournament($details);
                $response = [
                    'status' => 'success',
                    'message' => 'Tournament created successfully.'
                ];
                http_response_code(200); 
    
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
   /**
     * Get upcoming tournaments.
     * This method fetches tournaments where the end date is in the future (after current time).
     * Adjust the logic according to your database structure and date comparison needs.
     */
    public function getTournaments()
    {
        $response = [];
        try {
            // Get all tournaments
            $tournaments = $this->tournamentModel->getTournament();

            // Filter out tournaments where the end date is in the future
            $upcomingTournaments = [];
            $currentTime = time();

            foreach ($tournaments as $tournament) {
                // Convert end date to timestamp (adjust according to your database structure)
                $endDate = strtotime($tournament->end_date);

                // Compare with current time
                if ($endDate > $currentTime) {
                    $upcomingTournaments[] = $tournament;
                }
            }
            // Prepare response
            $response = [
                'status' => 'success',
                'data' => $upcomingTournaments
            ];
        } catch (Exception $e) {
            // Handle exceptions
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            http_response_code(500);
        }

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function registerTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [];

            try {
                $details = [
                    'registration_id' => $_POST['registration_id'],
                    'tournament_id' => $_POST['tournament_id'],
                    'player_name' => $_POST['player_name'],
                    'team_name' => $_POST['team_name'],
                    'email' => $_POST['email'],
                    'phone_number' => $_POST['phone_number']
                ];

                // Check if the player already exists in the tournament
                $playerExists = $this->tournamentModel->isPlayerRegistered($details['registration_id'], $details['email'], $details['player_name']);

                if ($playerExists) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Player is already registered for this tournament.'
                    ];
                    http_response_code(409);
                } else {
                    $this->tournamentModel->addPlayer($details);
                    $response = [
                        'status' => 'success',
                        'message' => 'Player registered successfully for the tournament.'
                    ];
                    http_response_code(200);
                }
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500); 
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
?>
