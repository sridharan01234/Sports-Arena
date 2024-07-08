<?php
require_once './database/Database.php';

class PaymentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addPayment(array $paymentDetails): int|false{
      return $this->db->insertWithLastId('order_payment', $paymentDetails);
    }
}