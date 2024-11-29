<?php
include("./model/Product.php");

class BlogController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product($this->connectDatabase());
    }

    private function connectDatabase() {
        $username = "root";
        $password = "12345678";
        $port = 3306;
        $dbname = "aht";
        $pdo = new PDO("mysql:host=127.0.0.1;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function display() {
        $products = $this->productModel->getAll();
        echo json_encode($products); 
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $this->productModel->create($name, $price);
        }
    }

    public function delete() {
        $id = $_GET['id'];
        $this->productModel->delete($id);
        $this->display(); 
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $this->productModel->update($id, $name, $price);
            $this->display();
        }
    }

    public function getById(){
        $id = $_GET['id'];
        $data = $this->productModel->getById($id);
        echo json_encode($data);
    }

}
