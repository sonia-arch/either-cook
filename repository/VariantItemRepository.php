<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../entity/VariantItem.php';

class VariantItemRepository
{
    private PDO $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM variant_items";
        $stmt = $this->conn->query($sql);

        $variantItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variantItem = new VariantItem(
                id: (int)$row['id'],
                variantId: $row['variant_id'],
                name: $row['name']
            );
            $variantItems[]=$variantItem;
        }
        return $variantItems;
    }

    public function findById(int $id): ?VariantItem
    {
        $sql = "SELECT * FROM variant_items WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $variantItem = new VariantItem (
            id: (int)$row['id'],
            variantId: $row['variant_id'],
            name: $row['name']
        );
        return $variantItem;
    }

   public function findByVariant(int $variantId): array
    {
        $sql = "SELECT * FROM variant_items WHERE variant_id = :variantId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['variantId' => $variantId]);
        $variantItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variantItem = new VariantItem (
                id: (int)$row['id'],
                variantId: $row['variant_id'], 
                name: $row['name'],
            );
            $variantItems[]=$variantItem;
        }
        return $variantItems;
    }
    public function create(VariantItem $variantItem): VariantItem
    {
        $sql = "INSERT INTO variant_items (name) VALUES (:name)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'name' => $variantItem->name,
        ]);
        $variantItem->id = (int)$this->conn->lastInsertId();
        return $variantItem;
    }

    public function update(VariantItem $variantItem): bool
    {
        if ($variantItem->id === null) return false;

        $sql = "UPDATE variant_items SET name = :name WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $variantItem->id,
            'name' => $variantItem->name,
        ]);
    }

    public function delete(VariantItem $variantItem): bool 
    {
        if ($variantItem->id === null) return false;

        $sql = "DELETE FROM variant_items WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute(['id' => $variantItem->id]);
    }
}