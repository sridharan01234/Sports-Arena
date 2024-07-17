<?php
require_once './model/WishlistModel.php';
require_once 'BaseController.php';
require_once './helper/SessionHelper.php';
require_once './helper/JWTHelper.php';

class WishlistController extends BaseController
{
    private $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
    }

    /**
     * Add item to wishlist
     * Expects a POST request with 'item_id' in the request body
     * 
     * @return void
     */
    public function addItemToWishlist(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $data = $this->decodeRequest();

            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User session not found');
            }

            $userId = $_SESSION['user_id'];

            if (!isset($data['itemId'])) {
                throw new Exception('item_id is required');
            }

            $itemId = (int) $data['itemId'];
            $success = $this->wishlistModel->addItemToWishlist($userId, $itemId);

            if ($success) {
                $response = [
                    'status' => 'success',
                    'message' => 'Item added to wishlist successfully',
                ];
                http_response_code(200);
            } else {
                throw new Exception('Failed to add item to wishlist');
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
        }
        echo json_encode($response);
    }

    /**
     * Get wishlist items for a user
     * 
     * @return void
     */
    public function getWishlistItems(): void
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User session not found');
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method');
            }

            $userId = $_SESSION['user_id'];
            $wishlistItems = $this->wishlistModel->getWishlistItems($userId);

            $response = [
                'status' => 'success',
                'data' => $wishlistItems,
            ];
            http_response_code(200);
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
        }
        echo json_encode($response);
    }

    /**
     * Delete item from wishlist
     * Expects a DELETE request with 'item_id' in the request body
     * 
     * @return void
     */
    public function deleteItemFromWishlist(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                throw new Exception('Invalid request method');
            }

            $data = $this->decodeRequest();

            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User session not found');
            }

            $userId = $_SESSION['user_id'];

            if (!isset($data['itemId'])) {
                throw new Exception('item_id is required');
            }

            $itemId = (int) $data['itemId'];
            $success = $this->wishlistModel->deleteItemFromWishlist($userId, $itemId);

            if ($success) {
                $response = [
                    'status' => 'success',
                    'message' => 'Item deleted from wishlist successfully',
                ];
                http_response_code(200);
            } else {
                throw new Exception('Failed to delete item from wishlist');
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            http_response_code(500);
        }
        echo json_encode($response);
    }
}


