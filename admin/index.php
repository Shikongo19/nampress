<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database');

// Fetch users
$users = $conn->query("SELECT * FROM users");

// Fetch documents
$documents = $conn->query("SELECT * FROM documents");

// Fetch orders
$orders = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>
</head>
<body>
    <h1>Admin Portal</h1>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Approved</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['user_type']; ?></td>
            <td><?php echo $user['approved'] ? 'Yes' : 'No'; ?></td>
            <td>
                <a href="approve_user.php?id=<?php echo $user['id']; ?>">Approve</a>
                <a href="reject_user.php?id=<?php echo $user['id']; ?>">Reject</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Documents</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Document Type</th>
            <th>Action</th>
        </tr>
        <?php while ($document = $documents->fetch_assoc()): ?>
        <tr>
            <td><?php echo $document['id']; ?></td>
            <td><?php echo $document['user_id']; ?></td>
            <td><?php echo $document['document_type']; ?></td>
            <td>
                <a href="view_document.php?id=<?php echo $document['id']; ?>">View</a>
                <a href="approve_document.php?id=<?php echo $document['id']; ?>">Approve</a>
                <a href="reject_document.php?id=<?php echo $document['id']; ?>">Reject</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Orders</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product ID</th>
            <th>Buyer ID</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['product_id']; ?></td>
            <td><?php echo $order['buyer_id']; ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td><?php echo $order['total_price']; ?></td>
            <td><?php echo $order['order_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>