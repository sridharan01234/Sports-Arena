<?php

require_once './database/Database.php';
require './helper/SessionHelper.php';

class WishlistModel extends Database
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Add item to wishlist
     *
     * @param int $userId
     * @param int $productId
     * @return void
     */
    public function addWishlist(int $userId, string $productId): void
    {
        $existingItem = $this->db->get('wishlists', [
            'user_id' => $userId,
            'product_id' => $productId
        ], ['wishlist_id']);

        if ($existingItem) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Product already in wishlist'
            ]);
            exit;
        } else {
            $this->db->insert('wishlists', [
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        }
    }

    /**
     * Get the wishlist items for the user
     *
     * @param int $userId
     * @return array
     */
    public function getWishlistItems(int $userId): array
    {
        $wishlistItems = $this->db->getAll('wishlists', [
            'user_id' => $userId
        ], ['product_id']);

        $products = [];
        foreach ($wishlistItems as $wishlistItem) {
            $product = $this->db->get('products', [
             'product_id' => $wishlistItem->product_id 
            ], ['name', 'price', 'main_image', 'product_id', 'description', 'stock', 'category']);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Remove item from wishlist
     *
     * @param int $userId
     * @param int $productId
     * @return void
     */
    public function removeItemFromWishlist(int $userId, string $productId): void
    {
        $this->db->delete('wishlists', [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    /**
     * Clear all items from wishlist
     *
     * @param int $userId
     * @return void
     */
    public function clearWishlist(int $userId): void
    {
        $this->db->delete('wishlists', [
            'user_id' => $userId
        ]);
    }
}
