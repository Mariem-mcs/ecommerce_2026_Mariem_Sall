<?php
require_once '../classes/customerClass.php';

class CustomerController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new customerClass();
    }

    public function register($data) {
        if ($this->customerModel->emailExists($data['email'])) {
            return ['success' => false, 'error' => 'Email already registered'];
        }
        $success = $this->customerModel->addCustomer(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['country'],
            $data['city'],
            $data['contact']
        );
        if ($success) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'Registration failed. Please try again.'];
        }
    }

    public function login($email, $pass) {
        $customer = $this->customerModel->login($email, $pass);
        if ($customer) {
            return ['success' => true, 'customer' => $customer];
        } else {
            return ['success' => false, 'error' => 'Invalid email or password'];
        }
    }
}
?>