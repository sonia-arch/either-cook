<?php require_once __DIR__ . '/../config/base_path.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Category</title>
</head>
<body>
    <h1>Create Category</h1>
    <form action="<?= BASE_PATH ?>/category/store" method="post">
        <label>Name:</label><br>
        <input type="text" name="name"><br><br>

        <label>Image URL:</label><br>
        <input type="text" name="urlImage"><br><br>

        <label>Description:</label><br>
        <textarea name="description"></textarea><br><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>
