<?php

require_once './model/TournamentModel.php';
require_once 'BaseController.php';
require_once './helper/JWTHelper.php';

class TournamentController extends BaseController
{
    private $tournamentModel;

    public function __construct()
    {
        $this->tournamentModel = new Tournament_Model();
    }

    /**
     * Endpoint to add a new tournament.
     * POST method expected.
     * Required fields: title, description, start_date, end_date, tournament_location, tournament_image, organizer_name, phone_number, email.
     * 
     * @return void
     */
    public function addTournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode the request data
            $data = $this->decodeRequest();
            $response = [];

            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception("User session not found.");
                }

                // Validate required fields
                $required_fields = ['title', 'description', 'start_date', 'end_date', 'tournament_location', 'tournament_image', 'organizer_name', 'phone_number', 'email'];
                foreach ($required_fields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("Field '$field' is required");
                    }
                }

                // Convert dates to proper format
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                $end_date = date('Y-m-d', strtotime($data['end_date']));

                // Prepare tournament details
                $details = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'tournament_location' => $data['tournamentLocation'],
                    'organizer_name' => $data['organizerName'],
                    'phone_number' => $data['phoneNumber'],
                    'email' => $data['email'],
                ];

                // Check for date conflict
                if ($this->tournamentModel->isDateConflict($details['title'], $details['tournament_location'], $details['start_date'], $details['end_date'])) {
                    throw new Exception("A tournament with the same name and location exists during these dates.");
                }

                // Create tournament
                $this->tournamentModel->createTournament($details);
                $response = [
                    'status' => 'success',
                    'message' => 'Tournament created successfully.'
                ];
                http_response_code(200);

            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
        }
    }

    /**
     * Endpoint to get tournament details.
     * GET method expected.
     * If tournament_id is provided, returns details of that tournament if it exists and is upcoming.
     * If no tournament_id is provided, returns details of all upcoming tournaments.
     * 
     * @return void
     */
    public function getTournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];

            try {
                $tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : null;
                $currentDate = date('Y-m-d H:i:s');
                
                if ($tournament_id !== null) {
                    // Get details of a specific tournament
                    $tournament = $this->tournamentModel->getTournament($tournament_id);
                    if ($tournament && strtotime($tournament[0]['end_date']) > strtotime($currentDate)) {
                        $response = [
                            'status' => 'success',
                            'data' => $tournament
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Tournament not found or it has already ended.'
                        ];
                        http_response_code(404);
                    }
                } else {
                    // Get details of all upcoming tournaments
                    $tournaments = $this->tournamentModel->getUpcomingTournaments();
                    $response = [
                        'status' => 'success',
                        'data' => $tournaments
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            throw new Exception("Invalid request method.");
        }   
    }

   /**
 * Endpoint to register a player for a tournament.
 * POST method expected.
 * Required fields: tournament_id, player_name, team_name, email, phone_number.
 * 
 * @return void
 */
public function registerTournament() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $this->decodeRequest();
        $response = [];

        try {
            // $required_fields = ['tournament_id', 'player_name', 'team_name', 'email', 'phone_number'];
            // foreach ($required_fields as $field) {
            //     if (!isset($data[$field]) || empty($data[$field])) {
            //         throw new Exception("Field '$field' is required");
            //     }
            // }
            $details = [
                'user_id' => $_SESSION['user_id'],
                'tournament_id' => $data['tournamentId'],
                'player_name' => $data['playerName'],
                'team_name' => $data['teamName'],
                'email' => $data['email'],
                'phone_number' => $data['phoneNumber']
            ];
            // Check if player is already registered for this tournament
            $playerExists = $this->tournamentModel->isPlayerRegistered($data['tournament_id'], $data);

            if ($playerExists) {
                // Player is already registered
                $response = [
                    // 'status' => 'error',
                    // 'message' => 'Player is already registered for this tournament.'
                ];
                http_response_code(409);
            } else {
                // Register player for the tournament
                $this->tournamentModel->addPlayer($details);
                $response = [
                    'status' => 'success',
                    'message' => 'Player registered successfully for the tournament.'
                ];
                http_response_code(200); 
            }

        } catch (Exception $e) {
            // Error occurred during registration
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
