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
            $data[$key]->productMainImage = $this->imageToBase64($data[$key]->productMainImage);

        }

        echo json_encode(
            [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ], 
            JSON_UNESCAPED_SLASHES
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
                'image' => $this->imageToBase64($data->productMainImage)
            ],
            JSON_UNESCAPED_SLASHES
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

        if($this->model->addProduct($data))
        {
            echo json_encode(
                [
                    'status' => 'success',
                    'message' => 'product added successfully'
                ]
            );
            exit;
        }
        else
        {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'product not added successfully'
                ]
            );
            exit;
        }
    }

    public function getImage()
    {

    }

    /**
     * Convert image to base64
     *
     * @param string $path
     *
     * @return string
     */
    public function imageToBase64(?string $path): string
    {
        if(is_null($path) || !$path) return '';
        $imageData = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    }
}
