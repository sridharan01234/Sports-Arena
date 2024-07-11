<?php

/**
 * Interface BaseInterface
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 3-05-2024
 */

require './helper/SessionHelper.php';

interface BaseInterface
{
    public function getUser(int $id);

    public function updateUser(int $id, array $data);

    public function deleteUser(int $id);
}
