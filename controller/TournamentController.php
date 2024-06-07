<?php
require_once './model/TournamentModel.php';

class TournamentController {
    private $tournamentModel;

    public function __construct() {
        $this->tournamentModel = new Tournament_Model();
    }

    public function addTournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [];

            try {
                $details = [
                    'user_id' => $_POST['user_id'],
                    'title' => $_POST['title'],
                    // 'location' => $_POST['location'],
                    'description' => $_POST['description'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date']
                ];
            
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
    public function getTournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];
    
            try {
                $tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : null;
                if ($tournament_id !== null) {
                    $tournament = $this->tournamentModel->getTournament($tournament_id);
                    if ($tournament) {
                        $response = [
                            'status' => 'success',
                            'data' => $tournament
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Tournament not found.'
                        ];
                        http_response_code(404);
                    }
                } else {
                    $tournaments = $this->tournamentModel->getTournament();
                    $response = [
                        'status' => 'success',
                        'data' => $tournaments
                    ];
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

    public function registerTournament() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [];
    
            try {
                $details = [
                    'registration_id' => $_POST['registration_id'],
                    'tournament_id' => $_POST['tournament_id'],
                    'player_name' => $_POST['player_name'],
                    'team_name' => $_POST['team_name'],
                    'email' => $_POST['email']
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
                        'message' => 'Tournament booked successfully.'
                    ];
                    http_response_code(200); 
                }
    
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500); // Internal Server Error
            }
    
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
