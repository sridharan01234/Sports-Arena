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
                $details = [
                    'admin_id' => $_SESSION['user_id'],
                    'name' => $data['productName'],
                    'price' => $data['productPrice'],
                    'description' => $data['productDescription'],
                    'stock' => $data['productStock'],
                    'category' => $data['productCategory'],
                    'main_image' => $this->decodeImage($data['productImage'], $data['productName'], './assets/product_pictures/')
                ];

                $result = $this->productModel->addProduct($details);
                if ($result) {
                    $response = [
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
                if ($user_id === null || !$this->isAdmin($user_id)) {
                    throw new Exception('Only admins are allowed to add turfs');
                }

                $details = [
                    'admin_id' => $_SESSION['user_id'],
                    'name' => $data['name'],
                    'location' => $data['location'],
                    'image_url' => $data['imageUrl'],
                    'owner' => $data['owner'],
                    'email' => $data['email'],
                    'phonenumber' => $data['contactNumber']
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

    public function decodeImage(string $image, string $filename, string $path): string|bool
    {
        // Match and extract the image type from the base64 string
        if (preg_match('#^data:image/(\w+);base64,#i', $image, $matches)) {
            $extension = $matches[1]; // e.g., 'png', 'jpg', etc.
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
    
            // Decode the base64 string
            $tempData = base64_decode($image);
            if ($tempData === false) {
                error_log('Failed to decode base64 string.');
                return false;
            }
    
            // Ensure the path ends with a directory separator
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    
            // Check if the directory exists, if not create it
            if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
                error_log('Failed to create directory: ' . $path);
                return false;
            }
    
            // Append the correct extension to the filename
            $fullPath = $path . $filename . '.' . $extension;
    
            // Save the decoded image to the specified path
            if (file_put_contents($fullPath, $tempData) === false) {
                error_log('Failed to write file: ' . $fullPath);
                return false;
            }
    
            return $fullPath;
        }
    
        // Return false if the image type could not be extracted
        error_log('Failed to extract image type from base64 string.');
        return false;
    }
    
    
    
}
?>