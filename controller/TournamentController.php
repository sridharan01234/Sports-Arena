<?php
require_once './model/TournamentModel.php';
require_once 'BaseController.php';
require_once './helper/SessionHelper.php';
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
            $data = $this->decodeRequest();
            $response = [];
    
            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception("User session not found.");
                }
                $created_by = $_SESSION['user_id'];
            
                $start_date = date('Y-m-d', strtotime($data['startDate']));
                $end_date = date('Y-m-d', strtotime($data['endDate']));
    
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
                    'tournament_image' => $data['tournamentImage'],
                    'created_by' => $created_by,
                ];
    
                if ($this->tournamentModel->isDateConflict($details['title'], $details['tournament_location'], $details['start_date'], $details['end_date'])) {
                    throw new Exception("A tournament with the same name and location exists during these dates.");
                }

                $tournamentId = $this->tournamentModel->createTournament($details);
                if (!$tournamentId) {
                    throw new Exception("Failed to create tournament.");
                }

                $response = [
                    'status' => 'success',
                    'message' => 'Tournament created successfully.',
                    'tournament_id' => $tournamentId
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

//    /**
//  * Endpoint to register a player for a tournament.
//  * POST method expected.
//  * Required fields: tournament_id, player_name, team_name, email, phone_number.
//  * 
//  * @return void
//  */
// public function registerTournament() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $data = $this->decodeRequest();
//         $response = [];

//         if (!isset($_SESSION['user_id'])) {
//             throw new Exception("User session not found.");
//         }
    
//         try {
//             $details = [
//                 'user_id' => $_SESSION['user_id'],
//                 'tournament_id' => $data['tournamentId'],
//                 'player_name' => $data['playerName'],
//                 'team_name' => $data['teamName'],
//                 'email' => $data['email'],
//                 'phone_number' => $data['phoneNumber']
//             ];
//             // Check if player is already registered for this tournament
//             $playerExists = $this->tournamentModel->isPlayerRegistered($data['tournament_id'], $data);

//             if ($playerExists) {
//                 // Player is already registered
//                 $response = [
//                     'status' => 'error',
//                     'message' => 'Player is already registered for this tournament.'
//                 ];
//                 http_response_code(409);
//             } else {
//                 // Register player for the tournament
//                 $this->tournamentModel->addPlayer($details);
//                 $response = [
//                     'status' => 'success',
//                     'message' => 'Player registered successfully for the tournament.'
//                 ];
//                 http_response_code(200); 
//             }

//         } catch (Exception $e) {
//             // Error occurred during registration
//             $response = [
//                 'status' => 'error',
//                 'message' => $e->getMessage()
//             ];
//             http_response_code(500); 
//         }

//         header('Content-Type: application/json');
//         echo json_encode($response);
//     }
// }

public function registerTournament() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $this->decodeRequest();
        $response = [];

        if (!isset($_SESSION['user_id'])) {
            throw new Exception("User session not found.");
        }

        try {
            $userId = $_SESSION['user_id'];
            $tournamentId = $data['tournamentId'];

            $tournament = $this->tournamentModel->getTournament($tournamentId);
            if ($tournament && $tournament[0]['created_by'] == $userId) {
                throw new Exception("You cannot register for a tournament you created.");
            }

            $playerExists = $this->tournamentModel->isPlayerRegistered($userId, $tournamentId);

            if ($playerExists) {
                $response = [
                    'status' => 'error',
                    'message' => 'You are already registered for this tournament.'
                ];
                http_response_code(409);

            $registeredUsers = $this->tournamentModel->getRegisteredUsers($tournamentId);
            if (!empty($registeredUsers)) {
                $response = [
                    'status' => 'error',
                    'message' => 'This tournament is already registered by another user.'
                ];
                http_response_code(409);
            } else {
                $details = [
                    'user_id' => $userId,
                    'tournament_id' => $tournamentId,
                    'player_name' => $data['playerName'],
                    'team_name' => $data['teamName'],
                    'email' => $data['email'],
                    'phone_number' => $data['phoneNumber']
                ];
                $this->tournamentModel->addPlayer($details);
                $response = [
                    'status' => 'success',
                    'message' => 'Player registered successfully for the tournament.'
                ];
                http_response_code(200); 
            }

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


    public function getHistory($registration_id) {
        $registration_id = $this->tournamentModel->getRegister($registration_id);
        return $registration_id;
    }

    /**
     * Endpoint to get registered tournament details for a user.
     * GET method expected.
     * 
     * @param int $registration_id
     * @return void
     */
    public function getUser()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $response = [];
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("User session not found.");
            }

            $registerTournament = $this->getHistory($_SESSION['user_id']);

            if (!empty($registerTournament)) {
                $response = [
                    'status' => 'success',
                    'registerTournament' => $registerTournament
                ];
                http_response_code(200);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Registration not found.'
                ];
                http_response_code(404);
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
    } else {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    }
}
}
