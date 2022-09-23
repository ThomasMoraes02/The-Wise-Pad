<?php 

require_once __DIR__ . "../../../vendor/autoload.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if(in_array('api', $uri) && $_GET['url']) {
    $search = array_search('api', $uri);

    for ($i=0; $i <= $search; $i++) { 
        array_shift($uri);
    }

    $service = 'App\Application\\'.ucfirst(current($uri)).'Service';

    try {
        echo call_user_func_array(array(new $service, $requestMethod), $uri);
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

// print_r($uri);
// print_r($requestMethod);
// print_r($service);