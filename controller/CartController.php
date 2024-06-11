<?php

/**
 * CartController class
 * 
 * @author Sridharan
 */

require './model/CartModel.php';
require_once 'BaseController.php';

class CartController extends BaseController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    /**
     * Update cart items
     * 
     * @return void
     */
    public function updateCart(): void           
    {
        $data = $this->decodeRequest();
        $this->cartModel->add($data);
        echo json_encode(
            [
                'status' => 'success',
                'data' => 'Product added to cart',
            ]
        );
        exit;
    }

    /**
     * Get cart items
     * 
     * @return void
     */
    public function getCart(): void
    {
        $data = $this->cartModel->getCart();
        foreach ($data as $key => $product) {
            $data[] = $this->correctNaming($product);
        }
        echo json_encode(
            [
                'status' => 'success',
                'data' => $data,
            ]
        );
        exit;
    }

    /**
     * Remove cart item
     * 
     * @return void
     */
    public function removeCart(): void
    {
        $data = $this->decodeRequest();
        if ($this->cartModel->removeCart($data['productId'])) {
            echo json_encode(
                [
                    'status' => 'success',
                    'data' => 'Product removed from cart',
                ]
            );
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'data' => 'Product not removed from cart',
                ]
            );
        }
        exit;
    }
}
