<?php

/**
 * User model
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

require_once './interface/BaseInterface.php';
require_once './database/Database.php';

class UserModel implements BaseInterface
{
    /**
     * Database instance
     *
     * @var Database
     */
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get user with id
     *
     * @param int $id
     *
     * @return bool | object
     */
    public function getUser(int $id): bool | object
    {
        return $this->db->get('users', ['user_id' => $id], []);
    }

    /**
     * Update user with id
     *
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function updateUser(int $id, array $data): bool
    {
        return $this->db->update('users', $data, ['user_id'=> $id]);
    }

    /**
     * Delete user with id
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->db->delete('users', ['user_id' => $id]);
    }

    /**
     * Get countries
     *
     * @return array
     */
    public function getCountries(): array
    {
        return $this->db->getAll('countries', [], []);
    }

    /**
     * Get states\
     * 
     * @param int $country_id
     *
     * @return array
     */
    public function getStates(int $country_id): array
    {
        return $this->db->getAll('states', ['country_id' => $country_id], []);
    }

    /**
     * Get cities
     *
     * @param int $state_id
     *
     * @return array
     */
    public function getCities(int $state_id): array
    {
        return $this->db->getAll('cities', ['state_id' => $state_id], []);
    }
}
