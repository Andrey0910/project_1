<?php
require_once '../core/MainController.php';
require_once '../core/MainView.php';
require_once '../models/ModelOrders.php';
require_once '../models/ModelAdmin.php';
require '../vendor/autoload.php';
$route = explode('/', $_SERVER['REQUEST_URI']);
$controllerName = 'burgers';
$actionName = 'index';
//Получаем контроллер
if (!empty($route[1])) {
    $controllerName = $route[1];
}
//Получаем действие
if (!empty($route[2])) {
    $explode = explode('?', $route[2]);
    $actionName = $explode[0];
}
try {
    //Получаем имя файла контроллера
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . '../controllers/Controller' . $controllerName . '.php';
    if (file_exists($fileName)) {
        require_once $fileName;
    } else {
        throw new Exception('File not found');
    }
    //Саздаем контроллер
    $className = '\App\Controller' . ucfirst(strtolower($controllerName));
    if (class_exists($className)) {
        $controller = new $className;
    } else {
        throw new Exception('Class not exists');
    }
    //Вызываем метод
    if (method_exists($controller, $actionName)) {
        $controller->$actionName(strtolower($controllerName));
    } else {
        throw new Exception('Method not exists');
    }
} catch (Exception $e) {
    echo $e->getMessage();
}









//// начинаем работать с сессией
//session_start();
//
//$appDir = realpath(__DIR__ . '/../controllers');
//
//// стартовая страница
//if ($_SERVER['REQUEST_URI'] == "/") {
//    constructTwig([]);
//    return 0;
//}
//
//// добавляем в базу
//if (!empty($_POST) && $_SERVER['REQUEST_URI'] == "/order/add") {
//    echo "<pre>";
//    print_r($appDir . DIRECTORY_SEPARATOR . 'controllers.php');
//    die();
//    require_once($appDir . DIRECTORY_SEPARATOR . 'controllers.php');
//    return 0;
//}
//
//// просмотр пользователей (административная панель)
//if ($_SERVER['REQUEST_URI'] == "/admin") {
//    require_once($appDir . DIRECTORY_SEPARATOR . '../www/admin.php');
//    return 0;
//}
//
//// работа с фото
//if ($_SERVER['REQUEST_URI'] == "/photo") {
//    require_once($appDir . DIRECTORY_SEPARATOR . '../www/photo.php');
//    return 0;
//}
//
//// уменьшенная фото
//if ($_SERVER['REQUEST_URI'] == "/photo_resize") {
//    require_once($appDir . DIRECTORY_SEPARATOR . '../www/photo_resize.php');
//    return 0;
//}
//
//// просмотр заказов (административная панель)
//if ($_SERVER['REQUEST_URI'] == "/admin/orders") {
//    // тут код вывода
//    return 0;
//}
//function constructTwig()
//{
//    $loader = new Twig_Loader_Filesystem('../controllers');
//    $twig = new Twig_Environment($loader);
//    showrecaptcha($twig);
//}
//
//function showrecaptcha($twig)
//{
//    $sitekey = '6Ld0MVAUAAAAAE9z7jgUnAyTcnQ3ZBUIoCLgjEf3';
//    echo $twig->render('templ.twig', [
//        'sitekey' => $sitekey,
//        'url' => '/order/add'
//    ]);
//}
//
//// такой страницы нет
//header("HTTP/1.0 404 Not Found");