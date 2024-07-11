<?php

/**
 *
 *
 */

require_once './model/TurfsModel.php';
require_once 'BaseController.php';
require_once './helper/SessionHelper.php';
require_once './helper/JWTHelper.php';

class ProductController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new TurfsModel();
    }

    /**
     * Get all turfs
     *
     * @return void
     */
    public function getAll(): void
    {
        $data = $this->model->get_all_turfs();

        foreach ($data as $key => $value) {
            $data[$key] = $this->correctNaming($value);
        }

        echo json_encode(
            [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ]
        );
        exit;
    }

    /**
     * Get turf by id
     *
     * @return void
     */
    public function getById(): void
    {
        $data = $this->model->get_turf($_GET['id']);

        $data = $this->correctNaming($data);
        $data->productImages = $this->model->get_turf_images($_GET['id']);

        echo json_encode(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
        exit;
    }

    public function bookTurf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();
            $response = [];
    
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("User session not found.");
            }
    
            try {
                $userId = $_SESSION['user_id'];
                $turfId = $data['turfId'];
                $turfLocation = $data['turfLocation'];
                $turfDate = $data['turfDate'];
                $turfStarts = $data['turfStarts']; 
                $turfEnds = $data['turfEnds']; 
    
                $conflictingSlots = $this->model->getConflictingSlots($turfId, $turfDate, $turfStarts, $turfEnds);
    
                if (!empty($conflictingSlots)) {
                    $conflictingSlotStr = implode(', ', array_map(function ($slot) {
                        return "{$slot['slot_start']}-{$slot['slot_end']}";
                    }, $conflictingSlots));
    
                    $response = [
                        'status' => 'error',
                        'message' => "Time slot(s) $conflictingSlotStr are already booked for this turf on $turfDate."
                    ];
                    http_response_code(409);
                } else {
                    $bookingDetails = [];
                    foreach ($turfStarts as $index => $start) {
                        $end = $turfEnds[$index];
    
                        $bookingDetails[] = [
                            'user_id' => $userId,
                            'turf_id' => $turfId,
                            'player_name' => $data['playerName'],
                            'turf_location' => $turfLocation,
                            'email' => $data['email'],
                            'turf_date' => $turfDate,
                            'slot_start' => $start,
                            'slot_end' => $end
                        ];
                    }
    
                    foreach ($bookingDetails as $booking) {
                        $this->model->bookTurf($booking);
                    }
    
                    $response = [
                        'status' => 'success',
                        'message' => 'User registered successfully for the turf.'
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

