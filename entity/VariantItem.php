<?php

class VariantItem
{
    public function __construct(
        public ?int $id = null,
        public ?int $variantId = null,
        public string $name = '',
    ) {}
}