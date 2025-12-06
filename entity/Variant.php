<?php
class Variant
{
    public array $variantItems = []; 
    public function __construct(
        public ?int $id = null,
        public ?int $productId = null,
        public string $name = '',
        public ?int $max = null,
        public ?int $min = null,
    ) {}
}