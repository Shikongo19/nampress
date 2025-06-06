<?php
// index.php - Main routing file with CRUD support
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
    private $crudRoutes = [];

    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }

    public function addRoute($path, $handler, $methods = ['GET']) {
        $this->routes[$path] = [
            'handler' => $handler,
            'methods' => array_map('strtoupper', (array)$methods)
        ];
    }

    public function addCrudRoute($basePath, $controllerClass) {
        $this->crudRoutes[$basePath] = $controllerClass;
    }

    public function handleRequest() {
        $request = $_SERVER['REQUEST_URI'];
        $request = strtok($request, '?');
        $request = str_replace($this->basePath, '', $request);
        $request = '/' . ltrim($request, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // Check static routes first
        if (isset($this->routes[$request])) {
            $route = $this->routes[$request];
            if (!in_array($method, $route['methods'])) {
                return $this->handle405($route['methods']);
            }
            return $route['handler']();
        }

        // Check CRUD routes
        foreach ($this->crudRoutes as $basePath => $controllerClass) {
            if (strpos($request, $basePath) === 0) {
                return $this->handleCrudRequest($basePath, $request, $method, $controllerClass);
            }
        }

        return $this->handle404();
    }

    private function handleCrudRequest($basePath, $request, $method, $controllerClass) {
        $parts = explode('/', trim(str_replace($basePath, '', $request), '/'));
        $id = null;
        $action = 'index';

        if (count($parts) >= 1 && !empty($parts[0])) {
            if (is_numeric($parts[0])) {
                $id = $parts[0];
                $action = isset($parts[1]) ? $parts[1] : 'show';
            } else {
                $action = $parts[0];
                $id = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
            }
        }

        // Map HTTP methods to CRUD actions
        $methodActions = [
            'GET' => $id ? 'show' : 'index',
            'POST' => 'store',
            'PUT' => 'update',
            'PATCH' => 'update',
            'DELETE' => 'destroy'
        ];

        // Override action if it's a standard CRUD method
        if (isset($methodActions[$method])) {
            $action = $methodActions[$method];
        }

        // Verify CSRF token for non-GET requests
        if ($method !== 'GET') {
            $this->verifyCsrfToken();
        }

        // Instantiate controller and call action
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            $actionMethod = strtolower($method) . ucfirst($action);
            
            if (method_exists($controller, $action)) {
                return $controller->$action($id);
            } elseif (method_exists($controller, $actionMethod)) {
                return $controller->$actionMethod($id);
            }
        }

        return $this->handle404();
    }

    private function verifyCsrfToken() {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!$token || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }

    private function handle404() {
        http_response_code(404);
        require __DIR__ . '/src/404/404.php';
        return true;
    }

    private function handle405($allowedMethods) {
        http_response_code(405);
        header('Allow: ' . implode(', ', $allowedMethods));
        echo 'Method Not Allowed. Allowed methods: ' . implode(', ', $allowedMethods);
        return true;
    }
}

// Initialize router
$router = new Router('/nampress');

// Define standard routes
$router->addRoute('/', function() {
    require __DIR__ . '/src/views/index.php';
    return true;
}, ['GET']);

$router->addRoute('/admin', function() {
    require __DIR__ . '/admin/index.php';
    return true;
}, ['GET']);

$router->addRoute('/client', function() {
    require __DIR__ . '/client/index.php';
    return true;
}, ['GET']);

$router->addRoute('/login', function() {
    require __DIR__ . '/login.php';
    return true;
}, ['GET', 'POST']);

$router->addRoute('/register', function() {
    require __DIR__ . '/register.php';
    return true;
}, ['GET', 'POST']);

$router->addRoute('/auth/logout/admin', function() {
    session_unset();
    session_destroy();
    header("Location: https://www.bluestoneinvestment.com.na/");
    exit();
}, ['GET']);

// Add CRUD routes
$router->addCrudRoute('/api/posts', 'PostController');
$router->addCrudRoute('/api/users', 'UserController');
// Add more CRUD routes as needed

// Handle the request
$router->handleRequest();