<?php
//echo "Hi", "<br>";
//Подкльлчение к БД
$dsn = "mysql:host=localhost;dbname=loftschool;charset=utf8";
$pdo = new PDO($dsn, "root", "");
//Получаем даннык пользовптеля с формы
$id = null;
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
//echo "email - ". $email, "<br>";
//echo "name - ". $name, "<br>";
//echo "phone - ". $phone, "<br>";

//Певеряем зарегистрирован ли пользовптель
$data = userExist($pdo, $email);
//$prepare = $pdo->prepare('SELECT * FROM users WHERE email = :email');
//$prepare->execute(['email' => $email]);
//$data = $prepare->fetchAll(pdo::FETCH_OBJ);
echo "email = " . $email . " записей - " . count($data), "<br>";
if (count($data) > 0) {
    echo "<pre>";
    print_r($data);
    //foreach ($data as $item){
    //$id = $item['id'];
    //echo "<pre>";
    //print_r($item);
    //echo "<br>", "id = ".$item->id;
    //}
    //Получаем данне пользователя
    $id = $data[0]->id;
    $email = $data[0]->email;
    $name = $data[0]->name;
    $phone = $data[0]->phone;
    //echo "<br>", "id = ".$id;
    //echo "<br>", "email = ".$email;
    //echo "<br>", "name = ".$name;
    //echo "<br>", "phone = ".$phone;
} else {
    //echo "<bt>", "000000";
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
echo "<pre>";
print_r($_POST);

$prepare = $pdo->prepare('INSERT INTO orders(userId, address, comment, payment, callback) VALUE (:id, :address, :comment, :payment, :callback)');
$prepare->execute(['id' => $id, 'address' => $address, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
//echo "<br>", "id - ".$id;
//echo "<br>", "address - ".$address;
//echo "<br>", "comment - ".$comment;
//echo "<br>", "payment - ".$payment;
//echo "<br>", "callback - ".$callback;

//$stmt = $pdo->query("SELECT * FROM users WHERE emsil = :email");
//$result = $stmt->fetchAll(PDO::FETCH_OBJ);
//print_r($result);
//die();
function userExist($pdo, $email)
{
    $prepare = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $prepare->execute(['email' => $email]);
    $data = $prepare->fetchAll(pdo::FETCH_OBJ);
    return $data;
}