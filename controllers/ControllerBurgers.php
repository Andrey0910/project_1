<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use ReCaptcha\ReCaptcha;

class ControllerBurgers extends MainController
{
    protected $host;
    protected $username;
    protected $password;
    protected $sitekey;
    protected $secret;

    public function __construct()
    {
        parent::__construct();
        // подключаем настройки для изменения фотографии.
        $config = include(__DIR__ . DIRECTORY_SEPARATOR . '../models/config.php');
        //получаем данные для PHPMailer
        $phpMailer = (object)$config["phpMailer"];
        $this->host = $phpMailer->host;
        $this->username = $phpMailer->username;
        $this->password = $phpMailer->password;
        //Получаем данные для recaptcha
        $recaptcha = (object)$config["recaptcha"];
        $this->sitekey = $recaptcha->sitekey;
        $this->secret = $recaptcha->secret;
    }

    public function index($viewName)
    {
        $sitekey = $this->sitekey; // recaptcha
        $data = [
            'sitekey' => $sitekey,
            'url' => '/burgers/order'
        ];
        $this->view->twigLoad($viewName, $data);
    }

    public function order()
    {
        $this->submit();
        try {
            $modelOrders = new ModelOrders();
            //Получаем данные пользовптеля с формы
            $id = null;
            $email = $_POST['email'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $user = $modelOrders->userExists($email);
            if (count($user) > 0) {
                //Получаем данне пользователя
                $id = $user[0]->id;
                $email = $user[0]->email;
                $name = $user[0]->name;
                $phone = $user[0]->phone;
            } else {
                $id = $modelOrders->userAdd($email, $name, $phone);
            }
            //Получаем данные заказа с формы
            $address = "Улица: " . $_POST['street']
                . ", Дом: " . $_POST['home']
                . ", Корпус: " . $_POST['part']
                . ", Квартира: " . $_POST['appt']
                . ", Этаж: " . $_POST['floor'];
            $comment = $_POST['comment'];
            $payment = ($_POST['payment'] == "on") ? 1 : 0;
            $callback = ($_POST['callback'] == "on") ? 1 : 0;
            //Записываем заказ в бвзу
            $order = $modelOrders->orderAdd($id, $address, $comment, $payment, $callback);
            $this->createJson($order);
            $this->sentMail($order);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function createJson($data)
    {
        $order = [
            "Номер заказа" => $data[0]->id
            , "Ваш заказ будет доставлен по адресу" => $data[0]->address
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
    }

    public function sentMail($data)
    {
        $htmlBody = '
            <head>
            <title>Ваш заказ</title>
            </head>
            <body>
                <p>Номер заказа ' . $data[0]->id . '</p><br>
                <p>Ваш заказ будет доставлен по адресу: ' . $data[0]->address . '</p><br>
                <p>Заказ: DarkBeefBurger за 500 рублей, 1 шт.</p><br>
                <p>ps: Спасибо - это ваш первый заказ!</p><br>
                <p>Время заказа: ' . date("Y-m-d H:i:s") . '</p><br>
            </body>>';
        try {
            $maile = new PHPMailer();
            $maile->isSMTP();
            $maile->SMTPAuth = true;
            $maile->Host = $this->host;
            $maile->Username = $this->username;
            $maile->Password = $this->password;
            $maile->SMTPSecure = "ssl";
            $maile->Port = 465;
            $maile->setFrom($this->username, "ааа");
            $maile->addAddress("petrovaa71@mail.ru", "Андрей");
            $maile->addAttachment("../composer.json");
            $maile->addReplyTo($this->username, "ааа");
            $maile->CharSet = "UTF-8";
            $maile->isHTML(true);
            $maile->Subject = "Письмо с сайта " . date("d.m.Y H:i:s");
            $maile->Body = $htmlBody;
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

    public function submit()
    {
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $gRecaptchaResponse = $_REQUEST['g-recaptcha-response'];
        $recaptcha = new ReCaptcha($this->secret);
        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
        if (!$resp->isSuccess()) {
            echo "Поражен вашей неудачей, сударь", "<br>";
            echo "<a href='/'>Назад</a>";
            die();
        }
    }
}
