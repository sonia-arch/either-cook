<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../entity/Product.php';

class ProductRepository
{
    private PDO $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->conn->query($sql);

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                (int)$row['id'],
                $row['category_id'],
                $row['name'],
                $row['url_image'],
                $row['description'],
                (bool)$row['recommendation'],
                (float)$row['price'],
                (bool)$row['available']
            );
            $products[]=$product;
        }
        return $products;
    }

    public function findById(int $id): ?Product
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $product = new Product (
            (int)$row['id'],
            $row['category_id'], 
            $row['name'],
            $row['url_image'],
            $row['description'],
            (bool)$row['recommendation'],
            (float)$row['price'],
            (bool)$row['available']
        );
        return $product;
    }

    public function findByCategory(int $categoryId): array
    {
        $sql = "SELECT * FROM products WHERE category_id = :categoryId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['categoryId' => $categoryId]);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product (
                (int)$row['id'],
                $row['category_id'], 
                $row['name'],
                $row['url_image'],
                $row['description'],
                (bool)$row['recommendation'],
                (float)$row['price'],
                (bool)$row['available']
            );
            $products[]=$product;
        }
        return $products;
    }

    public function create(Product $product): Product
    {
        $sql = "INSERT INTO products (category_id, name, url_image, description, recommendation, price, available) VALUES (:categoryId, :name, :urlImage, :description, :recommendation, :price, :available)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'categoryId' => $product->categoryId,
            'name' => $product->name,
            'urlImage' => $product->urlImage,
            'description' => $product->description,
            'recommendation' => $product->recommendation,
            'price' => $product->price,
            'available' => $product->available,
        ]);
        $product->id = (int)$this->conn->lastInsertId();
        return $product;
    }

    public function update(Product $product): bool
    {
        if ($product->id === null) return false;

        $sql = "UPDATE products SET category_id = :categoryId, name = :name, url_image = :urlImage, description = :description, recommendation = :recommendation, price = :price, available = :available WHERE id = :Id";
        
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $product->id,
            'categoryId' => $product->categoryId,
            'name' => $product->name,
            'urlImage' => $product->urlImage,
            'description' => $product->description,
            'recommendation' => $product->recommendation,
            'price' => $product->price,
            'available' => $product->available,
        ]);
    }

    public function delete(int $productId): bool
    {
        if ($productId === null) return false;

        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute(['id' => $productId]);
    }
}