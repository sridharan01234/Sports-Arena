<?php

/**
 *
 */

require "./database/Database.php";

class ProductModel extends Database
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all products
     *
     * @return array
     */
    public function get_all_products(): array
    {
        return $this->db->getAll('products', [], []);
    }

    /**
     * Get product by id
     *
     * @param int $id
     *
     * @return object | bool
     */
    public function get_product(int $id): object | bool
    {
        return $this->db->get('products', ['product_id' => $id], []);
    }

    /**
     * Get product images by product id
     *
     * @param int $id
     *
     * @return array
     */
    public function get_product_images(int $id): array
    {
        return $this->db->getAll('product_images', ['product_id' => $id], ['imageUrl']);
    }

    /**
     * Get product size
     *
     * @param int $id
     *
     * @return array
     */
    public function get_product_size(int $id): array
    {
        return $this->db->getAll('product_size', ['product_id' => $id], ['size']);
    }
}
