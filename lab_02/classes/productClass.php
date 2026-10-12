<?php
require_once __DIR__ . '/../core/db_class.php';

class productClass extends Database {
    // Adding a new brand:
    public function addBrand($name) {
        $stmt = $this->conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Getting all the brands:
    public function getAllBrands() {
        $result = $this->conn->query("SELECT * FROM brands ORDER BY brand_name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Getting a single brand by ID for editing:
    public function getBrandById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE brand_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row : false;
    }

    // For updating a brand:
    public function updateBrand($id, $name) {
        $stmt = $this->conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param('si', $name, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // For deleting a brand:
    public function deleteBrand($id) {
        $stmt = $this->conn->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    // Adding a new category:
    public function addCategory($name) {
        $stmt = $this->conn->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Getting all the categories:
    public function getAllCategories() {
        $result = $this->conn->query("SELECT * FROM categories ORDER BY cat_name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Getting a single category by ID for editing:
    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE cat_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row : false;
    }

    // For updating a category:
    public function updateCategory($id, $name) {
        $stmt = $this->conn->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param('si', $name, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // For deleting a category:
    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ============ PRODUCT MANAGEMENT METHODS (Task 9) ============

    // Adding a new product:
    public function addProduct($cat, $brand, $title, $price, $desc, $image, $keywords) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iisdsss', $cat, $brand, $title, $price, $desc, $image, $keywords);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // For updating a product:
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords) {
        $stmt = $this->conn->prepare(
            "UPDATE products 
             SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, 
                 product_desc = ?, product_image = ?, product_keywords = ? 
             WHERE product_id = ?"
        );
        $stmt->bind_param('iisdsssi', $cat, $brand, $title, $price, $desc, $image, $keywords, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Getting a single product by ID (with category and brand names):
    public function getProductById($id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             WHERE p.product_id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row : false;
    }

    // Getting all the products:
    public function getAllProducts() {
        $result = $this->conn->query(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             ORDER BY p.product_id DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // For deleting a product:
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ============ PRODUCT DISPLAY METHODS (Task 10) ============

    // Getting the featured products by random selection:
    public function getFeaturedProducts($limit = 6) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             ORDER BY RAND() LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }

    // Getting products by category:
    public function getProductsByCategory($cat_id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             WHERE p.product_cat = ?"
        );
        $stmt->bind_param('i', $cat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }

    // Getting products by brand:
    public function getProductsByBrand($brand_id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             WHERE p.product_brand = ?"
        );
        $stmt->bind_param('i', $brand_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }

    // Searching products by title or keywords:
    public function searchProducts($query) {
        $like = '%' . $query . '%';
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name 
             FROM products p
             LEFT JOIN categories c ON p.product_cat = c.cat_id
             LEFT JOIN brands b ON p.product_brand = b.brand_id
             WHERE p.product_title LIKE ? OR p.product_keywords LIKE ?"
        );
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }
}
?>