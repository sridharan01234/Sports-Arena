<?php

/**
 * WishlistController class
 *
 * Manages wishlist operations
 */

require './model/WishlistModel.php';
require_once 'BaseController.php';

class WishlistController extends BaseController
{
    private $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
    }

    /**
     * Add item to wishlist
     *
     * @return void
     */
    public function addWishlist(): void
    {
        $data = $this->decodeRequest();

        if (!isset($data['productId']) || empty($data['productId'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Product ID is required'
            ]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        if ($userId === null) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not logged in'
            ]);
            exit;
        }

        $this->wishlistModel->addWishlist($userId, $data['productId']);
        echo json_encode([
            'status' => 'success',
            'message' => 'Product added to wishlist'
        ]);
        header('Content-Type: application/json');
        exit;
    }

    /**
     * Get wishlist items
     *
     * @return void
     */
    public function getWishlist(): void
    {
        try {
            $userId = $_SESSION['user_id'];

            if (!$userId) {
                throw new Exception('User session not found');
            }

            $products = $this->wishlistModel->getWishlistItems($userId);

            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
            header('Content-Type: application/json');
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Remove item from wishlist
     *
     * @return void
     */
    public function removeWishlist(): void
    {
        try {
            $data = $this->decodeRequest();
            $userId = $_SESSION['user_id'];

            if (!$userId) {
                throw new Exception('User session not found');
            }

            if (empty($data['productId'])) {
                throw new Exception('Product ID is required');
            }

            $this->wishlistModel->removeItemFromWishlist($userId, $data['productId']);

            echo json_encode([
                'status' => 'success',
                'message' => 'Product removed from wishlist'
            ]);
            header('Content-Type: application/json');
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Clear all items from wishlist
     *
     * @return void
     */
    public function clearWishlist(): void
    {
        try {
            $userId = $_SESSION['user_id'];

            if (!$userId) {
                throw new Exception('User session not found');
            }

            $this->wishlistModel->clearWishlist($userId);

            echo json_encode([
                'status' => 'success',
                'message' => 'Wishlist cleared'
            ]);
            header('Content-Type: application/json');
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
