<?php

/**
 *
 *
 */

require './model/ProductModel.php';
require_once 'BaseController.php';

class ProductController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    /**
     * Get all products
     *
     * @return void
     */
    public function getAll(): void
    {
        $data = $this->model->get_all_products();

        foreach ($data as $key => $value) {
            $data[$key] = $this->correctNaming($value);
        }

        echo json_encode(
            [
                'status' => 200,
                'message' => 'success',
                'data' => $data,
            ]
        );
        exit;
    }

    /**
     * Get product by id
     *
     * @return void
     */
    public function getById(): void
    {
        $data = $this->model->get_product($_GET['id']);

        $data = $this->correctNaming($data);
        $data->productImages = $this->model->get_product_images($_GET['id']);
        $data->productSize = $this->model->get_product_size($_GET['id']);

        echo json_encode(
            [
                'status' => 'success',
                'data' => $data,
            ]
        );
        exit;
    }

    /**
     * Add product
     *
     * @return void
     */
    public function addProduct()
    {
        $data = $this->decodeRequest();

        $details = [
            'category' => $data['productCategory'],
            'description' => $data['productDescription'],
            'main_image' => $data['productMainImage'],
            'name' => $data['productName'],
            'price' => $data['productPrice'],
            'stock' => $data['productStock'],
        ];

        if ($this->model->addProduct($details)) {
            echo json_encode(
                [
                    'status' => 'success',
                    'message' => 'product added successfully',
                ]
            );
            exit;
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'product not added successfully',
                ]
            );
            exit;
        }
    }
}
