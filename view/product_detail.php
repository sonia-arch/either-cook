<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Detail</title>
</head>
<body>
    <h1>Product Detail</h1>
    <a href="/product/list">Back to Product List</a>

```
<h2><?= htmlspecialchars($product->name) ?></h2>
<p><strong>Category ID:</strong> <?= htmlspecialchars($product->categoryId) ?></p>
<p><strong>Image URL:</strong> <?= htmlspecialchars($product->urlImage) ?></p>
<p><strong>Description:</strong> <?= htmlspecialchars($product->description) ?></p>
<p><strong>Recommendation:</strong> <?= $product->recommendation ? 'Yes' : 'No' ?></p>
<p><strong>Price:</strong> <?= number_format($product->price, 2) ?></p>
<p><strong>Available:</strong> <?= $product->available ? 'Yes' : 'No' ?></p>

<?php if (!empty($product->variants)): ?>
    <h3>Variants</h3>
    <ul>
    <?php foreach ($product->variants as $variant): ?>
        <li>
            <strong><?= htmlspecialchars($variant->name) ?></strong>
            <?php if ($variant->min !== null || $variant->max !== null): ?>
                (Min: <?= $variant->min ?? '-' ?>, Max: <?= $variant->max ?? '-' ?>)
            <?php endif; ?>
            <?php if (!empty($variant->variantItems)): ?>
                <ul>
                    <?php foreach ($variant->variantItems as $item): ?>
                        <li><?= htmlspecialchars($item->name) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
```

</body>
</html>
