<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'nampress');

// Fetch user's products
$products = $conn->query("SELECT * FROM products WHERE user_id = $user_id");

// Fetch user's orders
$orders = $conn->query("SELECT * FROM orders WHERE buyer_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Portal</title>
</head>
<body>
    <h1>Client Portal</h1>

    <h2>My Products</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php while ($product = $products->fetch_assoc()): ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['description']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>My Orders</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['product_id']; ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td><?php echo $order['total_price']; ?></td>
            <td><?php echo $order['order_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>