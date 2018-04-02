<?php

//use Twig_Environment;
//Пдключение БД
require_once('../app/db.php');
//
try {
    $stmt = $pdo->query('SELECT u.email, u.name, u.phone, o.address, o.comment FROM users u, orders o WHERE u.id = o.userId ORDER BY u.id');
    $data['users'] = $stmt->fetchAll(pdo::FETCH_OBJ);
    $file = 'admin.twig';
    $loader = new Twig_Loader_Filesystem(__DIR__ . '\\');
    $twig = new Twig_Environment($loader);
    echo $twig->render($file, $data);
} catch (Exception $e) {
    echo $e->getMessage();
}