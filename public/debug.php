<?php
echo "<h1>Debug Information - Public Folder</h1>";
echo "<p>REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>PHP_SELF: " . $_SERVER['PHP_SELF'] . "</p>";

// Test si l'autoloader fonctionne
require dirname(__DIR__) . '/vendor/autoload.php';

echo "<p>Autoloader: OK</p>";

// Test si les classes existent
echo "<p>Router class exists: " . (class_exists('Mini\Core\Router') ? 'YES' : 'NO') . "</p>";
echo "<p>HomeController class exists: " . (class_exists('Mini\Controllers\HomeController') ? 'YES' : 'NO') . "</p>";

// Test le router
use Mini\Core\Router;
use Mini\Controllers\HomeController;

$routes = [
    ['GET', '/', [HomeController::class, 'index']],
];

$router = new Router($routes);
echo "<p>Router created: OK</p>";

// Test dispatch
echo "<p>Testing dispatch...</p>";
$router->dispatch($_SERVER['REQUEST_METHOD'], '/');
?>
