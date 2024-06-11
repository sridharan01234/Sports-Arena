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

        if (
            $this->db->get('cart_items', [
                'cart_id' => $cart_id,
                'product_id' => $data['productId']
            ], [])
        ) {
            $this->db->update("cart_items", [
                "quantity" => $data["quantity"],
            ], [
                "cart_id" => $cart_id,
                "product_id" => $data["productId"],
            ]);
        } else {
            $this->db->insert("cart_items", [
                "cart_id" => $cart_id,
                "product_id" => $data["productId"],
            ]);
        }
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
        if (
            !$this->db->get("cart", [
                "user_id" => $_SESSION["user_id"],
            ], [])
        ) {
            $this->db->insert("cart", [
                "user_id" => $_SESSION["user_id"],
            ]);
        }
    }

    /**
     * Get the cart items for the user
     *
     * @return array
     */
    public function getCart(): array
    {
        $cart_id = $this->findCart();
        $cart_items = $this->db->getAll("cart_items", [
            "cart_id" => $cart_id,
        ], []);
        $products = [];
        foreach ($cart_items as $cart_item) {
            $product = $this->db->get("products", [
                "product_id" => $cart_item->product_id,
            ], []);
            $product->quantity = $cart_item->quantity;
            $products[] = $product;
        }
        return $products;
    }

    /**
     *
     */
    public function removeCart(string $productId)
    {
        $cart_id = $this->findCart();
        if (
            $this->db->delete("cart_items", [
                "cart_id" => $cart_id,
                "product_id" => $productId,
            ])
        ) {
            return true;
        } else {
            return false;
        }
    }
}
