<?php

require_once __DIR__ . '/../repository/CategoryRepository.php';
require_once __DIR__ . '/../repository/ProductRepository.php';
require_once __DIR__ . '/../repository/VariantRepository.php';
require_once __DIR__ . '/../repository/VariantItemRepository.php';
require_once __DIR__ . '/../entity/Category.php';
require_once __DIR__ . '/../entity/Product.php';
require_once __DIR__ . '/../entity/Variant.php';
require_once __DIR__ . '/../entity/VariantItem.php';

echo "===== SUPER COMPLETE REPOSITORY TEST =====\n\n";

$failedTests = [];

function assertTrue($cond, $msg) {
    global $failedTests;
    if ($cond) {
        echo "[PASS] $msg\n";
    } else {
        echo "[FAIL] $msg\n";
        $failedTests[] = $msg;
    }
}

// --- INIT ---
$catRepo = new CategoryRepository();
$prodRepo = new ProductRepository();
$variantRepo = new VariantRepository();
$variantItemRepo = new VariantItemRepository();

// --- CREATE MULTIPLE CATEGORIES ---
$categories = [];
for ($i=1; $i<=5; $i++) {
    $cat = new Category(name: "Category $i", urlImage: "http://example.com/cat$i.jpg", description: "Desc $i");
    $cat = $catRepo->create($cat); // pastikan ID terisi
    $categories[] = $cat;
    assertTrue($cat->id !== null, "Category $i created");
}

// --- CREATE MULTIPLE PRODUCTS PER CATEGORY ---
$products = [];
foreach ($categories as $cat) {
    for ($i=1; $i<=10; $i++) {
        $prod = new Product(
            categoryId: $cat->id, 
            name: "Product {$cat->id}-$i", 
            urlImage: "http://example.com/prod{$cat->id}_$i.jpg", 
            description: "Desc $i", 
            recommendation: true, 
            price: rand(10,100), 
            available: true
        );
        $prod = $prodRepo->create($prod);
        $products[] = $prod;
        assertTrue($prod->id !== null, "Product {$prod->id} created in Category {$cat->id}");
    }
}

// --- CREATE VARIANTS AND VARIANT ITEMS PER PRODUCT ---
$variants = [];
$variantItems = [];
foreach ($products as $prod) {
    for ($i=1; $i<=3; $i++) {
        $variant = new Variant(productId: $prod->id, name: "Variant $i", max: 5, min: 1);
        $variant = $variantRepo->create($variant);
        $variants[] = $variant;
        assertTrue($variant->id !== null, "Variant {$variant->id} created for Product {$prod->id}");
        for ($j=1; $j<=3; $j++) {
            $vi = new VariantItem(variantId: $variant->id, name: "Item $j");
            $vi = $variantItemRepo->create($vi);
            $variantItems[] = $vi;
            assertTrue($vi->id !== null, "VariantItem {$vi->id} created for Variant {$variant->id}");
        }
    }
}

// --- VERIFY RELATIONS ---
foreach ($products as $prod) {
    if ($prod->categoryId !== null) {
        $prodsInCat = $prodRepo->findByCategory($prod->categoryId);
        assertTrue(in_array($prod->id, array_map(fn($p)=>$p->id,$prodsInCat)), "Product {$prod->id} correctly linked to Category {$prod->categoryId}");
    }
}

foreach ($variants as $variant) {
    $vars = $variantRepo->findByProduct($variant->productId);
    assertTrue(in_array($variant->id, array_map(fn($v)=>$v->id,$vars)), "Variant {$variant->id} correctly linked to Product {$variant->productId}");
}

foreach ($variantItems as $vi) {
    $vis = $variantItemRepo->findByVariant($vi->variantId);
    assertTrue(in_array($vi->id, array_map(fn($v)=>$v->id,$vis)), "VariantItem {$vi->id} correctly linked to Variant {$vi->variantId}");
}

// --- UPDATE RANDOM RECORDS ---
foreach (array_slice($categories,0,2) as $cat) {
    $cat->name .= " Updated";
    assertTrue($catRepo->update($cat), "Category {$cat->id} updated");
}
foreach (array_slice($products,0,5) as $prod) {
    $prod->price += 5;
    assertTrue($prodRepo->update($prod), "Product {$prod->id} updated");
}
foreach (array_slice($variants,0,5) as $v) {
    $v->name .= " Updated";
    assertTrue($variantRepo->update($v), "Variant {$v->id} updated");
}
foreach (array_slice($variantItems,0,5) as $vi) {
    $vi->name .= " Updated";
    assertTrue($variantItemRepo->update($vi), "VariantItem {$vi->id} updated");
}

// --- DELETE AND CASCADE CHECK ---
$delCat = $categories[0];
assertTrue($catRepo->delete($delCat->id), "Deleted Category {$delCat->id}");

// Products of deleted category should have category_id = null
foreach ($products as $prod) {
    if ($prod->categoryId === $delCat->id) {
        $prodsAfterDelCat = $prodRepo->findByCategory($prod->categoryId);
        assertTrue(count($prodsAfterDelCat) == 0, "Products of deleted category {$delCat->id} are gone");
    }
}

// Delete all products and check cascade to variants & items
foreach ($products as $prod) {
    assertTrue($prodRepo->delete($prod->id), "Deleted Product {$prod->id}");
    $vars = $variantRepo->findByProduct($prod->id);
    assertTrue(count($vars) == 0, "Variants of deleted Product {$prod->id} are gone");
}

// Delete all remaining categories
foreach ($categories as $cat) {
    if ($cat->id !== $delCat->id) $catRepo->delete($cat->id);
}

// --- EDGE CASES ---
$fakeDeleteCat = $catRepo->delete(999999);
assertTrue($fakeDeleteCat, "Deleting non-existent category returns true");

$fakeUpdateProduct = $prodRepo->update(new Product(id: 999999, name: "Fake"));
assertTrue(!$fakeUpdateProduct, "Updating non-existent product returns false");

// --- TEST SUMMARY ---
echo "\n===== SUPER COMPLETE REPOSITORY TEST FINISHED =====\n";

if (empty($failedTests)) {
    echo "All tests passed!\n";
} else {
    echo "Failed tests:\n";
    foreach ($failedTests as $fail) {
        echo " - $fail\n";
    }
}
