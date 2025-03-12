<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$sql = "SELECT * FROM products WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found or you do not have permission to edit this product.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdi', $name, $description, $price, $product_id);

    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>
        <br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        <br>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>