<?php

require_once __DIR__ . '/../repository/ProductRepository.php';
require_once __DIR__ . '/../repository/CategoryRepository.php';
require_once __DIR__ . '/../repository/VariantRepository.php';
require_once __DIR__ . '/../repository/VariantItemRepository.php';
require_once __DIR__ . '/../entity/Product.php';
require_once __DIR__ . '/../entity/Variant.php';
require_once __DIR__ . '/../entity/VariantItem.php';
require_once __DIR__ . '/../config/base_path.php';

class ProductController
{
    private ProductRepository $productRepository;
    private VariantRepository $variantRepository;
    private VariantItemRepository $variantItemRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->variantRepository = new VariantRepository();
        $this->variantItemRepository = new VariantItemRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function list()
    {
        $products = $this->productRepository->findAll();
        include __DIR__ . '/../view/product_list.php';
    }

    public function listFromCategory(int $id)
    {
        $products = $this->productRepository->findByCategory($id);
        include __DIR__ . '/../view/product_list.php';
    }

    public function detail(int $id)
    {
        $product = $this->productRepository->findById($id);
        $variants = $this->variantRepository->findByProduct($product->id);
        foreach ($variants as $variant) {
            $variantItems = $this->variantItemRepository->findByVariant($variant->id);
            $variant->variantItems = $variantItems;
        }
        $product->variants = $variants;
        include __DIR__ . '/../view/product_detail.php';
    }

    public function create()
    {
        $categories = $this->categoryRepository->findAll();
        include __DIR__ . '/../view/product_create.php';
    }

    public function store()
    {
        $categoryId = $_POST['categoryId'] ?? null;
        $name = $_POST['name'];
        $urlImage = $_POST['urlImage'];
        $description = $_POST['description'];
        $recommendation = $_POST['recommendation'];
        $price = $_POST['price'];
        $available = $_POST['available'];
        $product = new Product(
            null,
            $categoryId,
            $name,
            $urlImage,
            $description,
            $recommendation,
            $price,
            $available
        );
        $product = $this->productRepository->create($product);

        $variants = $_POST['variants'] ?? [];

        foreach ($variants as $v) {
            $variant = new Variant(
                null,
                $product->id,
                $v['name'],
                $v['max'],
                $v['min']
            );
            $variant = $this->variantRepository->create($variant);

            $variantItems = $v['variantItems'];
            foreach ($variantItems as $vi) {
                $variantItem = new VariantItem(
                    null,
                    $variant->id,
                    $vi['name']
                );
                $variantItem = $this->variantItemRepository->create($variantItem);
            }
        }
        header("Location: " . BASE_PATH . "/product/list");
        exit;
    }

    public function edit(int $id)
    {
        $product = $this->productRepository->findById($id);
        $variants = $this->variantRepository->findByProduct($product->id);
        foreach ($variants as $variant) {
            $variantItems = $this->variantItemRepository->findByVariant($variant->id);
            $variant->variantItems = $variantItems;
        }
        $product->variants = $variants;
        $categories = $this->categoryRepository->findAll();
        include __DIR__ . '/../view/product_edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];
        $categoryId = $_POST['categoryId'] ?? null;
        $name = $_POST['name'];
        $urlImage = $_POST['urlImage'];
        $description = $_POST['description'];
        $recommendation = $_POST['recommendation'];
        $price = $_POST['price'];
        $available = $_POST['available'];
        $product = new Product(
            $id,
            $categoryId,
            $name,
            $urlImage,
            $description,
            $recommendation,
            $price,
            $available
        );
        $this->productRepository->update($product);

        $variants = $_POST['variants'] ?? [];

        foreach ($variants as $v) {
            $variant = new Variant(
                empty($v['id']) ? null : (int)$v['id'],
                $v['productId'],
                $v['name'],
                $v['max'],
                $v['min']
            );
            if (empty($variant->id)) {
                $variant = $this->variantRepository->create($variant);
            } else {
                $this->variantRepository->update($variant);
            }

            $variantItems = $v['variantItems'];
            foreach ($variantItems as $vi) {
                $variantItem = new VariantItem(
                    empty($vi['id']) ? null : (int)$vi['id'],
                    $variant->id,
                    $vi['name']
                );
                if (empty($variantItem->id)) {
                    $this->variantItemRepository->create($variantItem);
                } else {
                    $this->variantItemRepository->update($variantItem);
                }
            }
        }

        $variantsDeleted = $_POST['variantsDeleted'] ?? [];
        foreach ($variantsDeleted as $vd) {
            $vd = $vd === '' ? null : (int)$vd;
            if ($vd !== null) {
                $this->variantRepository->delete($vd);
            }
        }

        $variantItemsDeleted = $_POST['variantItemsDeleted'] ?? [];
        foreach ($variantItemsDeleted as $vid) {
            $vid = $vid === '' ? null : (int)$vid;
            if ($vid !== null) {
                $this->variantItemRepository->delete($vid);
            }
        }

        header("Location: " . BASE_PATH . "/product/edit/{$product->id}");
        exit;
    }
}
