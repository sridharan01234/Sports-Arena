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
     * Add tournament 
     * 
     * This method is responsible for adding a new tournament to the database.
     * 
     * @return void
     */
    public function addTournament(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode the request data
            $data = $this->decodeRequest();
            $response = [];
            try {
                // Extract only date part from start_date and end_date
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                $end_date = date('Y-m-d', strtotime($data['end_date']));

                $requiredKeys = ['title', 'description', 'start_date', 'end_date', 'tournament_location', 'organizer_name', 'phone_number', 'email'];
                $missingKeys = [];

                foreach ($requiredKeys as $key) {
                    if (!array_key_exists($key, $data) || empty($data[$key])) {
                        $missingKeys[] = $key;
                    }
                }

                if (!empty($missingKeys)) {
                    echo json_encode([
                        'error' => 'Missing required keys: ' . implode(', ', $missingKeys),
                    ]);
                    exit;
                }

                $details = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'tournament_location' => $data['tournament_location'],
                    'organizer_name' => $data['organizer_name'],
                    'phone_number' => $data['phone_number'],
                    'email' => $data['email'],
                ];

                // Check if tournament with the same title and location already exists
                $conflictingTournamentExists = $this->tournamentModel->userHasTournamentWithConflictingDate($details);
                if ($conflictingTournamentExists) {
                    throw new Exception("You already have a tournament with the same title and location for overlapping dates.");
                }

                $tournament_id = $this->tournamentModel->createTournament($details);

                // Updated code for handling profile picture upload with error handling, permission fix, and database update
                if ($_FILES['tournament_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '/home/asplap1937/github/be/assets/tournament_image/';

                    $fileExtension = pathinfo($_FILES['tournament_image']['name'], PATHINFO_EXTENSION);
                    $uploadFile = $uploadDir . $tournament_id . '.' . $fileExtension;

                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
                        if ($this->tournamentModel->updateTournamentImage(
                            $tournament_id,
                            $uploadFile
                        )) {
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Tournament image updated successfully.',
                            ]);
                            exit;
                        } else {
                            echo json_encode([
                                throw new Exception("Failed to update tournament image."),
                            ]);
                            exit;
                        }
                    } else {
                        echo json_encode([
                            throw new Exception("Failed to upload profile picture."),
                        ]);
                        exit;
                    }
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
            http_response_code(405);
        }
    }

    /**
     * Get upcoming tournaments.
     * This method fetches tournaments where the end date is in the future (after current time).
     * Adjust the logic according to your database structure and date comparison needs.
     * 
     * @return void
     */
    public function getTournaments(): void
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
                'data' => $upcomingTournaments,
            ];
        } catch (Exception $e) {
            // Handle exceptions
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
        }

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Register for a tournament.
     * This method registers a player for a tournament.
     * 
     * @return void
     */
    public function registerTournament(): void
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
                    'phone_number' => $_POST['phone_number'],
                ];

                // Check if the player already exists in the tournament
                $playerExists = $this->tournamentModel->isPlayerRegistered($details['registration_id'], $details['email'], $details['player_name']);

                if ($playerExists) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Player is already registered for this tournament.',
                    ];
                    http_response_code(409);
                } else {
                    $this->tournamentModel->addPlayer($details);
                    $response = [
                        'status' => 'success',
                        'message' => 'Player registered successfully for the tournament.',
                    ];
                    http_response_code(200);
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
}
