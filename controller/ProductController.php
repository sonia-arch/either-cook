<?php
require_once __DIR__ . '/../repository/ProductRepository.php';

class ProductController
{
    private ProductRepository $productRepository;
    private VariantRepository $variantRepository;
    private VariantItemRepository $variantItemRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->variantRepository = new VariantRepository();
        $this->variantItemRepository = new VariantItemRepository();
    }

    public function list() 
    {
        $products = $this->productRepository->findAll();
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
        include __DIR__ . '/../view/product_create.php';
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $urlImage = $_POST['urlImage'] ?? '';
        $description = $_POST['description'] ?? '';
        $recommendation = $_POST['recommendation'] ?? '';
        $price = $_POST['price'] ?? '';      
        $product = new Product(
            null,
            null, 
            $name,
            $urlImage,
            $description,
            $recommendation,
            $price
        );
        $product = $this->productRepository->create($product);
        
        $variants = $_POST['variants'];

        foreach ($variants as $v) {
            $name = $v['name'];
            $max = $v['max'];
            $min = $v['min'];
            $variant = new Variant(
                null,
                $product->id,
                $name,
                $max,
                $min
            );
            $variant = $this->variantRepository->create($variant);

            $variantItems = $v['variantItems'];
            foreach ($variantItems as $vi) {
                $name = $vi['name'];
                $variantItem = new VariantItem(
                    null,
                    $variant->id,
                    $name
                );
                $variantItem = $this->variantItemRepository->create($variantItem);
            }
        } 
        header("Location: /product/list");
        exit;
    }

    public function storeVariant(int $productId) 
    {
        $name = $_POST['name'] ?? '';
        $max = $_POST['max'] ?? '';
        $min = $_POST['min'] ?? '';
        $variant = new Variant(
            null,
            $productId,
            $name,
            $max,
            $min
        );
        $variant = $this->variantRepository->create($variant);
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
        include __DIR__ . '/../view/product_edit.php';
    }


    public function update()
    {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $urlImage = $_POST['urlImage'] ?? '';
        $description = $_POST['description'] ?? '';
        $recommendation = $_POST['recommendation'] ?? '';
        $price = $_POST['price'] ?? '';

        $product = new Product(
            $id,
            null, 
            $name,
            $urlImage,
            $description,
            $recommendation,
            $price
        );
        $this->productRepository->update($product);
        header("Location: /product/list");
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $urlImage = $_POST['urlImage'] ?? '';
        $description = $_POST['description'] ?? '';
        $recommendation = $_POST['recommendation'] ?? '';
        $price = $_POST['price'] ?? '';

        $product = new Product(
          $id,
          null,
          $name,
          $urlImage,
          $description,
          $recommendation,
          $price 
        );
        $this->productRepository->delete($product);
        header("Location: /product/list");
        exit;
    }
}



