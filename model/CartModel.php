<?php

/**
 *
 */

require_once './interface/BaseInterface.php';
require './database/Database.php';

class CartModel extends Database
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Add a product to the cart
     *
     * @param array $data
     *
     * @return void
     */
    public function add(array $data)
    {
        $cart_id = $this->findCart();

        $this->db->insert("cart_items", [
            "user_id" => $_SESSION["user_id"],
            "cart_id" => $cart_id,
            "product_id" => $data["product_id"],
            "quantity" => $data["quantity"],
        ]);
    }

    /**
     * Find the cart id for the user
     *
     * @return int
     */
    private function findCart(): int
    {
        $cart_id = $this->db->get("cart", [
            "user_id" => $_SESSION["user_id"],
        ], [])->cart_id;
        if ($cart_id) {
            return $cart_id;
        } else {
            $this->createCart();
            return $this->findCart();
        }
    }

    /**
     * Create a new cart for the user
     *
     * @return void
     */
    private function createCart()
    {
        if (!$this->db->get("cart", [
            "user_id" => $_SESSION["user_id"],
        ], [])) {
            $this->db->insert("cart", [
                "user_id" => $_SESSION["user_id"],
            ]);
        }
    }
}
