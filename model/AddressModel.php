<?php
require_once './database/Database.php';

class AddressModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Add a new address for a user.
     * 
     * @param array $addressDetails Details of the address to be added.
     * @return int|false Returns the ID of the newly added address if successful, false otherwise.
     */
    public function addUserAddress(array $addressDetails): int|false {
        return $this->db->insert('user_addresses', $addressDetails);
    }

    /**
     * Get user's address by address ID.
     * 
     * @param int $address_id ID of the address to fetch.
     * @return object|null Returns address details as an object if found, null otherwise.
     */
    public function getAddress(int $address_id): object|null {
        return $this->db->get('user_addresses', ['id' => $address_id], []);
    }
}

?>
