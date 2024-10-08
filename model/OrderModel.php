<?php
require_once './database/Database.php';

class OrderModel extends Database {
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
        var_dump($order_id);
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

   /**
     * Get orders by user ID with detailed information.
     * 
     * @param int $user_id ID of the user.
     * @return array Order history details.
     */
    public function getOrdersByUserId(int $user_id): array {
        $query = "
            SELECT 
                o.id AS orderId,
                o.order_date AS orderDate,
                o.total_amount AS total,
                o.status,
                a.phone_number AS phoneNumber,
                a.name AS name,
                CONCAT(a.address, ', ', a.locality, ', ', a.city, ', ', a.state, ' - ', a.pincode) AS address,
                GROUP_CONCAT(p.name ORDER BY p.name ASC) AS products
            FROM orders o
            INNER JOIN user_addresses a ON o.address_id = a.id
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.order_date DESC
        ";

        $this->db->query($query);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }

     /**
     * Get the count of orders by gender.
     * 
     * @return array Returns an associative array with the count of orders by gender.
     */
    public function getOrderCountByGender(): array {
        $query = "
            SELECT 
                u.gender, 
                COUNT(o.id) AS orderCount
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id
            GROUP BY u.gender
        ";

        $this->db->query($query);
        return $this->db->resultSet();
    }

public function getOrderCountByItems(): array {
    $query = "
        SELECT 
            p.category, 
            COUNT(oi.id) AS productCount
        FROM order_items oi
        INNER JOIN products p ON p.product_id = oi.product_id
        GROUP BY p.category
    ";

    $this->db->query($query);
    return $this->db->resultSet();
}
}
?>
