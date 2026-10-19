<?php
require_once __DIR__ . '/../classes/cartClass.php';

class cartController { 
    private $cartModel;
    public function __construct() {
        $this->cartModel = new cartClass();
    }
    public function isInCart($id, $ip) { return $this->cartModel->isInCart($id, $ip); }
    public function addToCart($id, $qty, $ip) { return $this->cartModel->addToCart($id, $qty, $ip); }
    public function getCartItems($ip) { return $this->cartModel->getCartItems($ip); }
    public function getCartCount($ip) { return $this->cartModel->getCartCount($ip); }
    public function getCartTotal($ip) { return $this->cartModel->getCartTotal($ip); }
    public function removeFromCart($id, $ip) { return $this->cartModel->removeFromCart($id, $ip); }
    public function updateQty($id, $qty, $ip) { return $this->cartModel->updateQty($id, $qty, $ip); }
}
?>