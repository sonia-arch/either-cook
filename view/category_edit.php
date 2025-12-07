<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
</head>

<body>
    <h1>Edit Category</h1>
    <form action="<?= BASE_PATH ?>/category/update" method="post">

        <input type="hidden" name="id" value="<?= htmlspecialchars($category->id) ?>">
        
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($category->name) ?>" required><br><br>

        ```
        <label>Image URL:</label><br>
        <input type="text" name="urlImage" value="<?= htmlspecialchars($category->urlImage) ?>"><br><br>

        <label>Description:</label><br>
        <textarea name="description"><?= htmlspecialchars($category->description) ?></textarea><br><br>

        <button type="submit">Update</button>
    </form>

    <form action="<?= BASE_PATH ?>/category/delete" method="post" style="margin-top:10px;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($category->id) ?>">
        <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
    </form>
    ```

</body>

</html>