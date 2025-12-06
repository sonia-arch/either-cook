<?php

class Category
{
    public array $products = []; 
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $urlImage = '',
        public string $description = '',
    ) {}
}
