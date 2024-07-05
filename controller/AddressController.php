<?php
require_once './model/AddressModel.php';
require_once 'BaseController.php';
require_once './helper/JWTHelper.php';
require_once './helper/SessionHelper.php';

class AddressController extends BaseController {

    private $userAddressModel;

    public function __construct() {
        $this->userAddressModel = new AddressModel();
    }

/**
     * Endpoint to get order history for a user.
     * GET method expected.
     * Required parameter: user_id (in query string).
     * 
     * @return void
     */
    public function getAddress() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];

            try {
                // Fetch order history
                $getAddress = $this->getAddress($_SESSION['user_id']);

                $response = [
                    'status' => 'success',
                    'address' => $getAddress 
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
}