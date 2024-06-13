<?php

/**
 * Base Controller class
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

require './helper/SessionHelper.php';

abstract class BaseController
{
    /**
     * Decodes raw data
     *
     * @return array|null
     */
    public function decodeRequest(): array | bool | null
    {
        // Access the raw POST data
        $raw_data = file_get_contents('php://input');

        // Parse the JSON data
        return json_decode($raw_data, true);
    }


    /**
     * Correct naming
     *
     * @param object $data
     *
     * @return object
     */
    protected function correctNaming(object $data): object
    {
        $data->productId = $data->product_id;
        unset($data->product_id);

        $data->productName = $data->name;
        unset($data->name);

        $data->productMainImage = $data->main_image;
        unset($data->main_image);

        $data->productPrice = (int)$data->price;
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

    /**
     * Correct tournment naming
     * 
     * @param object $data
     * 
     * @return object
     */
    protected function correctTournmentNaming(object $data): object
    {
        $data->tournamentName = $data->title;
        unset($data->title);

        $data->tournamentLocation = $data->location;
        unset($data->location);

        $data->tournamentStartDate = $data->start_date;
        unset($data->start_date);

        $data->tournamentEndDate = $data->end_date;
        unset($data->end_date);

        $data->tournamentOrganizerName = $data->organizer_name;
        unset($data->organizer_name);

        $data->tournamentOrganizerEmail = $data->organizer_email;
        unset($data->organizer_email);

        $data->tournamentOrganizerPhoneNumber = $data->organizer_phone_number;
        unset($data->organizer_phone_number);

        $data->tournamentImage = $data->image;
        unset($data->image);

        $data->tournamentDescription = $data->description;
        unset($data->description);

        return $data;
    }
}
