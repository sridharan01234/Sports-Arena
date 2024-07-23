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
     * ValidateCart method
     *
     * @param array $data
     *
     * @return array
     */
    public function validateCart(array $data): array
    {
        
        $error = [];
        if (!isset($data['productId']) || empty($data['productId'])) {
            $error[] = 'Product ID is required';
        }
        return $error;
        ;
    }

    /**
     * Update cart items
     *
     * @return void
     */
    public function updateCart(): void
    {
        if(!$_SESSION['user_id'])
        {
            echo json_encode(
                [
                    'status'=> 'fail',
                    'data'=> 'Cart items cleared',
                ]
            );
            exit;
        }
        $data = $this->decodeRequest();
        $error = $this->validateCart($data);
        if (!empty($error)) {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => implode(' ,', $error),
                ]
            );
            exit;
        }
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
        if(!$_SESSION['user_id'])
        {
            echo json_encode(
                [
                    'status'=> 'fail',
                    'data'=> 'Cart items cleared',
                ]
            );
            exit;
        }
        $data = $this->cartModel->getCart();
        $products = [];
        foreach ($data as $product) {
            $product->productMainImage = $this->imageToBase64($product->productMainImage);
            $product = $this->correctNaming($product);
            $products[] = $product;
        }
        echo json_encode(
            [
                'status' => 'success',
                'data' => $products,
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
        if ($this->cartModel->removeCart($data['productId'], $data['productSize'])) {
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

    /**
     * Clear cart items
     *
     * @return void
     */
    public function clearCart(): void
    {
        $user_id = $_SESSION['user_id'];
        if(!$_SESSION['user_id'])
        {
            echo json_encode(
                [
                    'status'=> 'fail',
                    'data'=> 'Cart items cleared',
                ]
            );
            exit;
        }
        $this->cartModel->clearCart();
        echo json_encode(
            [
                'status'=> 'success',
                'data'=> 'Cart items cleared',
            ]
        );
        exit;
    }

    public function imageToBase64(?string $path): string
    {
        if(is_null($path) || !$path) return '';
        $imageData = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    }
}
