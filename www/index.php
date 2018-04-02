<?php
require '../vendor/autoload.php';
// начинаем работать с сессией
session_start();

$appDir = realpath(__DIR__ . '/../app');

// стартовая страница
if ($_SERVER['REQUEST_URI'] == "/") {
    constructTwig([]);
    return 0;
}

// добавляем в базу
if (!empty($_POST) && $_SERVER['REQUEST_URI'] == "/order/add") {
    echo "<pre>";
    print_r($appDir . DIRECTORY_SEPARATOR . 'app.php');
    die();
    require_once($appDir . DIRECTORY_SEPARATOR . 'app.php');
    return 0;
}

// просмотр пользователей (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin") {
    require_once($appDir . DIRECTORY_SEPARATOR . '../www/admin.php');
    return 0;
}

// просмотр заказов (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin/orders") {
    // тут код вывода
    return 0;
}
function constructTwig()
{
    $loader = new Twig_Loader_Filesystem('../app');
    $twig = new Twig_Environment($loader);
    showrecaptcha($twig);
}

function showrecaptcha($twig)
{
    $sitekey = '6Ld0MVAUAAAAAE9z7jgUnAyTcnQ3ZBUIoCLgjEf3';
    echo $twig->render('templ.twig', [
        'sitekey' => $sitekey,
        'url' => '/order/add'
    ]);
}

// такой страницы нет
header("HTTP/1.0 404 Not Found");