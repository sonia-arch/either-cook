<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>
    <a href="<?= BASE_PATH ?>/product/create">Create New Product</a>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image URL</th>
                <th>Description</th>
                <th>Recommendation</th>
                <th>Price</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product->id) ?></td>
                <td>
                    <a href="<?= BASE_PATH ?>/product/detail/<?= htmlspecialchars($product->id) ?>">
                        <?= htmlspecialchars($product->name) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($product->urlImage) ?></td>
                <td><?= htmlspecialchars($product->description) ?></td>
                <td><?= $product->recommendation ? 'Yes' : 'No' ?></td>
                <td><?= number_format($product->price, 2) ?></td>
                <td><?= $product->available ? 'Yes' : 'No' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
