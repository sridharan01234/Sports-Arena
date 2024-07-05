<?php
require_once './model/ProductsModel.php';
require_once './model/TurfModel.php';
require_once './helper/JWTHelper.php';
require './helper/SessionHelper.php';
require_once 'BaseController.php';

class AdminController extends BaseController {
    private $productModel;
    private $turfModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->turfModel = new TurfModel();
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();
            $response = [];
            try {
                $user_id = $_SESSION['user_id'] ?? null;

                if ($user_id === null || !$this->getAdmin($user_id)) {
                    throw new Exception('Only admins are allowed to add products');
                }
                // $required_fields = ['name', 'description', 'price', 'stock', 'category'];
                // foreach ($required_fields as $field) {
                //     if (empty($data[$field])) {
                //         throw new Exception("Field '$field' is required");
                //     }
                // }

                $details = [
                    'admin_id' => $_SESSION['user_id'],
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'stock' => $data['stock'],
                    'category' => $data['category'],
                    'main_image' => $data['mainImage'],
                ];

                $result = $this->productModel->addProduct($details);
                if ($result) {
                    $response = [
                        var_dump($result),
                        'status' => 'success',
                        'message' => 'Product added successfully'
                    ];
                    http_response_code(200);
                } else {
                    throw new Exception('Failed to add product');
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
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function addTurf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();
            $response = [];
            try {
                $user_id = $_SESSION['user_id'] ?? null;
                echo $_SESSION['user_id'];
                if ($user_id === null || !$this->isAdmin($user_id)) {
                    throw new Exception('Only admins are allowed to add turfs');
                }

                $required_fields = ['name', 'location', 'image_url', 'details'];
                foreach ($required_fields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("Field '$field' is required");
                    }
                }

                $details = [
                    'name' => $data['name'],
                    'location' => $data['location'],
                    'image_url' => $data['imageUrl'],
                    'details' => $data['details']
                ];

                $result = $this->turfModel->addTurf($details);
                if ($result) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Turf added successfully'
                    ];
                    http_response_code(200);
                } else {
                    throw new Exception('Failed to add turf');
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
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    private function isAdmin($user_id) {
        return $this->turfModel->isAdmin($user_id);
    }

    private function getAdmin($user_id) {
        return $this->productModel->isAdmin($user_id);
    }
}
?>