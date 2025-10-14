<?php
$request_uri = $_SERVER['REQUEST_URI'];

if (strpos($request_uri, '?') !== false) {
    $request_uri = strstr($request_uri, '?', true);
}

//routing antar pagenya
switch ($request_uri) {
    case '/':
    case '/login':
        require_once __DIR__ . '/../views/pages/login.php';
        break;

    case '/signup':
        require_once __DIR__ . '/../views/pages/signup.php';
        break;

    case '/terms':
        require_once __DIR__ . '/../views/pages/terms_of_use.php';
        break;

    case '/home':
        require_once __DIR__ . '/../views/pages/home.php';
        break;

    case '/courses':
        require_once __DIR__ . '/../views/pages/courses.php';
        break;

    case '/dashboard':
        require_once __DIR__ . '/../views/pages/dashboard.php';
        break;
    
    //Counter error 404 kalo gaada page yang sesuai
    default:
        http_response_code(404);
        echo "<h1>404 Page Not Found</h1>";
        break;
}