<?php

require_once './model/WishlistModel.php';

class WishlistController
{
    private $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
    }

    /**
     * Add item to wishlist
     *
     * @param int $userId
     * @param int $itemId
     * @return void
     */
    public function addItemToWishlist(int $userId, int $itemId): void
    {
        $success = $this->wishlistModel->addItemToWishlist($userId, $itemId);

        if ($success) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Item added to wishlist successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add item to wishlist',
            ]);
        }
    }

    /**
     * Get wishlist items for a user
     *
     * @param int $userId
     * @return void
     */
    public function getWishlistItems(int $userId): void
    {
        $wishlistItems = $this->wishlistModel->getWishlistItems($userId);

        echo json_encode([
            'status' => 'success',
            'data' => $wishlistItems,
        ]);
    }
}
