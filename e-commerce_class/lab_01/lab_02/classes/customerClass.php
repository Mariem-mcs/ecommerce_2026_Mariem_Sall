<?php
require_once __DIR__ . '/../core/db_class.php';
class CustomerClass extends Database {

    // Checking if the email already exists:
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT customer_email FROM customer WHERE customer_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Adding a new customer:
    public function addCustomer($name, $email, $pass, $country, $city, $contact) {
        $hashed = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $email, $hashed, $country, $city, $contact);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Getting the customer by email:
    public function getCustomerByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row : false;
    }

    // Login:
    public function login($email, $pass) {
        $row = $this->getCustomerByEmail($email);
        if ($row && password_verify($pass, $row['customer_pass'])) {
            return $row;
        }
        return false;
    }
}
?>