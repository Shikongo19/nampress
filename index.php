<?php
// index.php - Main routing file
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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
$router = new Router('https://www.bluestoneinvestment.com.na');

// Define routes
$router->addRoute('/', function() {
    require __DIR__ . '/src/main/main.php';
    return true;
});

$router->addRoute('/gallery', function() {
    require __DIR__ . '/src/main/gallary.php';
    return true;
});

$router->addRoute('/sendContactForm', function() {
    require __DIR__ . '/src/main/proccessForm.php';
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