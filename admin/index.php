<?php
require_once __DIR__ . '/../includes/functions.php'; // Include the shared functions file
require_once __DIR__ . '/../db/conn.php'; // Include the database connection file

session_start();
if (!isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'admin' ) {
    header('Location: ../login.php');
    exit();
}

// Fetch users
$users = getAll('users');

// Fetch documents
$documents = getAll('documents');

// Fetch orders
$orders = getAll('orders');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        button{
            border: none;
            padding: .3rem;
            border-radius: .3rem;
        }
        th{
            font-size: .8rem;
        }
        tr:nth-child(odd){
            background-color: lightgray;
        }
    </style>
</head>
<body class="bg-light">
    <header>
        <nav class = "w-100 d-flex p-2 align-items-center justify-content-center gap-2 bg-dark">
            <div class="d-flex p-2 align-items-center justify-content-center gap-2">
               <h1 class="text-light">Nam<span class="text-warning">Press</span></h1> 
               <div class="bg-light d-flex flex-column p-2 align-items-center justify-content-center gap-2" style="width: 40px; height: 40px; cursor: pointer;">
                    <div class="w-100 bg-dark" style="height: .2rem;"></div><div class="w-100 bg-dark" style="height: .2rem;"></div><div class="w-100 bg-dark" style="height: .2rem;"></div>
               </div>
            </div>
            <div class="d-flex p-2 align-items-center justify-content-center gap-2" style="width: 70%">
                <h6 class="text-light">Hello admin</h6>
            </div>
        </nav>
    </header>
    <div class="bg-secondary mt-2 flex-column p-2 align-items-center justify-content-start gap-2" style="width: 200px; height: 80vh; position: absolute; display: none;">
        <button class="w-100">Home</button><button class="w-100">Users</button> <button class="w-100">Documents</button> <button class="w-100">Orders</button> 
    </div>

    <div class="p-2 mt-4">
        <h2>Over View</h2>
    </div>
    <div class="w-100  d-flex p-2 align-items-center justify-content-center gap-2">
        <div class="bg-secondary p-2 w-100 rounded text-light">
            <h2>100</h2>
            <h6>Users</h6>
        </div>
        <div class=" p-2 w-100 rounded" style="border: .1rem solid orangered; background-color: orange;">
            <h2>100</h2>
            <h6>Unverified Users</h6>
        </div>
        <div class="text-light bg-primary p-2 w-100 rounded">
            <h2>100</h2>
            <h6>Blocked Users</h6>
        </div>
        <div class="bg-warning p-2 w-100 rounded">
            <h2>100</h2>
            <h6>Documents</h6>
        </div>
    </div>

    <div class="users w-100 p-2" style="flex-wrap: wrap;">
        
        <div style=" max-height: 50vh; overflow: auto; min-width: 100%;">
            <h2>Users</h2>
            <table class="w-100">
                <tr class="bg-dark text-light">
                    <th class="p-2">ID</th>
                    <th class="p-2">Username</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">User Type</th>
                    <th class="p-2">Approved</th>
                    <th class="p-2">Action</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <div class="w-100" >
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
                    </div>
                
                <?php endforeach; ?>
            </table>
            
        </div>
        <div class="w-100 d-flex p-2 align-items-center justify-content-end">
            <button class="p-2 bg-primary text-light">View More</button>
        </div>
    </div>
        <div class="p-2 documents mt-4" style="width: 100%;">
            <h2>Documents</h2>
            <div class="w-100" style=" max-height: 50vh; overflow: auto;">
                <table class="w-100">
                    <tr class="bg-dark text-light">
                        <th class="p-2">ID</th>
                        <th class="p-2">User ID</th>
                        <th class="p-2">Document Type</th>
                        <th class="p-2">Action</th>
                    </tr>
                    <?php foreach ( $documents as $document): ?>
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
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="w-100 d-flex p-2 align-items-center justify-content-end">
                <button class="p-2 bg-primary text-light">View More</button>
            </div>
        </div>
    
    

    
    <div class="orders mt-4 w-100">
        <h2>Orders</h2>
        <table class="w-100">
            <tr class="bg-dark text-light">
                <th class="p-2">ID</th>
                <th class="p-2">Product ID</th>
                <th class="p-2">Buyer ID</th>
                <th class="p-2">Quantity</th>
                <th class="p-2">Total Price</th>
                <th class="p-2">Order Date</th>
            </tr>
            <?php foreach ( $orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['product_id']; ?></td>
                <td><?php echo $order['buyer_id']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['total_price']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="w-100 d-flex p-2 align-items-center justify-content-end">
            <button class="p-2 bg-primary text-light">View More</button>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>