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
}
?>