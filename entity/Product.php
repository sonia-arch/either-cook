<?php

class Product
{
    public array $variants = []; 
    public function __construct(
        public ?int $id = null,
        public ?int $categoryId = null,
        public string $name = '',
        public string $urlImage = '',
        public string $description = '',
        public bool $recommendation = false,
        public float $price = 0.0,
        public bool $available = true,
    ) {}
}