<?php
// начинаем работать с сессией
session_start();

$appDir = realpath(__DIR__ . '/../app');

// стартовая страница
if ($_SERVER['REQUEST_URI'] == "/") {
    require_once($appDir .DIRECTORY_SEPARATOR . 'template.php');
    return 0;
}

// добавляем в базу
if (!empty($_POST) && $_SERVER['REQUEST_URI'] == "/order/add") {
    require_once($appDir .DIRECTORY_SEPARATOR . 'app.php');
    return 0;
}

// просмотр пользователей (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin/users") {
    // тут код вывода
    return 0;
}

// просмотр заказов (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin/orders") {
    // тут код вывода
    return 0;
}

// такой страницы нет
header("HTTP/1.0 404 Not Found");