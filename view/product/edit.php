<form method="post" action="index.php?act=save">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
    <button type="submit">Save Changes</button>
</form>
