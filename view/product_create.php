<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
</head>
<body>

<h1>Create Product</h1>
<a href="<?= BASE_PATH ?>/product/list">Back to Product List</a>

<form action="<?= BASE_PATH ?>/product/store" method="post">

```
<!-- Category -->
<label>Category</label><br>
<select name="categoryId" required>
    <option value="">-- Select Category --</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= htmlspecialchars($c->id) ?>">
            <?= htmlspecialchars($c->name) ?>
        </option>
    <?php endforeach; ?>
</select>
<br><br>

<!-- Name -->
<label>Name</label><br>
<input type="text" name="name" required><br><br>

<!-- Image URL -->
<label>Image URL</label><br>
<input type="text" name="urlImage"><br><br>

<!-- Description -->
<label>Description</label><br>
<textarea name="description"></textarea><br><br>

<!-- Recommendation -->
<label>Recommendation</label><br>
<select name="recommendation">
    <option value="0">No</option>
    <option value="1">Yes</option>
</select><br><br>

<!-- Price -->
<label>Price</label><br>
<input type="number" name="price" step="0.01" required><br><br>

<!-- Available -->
<label>Available</label><br>
<select name="available">
    <option value="1">Yes</option>
    <option value="0">No</option>
</select><br><br>

<hr>

<h3>Variants (Optional)</h3>
<button type="button" id="add-variant-btn">Add Variant</button>
<br><br>

<div id="variant-container"></div>

<br>
<button type="submit">Create</button>
```

</form>

<script>
let variantIndex = 0;

document.getElementById('add-variant-btn').addEventListener('click', function () {
    addVariant();
});

function addVariant() {
    const container = document.getElementById('variant-container');

    const div = document.createElement('div');
    div.setAttribute('data-variant', variantIndex);

    div.innerHTML = `
        <h4>Variant</h4>

        <label>Variant Name</label><br>
        <input type="text" name="variants[${variantIndex}][name]" required><br><br>

        <label>Min</label><br>
        <input type="number" name="variants[${variantIndex}][min]" min="0"><br><br>

        <label>Max</label><br>
        <input type="number" name="variants[${variantIndex}][max]" min="0"><br><br>

        <label>Variant Items</label><br>

        <div id="variant-items-${variantIndex}">
            <input type="text" name="variants[${variantIndex}][variantItems][0][name]" placeholder="Item 1" required><br>
            <input type="text" name="variants[${variantIndex}][variantItems][1][name]" placeholder="Item 2" required><br>
        </div>

        <button type="button" onclick="addItem(${variantIndex})">Add Item</button>
        <button type="button" onclick="removeVariant(this)">Remove Variant</button>
        <br><br>
    `;

    container.appendChild(div);

    variantIndex++;
}

function addItem(variantId) {
    const itemsDiv = document.getElementById("variant-items-" + variantId);
    const count = itemsDiv.querySelectorAll("input").length;

    const input = document.createElement("input");
    input.type = "text";
    input.name = `variants[${variantId}][variantItems][${count}][name]`;
    input.placeholder = `Item ${count + 1}`;
    input.required = true;

    itemsDiv.appendChild(input);
    itemsDiv.appendChild(document.createElement("br"));
}

function removeVariant(btn) {
    btn.parentElement.remove();
}
</script>

</body>
</html>
