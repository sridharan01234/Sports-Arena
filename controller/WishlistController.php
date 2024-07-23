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

            $data = [];
            foreach($products as $product) {
                $product->main_image = $this->imageToBase64($product->main_image);
                $data[] = $product;
            }

            echo json_encode([
                'status' => 'success',
                'data' => $data
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

            echo json_encode(
                $data
            );
            exit;
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

        /**
     * Convert image to base64
     *
     * @param string $path
     *
     * @return string
     */
    public function imageToBase64(?string $path): string
    {
        if(is_null($path) || !$path) return '';
        $imageData = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    }
}
