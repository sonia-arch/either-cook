<?php
require_once __DIR__ . '/../repository/CategoryRepository.php';

class CategoryController
{
    private CategoryRepository $repo;
    
    public function __construct()
    {
        $this->repo = new CategoryRepository();
    }

    public function list() 
    {
        $categories = $this->repo->findAll();
        include __DIR__ . '/../view/category_list.php';
    }

    // public function detail(int $id)
    // {
    //     $category = $this->repo->findById($id);
    //     include __DIR__ . '/../view/category_detail.php';
    // }

    public function create()
    {
        include __DIR__ . '/../view/category_create.php';
    }

    public function store()
    {
        $name = $_POST['name'];
        $urlImage = $_POST['urlImage'];
        $description = $_POST['description'];

        $category = new Category(
            name: $name,
            urlImage: $urlImage,
            description: $description
        );
        $this->repo->create($category);
        header("Location: /category/list");
        exit;
    }

    public function edit(int $id)
    {

        $category = $this->repo->findById($id);
        include __DIR__ . '/../view/category_edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $urlImage = $_POST['urlImage'];
        $description = $_POST['description'];

        $category = new Category(
            id: $id,
            name: $name,
            urlImage: $urlImage,
            description: $description
        );
        $this->repo->update($category);
        header("Location: /category/list");
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'];

        $this->repo->delete($id);
        header("Location: /category/list");
        exit;
    }
}