<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../entity/Variant.php';

class VariantRepository
{
    private PDO $conn;
    public function __construct() 
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM variants";
        $stmt = $this->conn->query($sql);

        $variants = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variant = new Variant(
                id: (int)$row['id'],
                productId: $row['product_id'],
                name: $row['name'],
                max: $row['max'],
                min: $row['min']
            );
            $variants[]=$variant;
        }
        return $variants;
    }

    public function findById(int $id): ?Variant 
    {
        $sql = "SELECT * FROM variants WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $variant = new Variant (
            id: (int)$row['id'],
            productId: $row['product_id'],
            name: $row['name'],
            max: $row['max'],
            min: $row['min']
        );
        return $variant;
    }

   public function findByProduct(int $productId): array
    {
        $sql = "SELECT * FROM variants WHERE product_id = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['productId' => $productId]);
        $variants = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variant = new Variant (
                id: (int)$row['id'],
                productId: $row['product_id'], 
                name: $row['name'],
                max: $row['max'],
                min: $row['min']
            );
            $variants[]=$variant;
        }
        return $variants;
    }

    public function create(Variant $variant): Variant 
    {
        $sql = "
        INSERT INTO variants 
        (product_id, name, max, min) 
        VALUES 
        (:productId, :name, :max, :min)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'productId' =>$variant->productId,
            'name' => $variant->name,
            'max' => $variant->max,
            'min' => $variant->min,
        ]);
        $variant->id = (int)$this->conn->lastInsertId();
        return $variant;
    }

    public function update(Variant $variant): bool 
    {
        if ($variant->id === null) return false;

        $sql = "UPDATE variants SET product_id = :productId, name = :name, max = :max, min = :min WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'productId' =>$variant->productId,
            'name' => $variant->name,
            'max' => $variant->max,
            'min' => $variant->min,
        ]);
    }

    public function delete(Variant $variant): bool 
    {
        if ($variant->id === null) return false;

        $sql = "DELETE FROM variants WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute(['id' => $variant->id]);
    }
}