<?php
require_once __DIR__ . '/../core/db_class.php';

class cartClass extends Database {
    public function isInCart($product_id, $ip) {
        $stmt = $this->conn->prepare("SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?");
        $stmt->bind_param('is', $product_id, $ip);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    
    public function addToCart($product_id, $qty, $ip) {
        $stmt = $this->conn->prepare("INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)");
        $stmt->bind_param('isi', $product_id, $ip, $qty);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function getCartItems($ip) {
        $stmt = $this->conn->prepare(
            "SELECT cart.*, products.product_title, products.product_price, products.product_image 
             FROM cart 
             JOIN products ON cart.p_id = products.product_id 
             WHERE cart.ip_add = ?"
        );
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }
    
    public function getCartCount($ip) {
        $stmt = $this->conn->prepare("SELECT SUM(qty) as total FROM cart WHERE ip_add = ?");
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] ? (int)$row['total'] : 0;
    }
    
    public function getCartTotal($ip) {
        $stmt = $this->conn->prepare(
            "SELECT SUM(cart.qty * products.product_price) as total 
             FROM cart 
             JOIN products ON cart.p_id = products.product_id 
             WHERE cart.ip_add = ?"
        );
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] ? (float)$row['total'] : 0.0;
    }
    
    public function removeFromCart($product_id, $ip) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE p_id = ? AND ip_add = ?");
        $stmt->bind_param('is', $product_id, $ip);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function updateQty($product_id, $new_qty, $ip) {
        if ($new_qty < 1) $new_qty = 1;
        $stmt = $this->conn->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?");
        $stmt->bind_param('iis', $new_qty, $product_id, $ip);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function processPayment($customer_id, $ip, $reference, $amount) {
        // Force MySQL to throw exceptions so we can see errors instead of failing silently
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Start transaction
        $this->conn->begin_transaction();

        try {
            $invoice_no = mt_rand(100000, 999999);
            $order_date = date('Y-m-d');
            $order_status = 'paid';

            // 1. Inserting into orders:
            $stmt = $this->conn->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiss', $customer_id, $invoice_no, $order_date, $order_status);
            $stmt->execute();
            $order_id = $this->conn->insert_id;
            $stmt->close();

            // 2. Copying cart to orderdetails:
            $stmt = $this->conn->prepare("INSERT INTO orderdetails (order_id, product_id, qty) SELECT ?, p_id, qty FROM cart WHERE ip_add = ?");
            $stmt->bind_param('is', $order_id, $ip);
            $stmt->execute();
            $stmt->close();

            // 3. Deleting cart items:
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param('s', $ip);
            $stmt->execute();
            $stmt->close();

            // Committing the transaction:
            $this->conn->commit();
            return $order_id;

        } catch (Exception $e) {
            // Rollback if anything fails:
            $this->conn->rollback();
            // This will stop the page and show us the EXACT database error
            die("<h2 style='color:red'>Database Error: " . $e->getMessage() . "</h2>");
        }
    }
    
    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($order) {
            $stmt = $this->conn->prepare("SELECT od.*, p.product_title, p.product_price FROM orderdetails od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?");
            $stmt->bind_param('i', $order_id);
            $stmt->execute();
            $order['items'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
        return $order;
    }
}
?>