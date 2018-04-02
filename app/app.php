<?php

use PHPMailer\PHPMailer\PHPMailer;
use ReCaptcha\ReCaptcha;

submit();
//Пдключение БД
require_once('db.php');
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
    , "Ваш заказ будет доставлен по адресу" => $address
    , "Заказ" => "DarkBeefBurger за 500 рублей, 1 шт."
    , "ps" => "Спасибо - это ваш первый заказ!"
    , "time" => date("Y-m-d H:i:s")
];
$json = json_encode($order);
$dir = "out";
$file = (string)time() . ".txt";
if (!file_exists($dir)) {
    mkdir($dir, 0700, true);
}
$fullPath = "./" . $dir . "/" . $file;
file_put_contents($fullPath, $json);
sentMail();
function userExist($pdo, $email)
{
    $prepare = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $prepare->execute(['email' => $email]);
    $data = $prepare->fetchAll(pdo::FETCH_OBJ);
    return $data;
}

function sentMail()
{
    try {
        $maile = new PHPMailer();
        $maile->isSMTP();
        $maile->SMTPAuth = true;
        $maile->Host = "smtp.yandex.ru";// возможно не верный host
        $maile->Username = "asdfrfk@yandex.ru";
        $maile->Password = "lkjejml12348";
        $maile->SMTPSecure = "ssl";
        $maile->Port = 465;
        $maile->setFrom("asdfrfk@yandex.ru", "ааа");
        $maile->addAddress("petrovaa71@mail.ru", "Андрей");
        $maile->addAttachment("../composer.json");
        $maile->addReplyTo("asdfrfk@yandex.ru", "ааа");
        $maile->CharSet = "UTF-8";
        $maile->isHTML(true);
        $maile->Subject = "Письмо с сайта " . date("d.m.Y H:i:s");
        $maile->Body = "This is the HTML message body <b>in bold</b>";
        $maile->AltBody = "Это HTML сообщение.";
        if (!$maile->send()) {
            echo "Сообщение не может быть отправлено.";
            echo "Mailer Errer:" . $maile->ErrorInfo;
        } else {
            echo "Сообщение отправлено.";
        }
    } catch (Exception $e) {
        $e->getMessage();
    }
}

function submit()
{
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $gRecaptchaResponse = $_REQUEST['g-recaptcha-response'];
    $recaptcha = new ReCaptcha("6Ld0MVAUAAAAAE-JwV29wOdxK-yQ78EudNkjFXrp");
    $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
    if (!$resp->isSuccess()) {
        echo "Поражен вашей неудачей, сударь", "<br>";
        echo "<a href='/'>Назад</a>";
        die();
    }
}
