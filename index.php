<?php
/**
 * Toki - authorization microservice
 * Author: WTERH
 * GitHub author: https://github.com/wterh
 * GitHub project: https://github.com/wterh/toki
 * License: AS IS
 */
error_reporting(E_ALL);

const BASE_DIR = __DIR__;

date_default_timezone_set('Europe/Moscow');

require_once BASE_DIR.'/modules/database.class.php';
require_once BASE_DIR.'/modules/model.class.php';
require_once BASE_DIR.'/modules/table.class.php';
require_once BASE_DIR.'/modules/base.class.php';
require_once BASE_DIR.'/modules/router.class.php';
require_once BASE_DIR.'/modules/request.class.php';

use app\libs\Database;
use app\models\Request;
use app\models\Table;
use app\models\Base;
use app\core\Model;
use Xesau\Router;

$router = new Router(function ($method, $path, $statusCode, $exception) {
    http_response_code($statusCode);
    exit(
        header("HTTP/1.1 404 Not found")
    );
});

$router->get('/', function() {
    // Пасхал очка
    exit(
        header("HTTP/1.1 418 I’m a teapot")
    );
});
$router->post('/api/(\w+)/', function($methodName) {
    $methodNames = array_diff(scandir(BASE_DIR.DIRECTORY_SEPARATOR."api"), array('.', '..'));
    // print_r($methodNames);
    // die;
    if(in_array("{$methodName}.php",$methodNames)) { // Проверяем наличие требуемого метода
        $db = new Base('tokens');
        $ch = new Request();
        require_once(BASE_DIR.DIRECTORY_SEPARATOR."api/{$methodName}.php");
    } else {
        exit(
            header("HTTP/1.1 405 Method Not Allowed")
        );
    }
});

$router->get('/docs/(\w+)', function($docPage) {
    $docPages = array_diff(scandir(BASE_DIR.DIRECTORY_SEPARATOR."docs"), array('.', '..'));
    if(in_array("{$docPage}.php",$docPages)) { // Проверяем наличие требуемой страницы документации
        require_once(BASE_DIR.DIRECTORY_SEPARATOR."docs/{$docPage}.php");
    } else {
        exit(
            header("HTTP/1.1 405 Method Not Allowed")
        );
    }
});

$router->get('/module/', function(){
    $module = file_get_contents(BASE_DIR.DIRECTORY_SEPARATOR."docs/module.php");
    header('Content-Disposition: attachment; filename="module.php"');
    header('application/octet-stream');
    echo $module;
});

$router->get('/schema/', function(){
    $schema = file_get_contents(BASE_DIR.DIRECTORY_SEPARATOR."assets/schema.svg");
    header('Content-Disposition: attachment; filename="toki.svg"');
    header('application/octet-stream');
    echo $schema;
});

$router->dispatchGlobal();