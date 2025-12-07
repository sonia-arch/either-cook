<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>

<h1>Edit Product</h1>
<a href="<?= BASE_PATH ?>/product/list">Back to Product List</a>

<form action="<?= BASE_PATH ?>/product/update" method="post">

<input type="hidden" name="id" value="<?= htmlspecialchars($product->id) ?>">

<!-- Category -->
<label>Category</label><br>
<select name="categoryId" required>
    <?php foreach ($categories as $c): ?>
        <option value="<?= $c->id ?>" <?= $c->id == $product->categoryId ? 'selected' : '' ?>>
            <?= htmlspecialchars($c->name) ?>
        </option>
    <?php endforeach; ?>
</select>
<br><br>

<!-- Name -->
<label>Name</label><br>
<input type="text" name="name" value="<?= htmlspecialchars($product->name) ?>" required><br><br>

<!-- Image URL -->
<label>Image URL</label><br>
<input type="text" name="urlImage" value="<?= htmlspecialchars($product->urlImage) ?>"><br><br>

<!-- Description -->
<label>Description</label><br>
<textarea name="description"><?= htmlspecialchars($product->description) ?></textarea><br><br>

<!-- Recommendation -->
<label>Recommendation</label><br>
<select name="recommendation">
    <option value="0" <?= !$product->recommendation ? 'selected' : '' ?>>No</option>
    <option value="1" <?= $product->recommendation ? 'selected' : '' ?>>Yes</option>
</select><br><br>

<!-- Price -->
<label>Price</label><br>
<input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product->price) ?>" required><br><br>

<!-- Available -->
<label>Available</label><br>
<select name="available">
    <option value="1" <?= $product->available ? 'selected' : '' ?>>Yes</option>
    <option value="0" <?= !$product->available ? 'selected' : '' ?>>No</option>
</select><br><br>

<hr>

<h3>Variants</h3>

<!-- Hidden array for deleted entities -->
<div id="deleted-container">
    <input type="hidden" name="variantsDeleted[]" value="">
    <input type="hidden" name="variantItemsDeleted[]" value="">
</div>

<button type="button" onclick="addVariant()">Add Variant</button><br><br>

<div id="variant-container">
    <?php foreach ($product->variants as $vi => $variant): ?>
        <div class="variant-block" data-variant-index="<?= $vi ?>">

            <h4>Variant</h4>

            <input type="hidden" name="variants[<?= $vi ?>][id]" value="<?= $variant->id ?>">
            <input type="hidden" name="variants[<?= $vi ?>][productId]" value="<?= $product->id ?>">

            <label>Name</label><br>
            <input type="text" name="variants[<?= $vi ?>][name]" value="<?= htmlspecialchars($variant->name) ?>" required><br><br>

            <label>Min</label><br>
            <input type="number" name="variants[<?= $vi ?>][min]" value="<?= $variant->min ?>"><br><br>

            <label>Max</label><br>
            <input type="number" name="variants[<?= $vi ?>][max]" value="<?= $variant->max ?>"><br><br>

            <label>Variant Items</label><br>

            <div id="variant-items-<?= $vi ?>">
                <?php foreach ($variant->variantItems as $ii => $item): ?>
                    <div data-item-index="<?= $ii ?>">
                        <input type="hidden" name="variants[<?= $vi ?>][variantItems][<?= $ii ?>][id]" value="<?= $item->id ?>">
                        <input type="hidden" name="variants[<?= $vi ?>][variantItems][<?= $ii ?>][variantId]" value="<?= $variant->id ?>">

                        <input type="text" name="variants[<?= $vi ?>][variantItems][<?= $ii ?>][name]" 
                               value="<?= htmlspecialchars($item->name) ?>" required>

                        <button type="button" onclick="removeItem(this, <?= $item->id ?>)">Remove Item</button>
                        <br><br>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addItem(<?= $vi ?>)">Add Item</button>
            <button type="button" onclick="removeVariant(this, <?= $variant->id ?>)">Remove Variant</button>

            <hr>
        </div>
    <?php endforeach; ?>
</div>

<button type="submit">Save</button>
```

</form>

<script>
let variantIndex = <?= count($product->variants) ?>;

// add variant
function addVariant() {
    const container = document.getElementById('variant-container');

    let html = `
        <div class="variant-block" data-variant-index="${variantIndex}">
            <h4>Variant</h4>

            <input type="hidden" name="variants[${variantIndex}][id]" value="0">
            <input type="hidden" name="variants[${variantIndex}][productId]" value="<?= $product->id ?>">

            <label>Name</label><br>
            <input type="text" name="variants[${variantIndex}][name]" required><br><br>

            <label>Min</label><br>
            <input type="number" name="variants[${variantIndex}][min]"><br><br>

            <label>Max</label><br>
            <input type="number" name="variants[${variantIndex}][max]"><br><br>

            <label>Variant Items</label><br>

            <div id="variant-items-${variantIndex}">
                <div data-item-index="0">
                    <input type="hidden" name="variants[${variantIndex}][variantItems][0][id]" value="0">
                    <input type="hidden" name="variants[${variantIndex}][variantItems][0][variantId]" value="0">
                    <input type="text" name="variants[${variantIndex}][variantItems][0][name]" placeholder="Item 1" required><br><br>
                </div>

                <div data-item-index="1">
                    <input type="hidden" name="variants[${variantIndex}][variantItems][1][id]" value="0">
                    <input type="hidden" name="variants[${variantIndex}][variantItems][1][variantId]" value="0">
                    <input type="text" name="variants[${variantIndex}][variantItems][1][name]" placeholder="Item 2" required><br><br>
                </div>
            </div>

            <button type="button" onclick="addItem(${variantIndex})">Add Item</button>
            <button type="button" onclick="removeVariant(this, null)">Remove Variant</button>
            <hr>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", html);
    variantIndex++;
}

// remove variant
function removeVariant(btn, id) {
    if (id) {
        document.getElementById('deleted-container').insertAdjacentHTML(
            "beforeend",
            `<input type="hidden" name="variantsDeleted[]" value="${id}">`
        );
    }
    btn.parentElement.remove();
}

// add item
function addItem(variantId) {
    const itemsDiv = document.getElementById("variant-items-" + variantId);
    const index = itemsDiv.children.length;

    let html = `
        <div data-item-index="${index}">
            <input type="hidden" name="variants[${variantId}][variantItems][${index}][id]" value="0">
            <input type="hidden" name="variants[${variantId}][variantItems][${index}][variantId]" value="0">
            <input type="text" name="variants[${variantId}][variantItems][${index}][name]" 
                   placeholder="Item ${index + 1}" required>

            <button type="button" onclick="removeItem(this, null)">Remove Item</button>
            <br><br>
        </div>
    `;

    itemsDiv.insertAdjacentHTML("beforeend", html);
}

// remove item
function removeItem(btn, id) {
    if (id) {
        document.getElementById('deleted-container').insertAdjacentHTML(
            "beforeend",
            `<input type="hidden" name="variantItemsDeleted[]" value="${id}">`
        );
    }
    btn.parentElement.remove();
}
</script>

</body>
</html>
