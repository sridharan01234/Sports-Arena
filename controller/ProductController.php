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

    /**
     * Correct naming
     *
     * @param object $data
     *
     * @return object
     */
    private function correctNaming(object $data)
    {
        $data->productId = $data->product_id;
        unset($data->product_id);

        $data->productName = $data->name;
        unset($data->name);

        $data->productMainImage = $data->main_image;
        unset($data->main_image);

        $data->productPrice = $data->price;
        unset($data->price);

        $data->productDescription = $data->description;
        unset($data->description);

        $data->productSize = $data->size;
        unset($data->size);

        $data->productCategory = $data->category;
        unset($data->category);

        $data->productStock = $data->stock;
        unset($data->stock);

        return $data;
    }

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    /**
     * Get all products
     *
     * @return void
     */
    public function getAll()
    {
        $data = $this->model->get_all_products();

        echo json_encode(
            [
                'status' => 200,
                'message' => 'success',
                'data' => $this->correctNaming($data)
            ]
        );
        exit;
    }

    /**
     * Get product by id
     *
     * @return void
     */
    public function getById()
    {
        $data = $this->model->get_product($_GET['id']);

        $data = $this->correctNaming($data);
        $data->productImages = $this->model->get_product_images($_GET['id']);
        $data->productSize = $this->model->get_product_size($_GET['id']);

        echo json_encode(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
        exit;
    }
}
