<?php
require_once __DIR__ . '/../repository/CategoryRepository.php';
require_once __DIR__ . '/../repository/ProductRepository.php';
require_once __DIR__ . '/../repository/VariantRepository.php';
require_once __DIR__ . '/../repository/VariantItemRepository.php';
require_once __DIR__ . '/../entity/Category.php';
require_once __DIR__ . '/../entity/Product.php';
require_once __DIR__ . '/../entity/Variant.php';
require_once __DIR__ . '/../entity/VariantItem.php';

echo "=== SEED DATABASE START ===\n";

$catRepo = new CategoryRepository();
$prodRepo = new ProductRepository();
$variantRepo = new VariantRepository();
$variantItemRepo = new VariantItemRepository();

// --- Seed Categories ---
$categoriesData = [
    ['name' => 'Food', 'urlImage' => 'https://via.placeholder.com/150', 'description' => 'All kinds of food'],
    ['name' => 'Drinks', 'urlImage' => 'https://via.placeholder.com/150', 'description' => 'Beverages and drinks'],
    ['name' => 'Snacks', 'urlImage' => 'https://via.placeholder.com/150', 'description' => 'Quick bites'],
];

$categories = [];
foreach ($categoriesData as $data) {
    $cat = new Category(name: $data['name'], urlImage: $data['urlImage'], description: $data['description']);
    $cat = $catRepo->create($cat);
    $categories[] = $cat;
    echo "Created Category: {$cat->id} - {$cat->name}\n";
}

// --- Seed Products ---
$products = [];
foreach ($categories as $cat) {
    for ($i = 1; $i <= 3; $i++) { // 3 products per category
        $prod = new Product(
            categoryId: $cat->id,
            name: "{$cat->name} Product $i",
            urlImage: 'https://via.placeholder.com/150',
            description: "Description of {$cat->name} Product $i",
            recommendation: rand(0,1) === 1,
            price: rand(10,100),
            available: true
        );
        $prod = $prodRepo->create($prod);
        $products[] = $prod;
        echo "Created Product: {$prod->id} - {$prod->name}\n";
    }
}

// --- Seed Variants & Variant Items ---
foreach ($products as $prod) {
    for ($i = 1; $i <= 2; $i++) { // 2 variants per product
        $variant = new Variant(
            productId: $prod->id,
            name: "Variant $i",
            max: rand(1,5),
            min: rand(0,2)
        );
        $variant = $variantRepo->create($variant);
        echo "  Created Variant: {$variant->id} - {$variant->name}\n";

        for ($j = 1; $j <= 2; $j++) { // 2 variant items per variant
            $vi = new VariantItem(
                variantId: $variant->id,
                name: "Item $j"
            );
            $vi = $variantItemRepo->create($vi);
            echo "    Created VariantItem: {$vi->id} - {$vi->name}\n";
        }
    }
}

echo "=== SEED DATABASE COMPLETE ===\n";
