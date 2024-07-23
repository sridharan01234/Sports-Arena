<?php
require_once './database/Database.php';

class AddressModel extends Database {
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
        return $this->db->insertWithLastId('user_addresses', $addressDetails);
    }
    
    /**
     * Get user's address by address ID.
     * 
     * @param int $address_id ID of the address to fetch.
     * @return object|null Returns address details as an object if found, null otherwise.
     */
    public function getAddresses(int $user_id): array {
        $query = "
            SELECT * FROM user_addresses
            WHERE user_id = :user_id
        ";

        $this->db->query($query);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }

    public function getAddress(int $address_id): ?object {
        $query = "SELECT * FROM user_addresses WHERE id = :address_id";
        $this->db->query($query);
        $this->db->bind(':address_id', $address_id);
        return $this->db->single();
    }

    /**
     * Example method: Update user's address details.
     * 
     * @param int $address_id ID of the address to update.
     * @param array $newDetails New details to update for the address.
     * @return bool Returns true if update is successful, false otherwise.
     */
    public function updateAddress(int $address_id, array $newDetails): bool {
        return $this->db->update('user_addresses', $newDetails, ['id' => $address_id]);
    }

    /**
     * Example method: Delete user's address by address ID.
     * 
     * @param int $address_id ID of the address to delete.
     * @return bool Returns true if deletion is successful, false otherwise.
     */
    public function deleteAddress(int $address_id): bool {
        return $this->db->delete('user_addresses', ['id' => $address_id]);
    }
}
?>
