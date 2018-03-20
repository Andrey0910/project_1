<?php
//Пдключение БД
require_once ('db.php');
//Получаем даннык пользовптеля с формы
$id = null;
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
//Певеряем зарегистрирован ли пользовптель
$data = userExist($pdo, $email);
if (count($data) > 0) {
    //Получаем данне пользователя
    $id = $data[0]->id;
    $email = $data[0]->email;
    $name = $data[0]->name;
    $phone = $data[0]->phone;
} else {
    //Записываем нового пользователя
    $prepare = $pdo->prepare('INSERT INTO users(email, name, phone) VALUE (:email, :name, :phone) ');
    $prepare->execute(['email' => $email, 'name' => $name, 'phone' => $phone]);
    //Получаю id нового пользователя
    $data = userExist($pdo, $email);
    $id = $data[0]->id;
}
//Запмсываем заказ
$address = "Улица: " . $_POST['street']
    . ", Дом: " . $_POST['home']
    . ", Корпус: " . $_POST['part']
    . ", Квартира: " . $_POST['appt']
    . ", Этаж: " . $_POST['floor'];
$comment = $_POST['comment'];
$payment = ($_POST['payment'] == "on") ? 1 : 0;
$callback = ($_POST['callback'] == "on") ? 1 : 0;
//INSERT INTO `orders`(`userId`, `address`, `commtnt`, `payment`, `callback`) VALUES (5,'fsfgdfgg','jlfdkgjldf',0,0)
$prepare = $pdo->prepare('INSERT INTO orders(userId, address, comment, payment, callback) VALUE (:id, :address, :comment, :payment, :callback)');
$prepare->execute(['id' => $id, 'address' => $address, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
//Получаем номер заказа
$stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 1');
$data = $stmt->fetchAll(pdo::FETCH_OBJ);
$order = [
    "Номер заказа" => $data[0]->id
    ,"Ваш заказ будет доставлен по адресу" => $address
    ,"Заказ" => "DarkBeefBurger за 500 рублей, 1 шт."
    ,"ps" => "Спасибо - это ваш первый заказ!"
    ,"time" => date("Y-m-d H:i:s")
];
$json = json_encode($order);
$dir = "out";
$file = (string)time().".txt";
if (!file_exists($dir)) {
    mkdir($dir, 0700, true);
}
$fullPath = "./".$dir."/".$file;
file_put_contents($fullPath, $json);
function userExist($pdo, $email)
{
    $prepare = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $prepare->execute(['email' => $email]);
    $data = $prepare->fetchAll(pdo::FETCH_OBJ);
    return $data;
}