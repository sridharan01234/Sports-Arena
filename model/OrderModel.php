<?php
require_once './database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Create a new order with associated items.
     * 
     * @param array $orderDetails Details of the order.
     * @param array $orderItems List of items in the order.
     * @return int|false Returns the ID of the newly created order if successful, false otherwise.
     */
    public function createOrder(array $orderDetails, array $orderItems): int|false {
        $order_id = $this->db->insertWithLastId('orders', $orderDetails);
        if (!$order_id) {
            return false;
        }

        foreach ($orderItems as $item) {
            $item['order_id'] = $order_id;
            $orderItemId = $this->db->insertt('order_items', $item);
            if (!$orderItemId) {
                return false;
            }
        }

        return $order_id;
    }

    /**
     * Get product price by product ID.
     * 
     * @param int $product_id ID of the product.
     * @return float|false Returns the price of the product if found, false otherwise.
     */
    public function getProductPrice(int $product_id): float|false {
        $product = $this->db->gett('products', ['product_id' => $product_id], ['price']);
        return $product ? (float)$product->price : false;
    }

    public function getOrdersByUserId(int $user_id): array {
        $orders = $this->db->getAll('orders', ['user_id' => $user_id], []);
        return $orders;
    }
}
?>
