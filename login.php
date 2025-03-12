<?php
session_start();
global $users;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $users = getUserById('users',$username );

    if (password_verify($password, $users['password'])) {
        
        $_SESSION['user'] = $users['username'];
        $_SESSION['user_id'] = $users['id'];
        $_SESSION['user_type'] = $users['user_type'];

        if ($_SESSION['user_type'] == 'admin') {
            header('Location: admin/');
        } else {
            header('Location: client/');
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">register</a>
</body>
</html>