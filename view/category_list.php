<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category List</title>
</head>
<body>
    <h1>Category List</h1>
    <a href="<?= BASE_PATH ?>/category/create">Create New Category</a> | 
    <a href="<?= BASE_PATH ?>/product/list">View All Products</a>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image URL</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category->id) ?></td>
                <td>
                    <a href="<?= BASE_PATH ?>/product/category/<?= htmlspecialchars($category->id) ?>">
                        <?= htmlspecialchars($category->name) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($category->urlImage) ?></td>
                <td><?= htmlspecialchars($category->description) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
