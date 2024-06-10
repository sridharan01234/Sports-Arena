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
}
