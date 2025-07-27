<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Handle form submissions
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_id'])) {
        // Delete product
        $id = intval($_POST['delete_id']);
        $sql = "DELETE FROM products WHERE id = $id";
        if ($conn->query($sql)) {
            $message = "Product deleted successfully!";
        } else {
            $message = "Error deleting product: " . $conn->error;
        }
    } else {
        // Add/Update product
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = $conn->real_escape_string($_POST['name']);
        $desc = $conn->real_escape_string($_POST['description']);
        $image = $conn->real_escape_string($_POST['image_url']);
        $affiliate = $conn->real_escape_string($_POST['affiliate_url']);
        $price_original = floatval($_POST['price_original']);
        $price_discounted = floatval($_POST['price_discounted']);

        if ($id > 0) {
            $sql = "UPDATE products SET name='$name', description='$desc', image_url='$image', 
                    affiliate_url='$affiliate', price_original=$price_original, 
                    price_discounted=$price_discounted WHERE id=$id";
            $success_msg = "Product updated!";
        } else {
            $sql = "INSERT INTO products (name, description, image_url, affiliate_url, 
                    price_original, price_discounted) VALUES ('$name', '$desc', '$image', 
                    '$affiliate', $price_original, $price_discounted)";
            $success_msg = "Product added!";
        }

        if ($conn->query($sql)) {
            $message = $success_msg;
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Fetch products
$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
if ($result) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Edit mode check
$editing = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
$editingProduct = null;
if ($editing) {
    $pid = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM products WHERE id=$pid");
    if ($res && $res->num_rows === 1) {
        $editingProduct = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h1>Admin Dashboard</h1>

  <?php if($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="admin-actions">
    <a href="?action=add" class="button">Add New Product</a>
  </div>

  <?php if (isset($_GET['action']) && ($_GET['action'] === 'add' || $editingProduct)): ?>
    <h2><?= $editingProduct ? 'Edit' : 'Add' ?> Product</h2>
    <form method="POST">
      <?php if ($editingProduct): ?>
        <input type="hidden" name="id" value="<?= $editingProduct['id'] ?>">
      <?php endif; ?>
      <input type="text" name="name" placeholder="Name" value="<?= $editingProduct['name'] ?? '' ?>" required>
      <textarea name="description" placeholder="Description"><?= $editingProduct['description'] ?? '' ?></textarea>
      <input type="text" name="image_url" placeholder="Image URL" value="<?= $editingProduct['image_url'] ?? '' ?>" required>
      <input type="text" name="affiliate_url" placeholder="Affiliate URL" value="<?= $editingProduct['affiliate_url'] ?? '' ?>" required>
      <input type="number" step="0.01" name="price_original" placeholder="Original Price" value="<?= $editingProduct['price_original'] ?? '' ?>" required>
      <input type="number" step="0.01" name="price_discounted" placeholder="Discounted Price" value="<?= $editingProduct['price_discounted'] ?? '' ?>" required>
      <button type="submit"><?= $editingProduct ? 'Update' : 'Add' ?> Product</button>
    </form>
  <?php endif; ?>

  <?php if (!empty($products)): ?>
    <div class="admin-products">
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <h3><?= htmlspecialchars($product['name']) ?></h3>
          <p><?= htmlspecialchars($product['description']) ?></p>
          <div class="admin-controls">
            <a href="?action=edit&id=<?= $product['id'] ?>" class="button">Edit</a>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="delete_id" value="<?= $product['id'] ?>">
              <button type="submit" class="button delete">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No products found.</p>
  <?php endif; ?>

  <p><a href="logout.php" class="button">Logout</a></p>
  <script src="../assets/js/script.js"></script>
</body>
</html>