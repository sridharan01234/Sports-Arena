<?php
require_once './model/OrderModel.php';
require_once './model/AddressModel.php';
require_once './model/PaymentModel.php'; 
require_once './controller/CartController.php';
require_once 'BaseController.php';
require_once './helper/SessionHelper.php';
require_once './helper/JWTHelper.php';

class OrderController extends BaseController {

    private $orderModel;
    private $userAddressModel;
    private $paymentModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->userAddressModel = new AddressModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * Endpoint to place a new order.
     * POST method expected.
     * Required fields: user_id, address_id, items (array of product_id and quantity), payment_method.
     * Clear the ordered items in cart
     * @return void
     */
    public function placeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();
    
            $response = [];
    
            try {
                // Validate session and user ID
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('User session not found.');
                }
                if (!isset($data['address_id'])) {
                    throw new Exception('Address ID is required.');
                }
    
                // Validate items array structure
                if (!isset($data['items']) || !is_array($data['items']) || empty($data['items'])) {
                    throw new Exception("Field 'items' must be a non-empty array");
                }
    
                // Validate payment method
                $allow_payment_methods = ['UPI','Cash on Delivery','Credit/Debit/ATM card'];
                if (!in_array($data['payment_method'], $allow_payment_methods)) {
                    throw new Exception("Invalid payment method");
                }
    
                // Fetch user address details
                $address = $this->userAddressModel->getAddress($data['address_id']);
                if (!$address) {
                    throw new Exception("User address not found");
                }
    
                // Calculate total amount and validate items
                $total_amount = 0;
                $orderItems = [];
                foreach ($data['items'] as $item) {
                    if (!isset($item['product_id']) || !isset($item['quantity'])) {
                        throw new Exception("Each item must have 'product_id' and 'quantity'");
                    }
                    // Fetch product price from database or calculate dynamically
                    $productPrice = $this->orderModel->getProductPrice($item['product_id']);
                    if (!$productPrice) {
                        throw new Exception("Product with ID {$item['product_id']} not found or price not available");
                    }
                    $total_amount += $productPrice * $item['quantity'];
    
                    // Prepare order item details
                    $orderItems[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $productPrice
                    ];
                }
    
                // Prepare payment details
                $paymentDetails = [
                    'user_id' => $_SESSION['user_id'],
                    'total_amount' => $total_amount,
                    'payment_method' => $data['payment_method'],
                ];
    
                // Process payment
                $payment_successful = $this->processPayment($total_amount, $data['payment_method'], $paymentDetails);
                if (!$payment_successful) {
                    throw new Exception("Payment failed");
                }
    
                // Prepare order details
                $orderDetails = [
                    'user_id' => $_SESSION['user_id'],
                    'address_id' => $data['address_id'],
                    'total_amount' => $total_amount,
                    'order_date' => date('Y-m-d H:i:s'), 
                    'status' => 'pending' 
                ];
    
                // Create order
                $order_id = $this->orderModel->createOrder($orderDetails, $orderItems);
                if (!$order_id) {
                    throw new Exception("Failed to place order");
                }
    
                // Add payment details
                $paymentDetails = [
                    'order_id' => $order_id,
                    'payment_method' => $data['payment_method'],
                    'payment_status' => 'Completed'
                ];
                $payment_id = $this->paymentModel->addPayment($paymentDetails);
                if (!$payment_id) {
                    throw new Exception("Failed to record payment");
                }
    
                // Get order history
                $orderHistory = $this->getOrderHistory($_SESSION['user_id']);
    
                $response = [
                    'status' => 'success',
                    'message' => 'Order placed successfully.',
                    'orderId' => $order_id,
                ];
                http_response_code(200);
    
                // Clear the ordered items in cart
                $cartController = new CartController();
                $cartController->clearCart();
    
            } catch (Exception $e) {
                error_log("Error placing order: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }
    
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }    
    
    /**
     * Retrieve the order history for a specific user.
     *
     * @param int $user_id User ID.
     * @return array Order history details.
     */
    public function getOrderHistory($user_id) {
        $orderHistory = $this->orderModel->getOrdersByUserId($user_id);
        return $orderHistory;
    }

     /**
     * Endpoint to get order history for a user.
     * GET method expected.
     * Required parameter: user_id (in query string).
     * 
     * @return void
     */
    public function orderHistory() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];

            try {
                $orderHistory = $this->getOrderHistory($_SESSION['user_id']);

                $response = [
                    'status' => 'success',
                    'orderHistory' => $orderHistory
                ];
                http_response_code(200);

            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Endpoint to add a new user address.
     * POST method expected.
     * Required fields: user_id, name, phone_number, pincode, locality, address, city, state.
     * Optional fields: landmark, alternate_phone_number.
     * 
     * @return void
     */
    public function addUserAddress() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->decodeRequest();

            $response = [];

            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('User session not found.');
                } 

                $addressDetails = [
                    'user_id' => $_SESSION['user_id'],
                    'name' => $data['name'],
                    'phone_number' => $data['phoneNumber'],
                    'pincode' => $data['pincode'],
                    'locality' => $data['locality'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'landmark' => isset($data['landmark']) ? $data['landmark'] : null,
                    'alternate_phone_number' => isset($data['alternatePhoneNumber']) ? $data['alternatePhoneNumber'] : null,
                ];

                $address_id = $this->userAddressModel->addUserAddress($addressDetails);
                if (!$address_id) {
                    throw new Exception("Failed to add user address");
                }

                $response = [
                    'status' => 'success',
                    'message' => 'User address added successfully.',
                    'address_id' => $address_id
                ];
                http_response_code(200);

            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Endpoint to fetch user address by address ID.
     * GET method expected.
     * Required parameter: address_id (in query string).
     * 
     * @return void
     */
    public function getAddress() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];
    
            try {
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception('User session not found.');
                }

                $userAddress = $this->userAddressModel->getAddresses($_SESSION['user_id']);
                if (!$userAddress) {
                    throw new Exception('Address ID not found or unauthorized.');
                }
    
                $response = [
                    'status' => 'success',
                    'address' => $userAddress 
                ];
                http_response_code(200);
    
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(404); 
                error_log("Error in getAddress endpoint: " . $e->getMessage());
            }
    
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    

    /**
    * Simulated function to process payment.
    * 
    * @param float $amount Total amount to be paid.
    * @param string $payment_method Payment method (e.g., 'credit_card', 'paypal').
    * @return bool Returns true if payment is successful, false otherwise.
    */
    private function processPayment(float $amount, string $payment_method, array $paymentDetails): bool {
        try {
            error_log("Processing payment of $amount using $payment_method and " . json_encode($paymentDetails));
            $success = true; 
            return $success;
        } catch (Exception $e) {
            error_log("Payment processing error: " . $e->getMessage());
            return false;
        }
    }    

     /**
     * Endpoint to get order count by gender.
     * GET method expected.
     * 
     * @return void
     */
    public function getOrderCountByGender() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];

            try {
                $orderCounts = $this->orderModel->getOrderCountByGender();

                $response = [
                    'status' => 'success',
                    'orderCountByGender' => $orderCounts
                ];
                http_response_code(200);

            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }


     /**
     * Endpoint to get order count by gender.
     * GET method expected.
     * 
     * @return void
     */
    public function getOrderCountByCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];

            try {
                $orderCounts = $this->orderModel->getOrderCountByItems();

                $response = [
                    'status' => 'success',
                    'orderCountByItems' => $orderCounts
                ];
                http_response_code(200);

            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                http_response_code(500);
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
?>
