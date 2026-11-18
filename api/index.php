<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$file = basename($uri);

chdir(__DIR__ . '/../public');

if ($uri === '/' || $uri === '' || $file === '' || $file === 'index.php') {
    require __DIR__ . '/../public/index.php';
    exit;
}

$target = __DIR__ . '/../public/' . $file;
if (is_file($target)) {
    require $target;
    exit;
}

if (is_file($target . '.php')) {
    require $target . '.php';
    exit;
}

http_response_code(404);
echo "404 Not Found";
