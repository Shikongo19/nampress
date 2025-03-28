<?php
session_start();
require_once __DIR__ . '/includes/functions.php'; // Include the shared functions file
require_once __DIR__ . '/db/conn.php'; // Include the database connection file

$error;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user by email (username)
    $user = getUserById('users', $username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];

        if ($user['user_type'] === 'admin') {
            header('Location: admin/');
        } else {
            header('Location: client/');
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="d-flex align-items-center justify-content-center bg-light" style = "height: 100vh; width: 100%;">
        <?php if(isset($error)){
            echo '<script> alert("Invalid Username or Password."); </script>';
        } ?>
        <div class="rounded bg-dark p-4 d-flex flex-column align-items-center justify-content-center text-warning" style = "min-height: 30vh; min-width: 50%;">
            <h1>Login</h1>

            <form method="POST" class="w-100">
                <div class="w-100">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="w-100 p-2 rounded" id="username" name="username" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="w-100">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="w-100 p-2 rounded" id="password" name="password">
                </div>
                <div class="w-100" form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-warning">Submit</button>
            </form>
            <a href="register.php">Register</a> 
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>