<?php

/**
 *
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
     * Update cart
     */
    public function updateCart()
    {
        $data = $this->decodeRequest();
        $this->cartModel->add($data);
    }
}
