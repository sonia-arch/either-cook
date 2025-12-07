<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../entity/Category.php';

class CategoryRepository
{
    private PDO $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->conn->query($sql);

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $category = new Category(
                id: (int)$row['id'],
                name: $row['name'],
                urlImage: $row['url_image'],
                description: $row['description']
            );
            $categories[]=$category;
        }
        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $category = new Category (
            id: (int)$row['id'],
            name: $row['name'],
            urlImage: $row['url_image'],
            description: $row['description']
        );
        return $category;
    }

    public function create(Category $category): bool
    {
        $sql = "INSERT INTO categories (name, url_image, description) VALUES (:name, :urlImage, :description)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'name' => $category->name,
            'urlImage' => $category->urlImage,
            'description' => $category->description,
        ]);
    }

    public function update(Category $category): bool
    {
        if ($category->id === null) return false;

        $sql = "UPDATE categories SET name = :name, url_image = :urlImage, description = :description WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $category->id,
            'name' => $category->name,
            'urlImage' => $category->urlImage,
            'description' => $category->description,
        ]);
    }

    public function delete(int $categoryId): bool
    {
        if ($categoryId === null) return false;

        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare ($sql);

        return $stmt->execute(['id' => $categoryId]);
    }
}