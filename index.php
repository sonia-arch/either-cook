<?php

use ProductController;
use CategoryController;

$uri = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = preg_replace('#^/either-cook#', '', $uri);
$uri = trim($uri, '/');
$segments = explode('/', $uri);
    
$s0 = $segments[0] ?? '';
$s1 = $segments[1] ?? '';
$s2 = $segments[2] ?? '';
// /product/detail/<id>
// /product/edit/<id>
// /product/category/<id>
// /product/list
// /product/create
// /product/store
// /product/update
// /category/edit/<id>
// /category/list
// /category/create
// /category/store
// /category/update

if ($s0 === 'product' && $s1 === 'detail' && is_numeric($s2)) {
    $controller = new ProductController();
    $controller->detail((int)$s2);
    exit;
}

if ($s0 === 'product' && $s1 === 'edit' && is_numeric($s2)) {
    $controller = new ProductController();
    $controller->edit((int)$s2);
    exit;
}

if ($s0 === 'product' && $s1 === 'category' && is_numeric($s2)) {
    $controller = new ProductController();
    $controller->listFromCategory((int)$s2);
    exit;
}

if ($s0 === 'product' && $s1 === 'list') {
    $controller = new ProductController();
    $controller->list();
    exit;
}

if ($s0 === 'product' && $s1 === 'create') {
    $controller = new ProductController();
    $controller->create();
    exit;
}

if ($s0 === 'product' && $s1 === 'store') {
    $controller = new ProductController();
    $controller->store();
    exit;
}

if ($s0 === 'product' && $s1 === 'update') {
    $controller = new ProductController();
    $controller->update();
    exit;
}

if ($s0 === 'category' && $s1 === 'edit' && is_numeric($s2)) {
    $controller = new CategoryController();
    $controller->edit((int)$s2);
    exit;
}

if ($s0 === 'product' && $s1 === 'list') {
    $controller = new CategoryController();
    $controller->list();
    exit;
}

if ($s0 === 'product' && $s1 === 'create') {
    $controller = new CategoryController();
    $controller->create();
    exit;
}

if ($s0 === 'product' && $s1 === 'store') {
    $controller = new CategoryController();
    $controller->store();
    exit;
}

if ($s0 === 'product' && $s1 === 'update') {
    $controller = new CategoryController();
    $controller->update();
    exit;
}