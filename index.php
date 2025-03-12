<?php
// index.php - Main routing file
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/db/conn.php';

// Define global variables
global $companies, $countries, $Id, $option, $request, $basePath, $users, $user, $conn;


function getConn() {
    global $con;
    $con = $conn;
    return $con;
}

// Function to fetch all records from a table
function getAllUser($table) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// Function to fetch products by company ID
function getProductsByCompany($companyID) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE company_id = :company_id");
        $stmt->bindParam(':company_id', $companyID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// Function to fetch products by company ID
function getUserById($table, $Id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = :id");
        $stmt->bindParam(':email', $Id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}


// Generate and store CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

class Router {
    private $basePath;
    private $routes = [];

    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }

    public function addRoute($path, $handler) {
        $this->routes[$path] = $handler;
    }

    public function handleRequest() {
        $request = $_SERVER['REQUEST_URI'];
        $request = strtok($request, '?');
        $request = str_replace($this->basePath, '', $request);
        $request = '/' . ltrim($request, '/');

        if (isset($this->routes[$request])) {
            return $this->routes[$request]();
        }
        
        return $this->handle404();
    }

    private function handle404() {
        http_response_code(404);
        require __DIR__ . '/src/404/404.php';
        return true;
    }
}

// Initialize router
$router = new Router('/nampress');

// Define routes
$router->addRoute('/', function() {
    require __DIR__ . '/src/views/index.php';
    return true;
});

$router->addRoute('/admin', function() {
    require __DIR__ . '/admin/index.php';
    return true;
});

$router->addRoute('/client', function() {
    require __DIR__ . '/client/index.php';
    return true;
});

$router->addRoute('/login', function() {
    require __DIR__ . '/login.php';
    return true;
});

$router->addRoute('/register', function() {
    require __DIR__ . '/register.php';
    return true;
});

$router->addRoute('/auth/logout/admin', function() {
    session_unset();
    session_destroy();
    header("Location: https://www.bluestoneinvestment.com.na/");
    exit();
});

// Add other routes similarly...

// Handle the request

// If no route was matched, display the custom 404 page

$router->handleRequest();