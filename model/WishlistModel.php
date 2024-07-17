<?php

require_once './database/Database.php';

class WishlistModel extends Database
{
    /**
     * Add item to wishlist
     *
     * @param int $userId
     * @param int $itemId
     * @return bool true if item added successfully, false otherwise
     */
    public function addItemToWishlist(int $userId, int $itemId): bool
    {
        $data = [
            'user_id' => $userId,
            'item_id' => $itemId,
        ];

        return $this->insert('wishlists', $data);
    }

    /**
     * Get wishlist items for a user
     *
     * @param int $userId
     * @return array wishlist items
     */
    public function getWishlistItems(int $userId): array
    {
        $condition = ['user_id' => $userId];
        return $this->getAll('wishlists', $condition, []);
    }

    /**
     * Delete item from wishlist
     *
     * @param int $userId
     * @param int $itemId
     * @return bool true if item deleted successfully, false otherwise
     */
    public function deleteItemFromWishlist(int $userId, int $itemId): bool
    {
        $condition = [
            'user_id' => $userId,
            'item_id' => $itemId,
        ];

        return $this->delete('wishlists', $condition);
    }
}
