<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 17:36
 */

namespace App;
class ModelOrders
{
    protected $pdo;

    public function __construct()
    {
        $this->connect();
    }

    //Соединение с базой данный
    protected function connect()
    {
        // подключаем настройки базы данных
        $config = include(__DIR__ . DIRECTORY_SEPARATOR . 'config.php');
        $pdoConfig = (object)$config["db"];
        try {
            //Connect to MySQL using the PDO object.
            $this->pdo = new \PDO(  // обратный слеш говорит о глобальнои пространсте имен
                sprintf('mysql:host=%s;dbname=%s', $pdoConfig->host, $pdoConfig->dbname),
                $pdoConfig->username,
                $pdoConfig->password
            );
        } catch (PDOException $e) {
            echo "Error connect to database: " . $e->getMessage() . "\n";
            return null;
        }
    }

    public function userAdd($email, $name, $phone)
    {
        $id = null;
        try {
            $prepare = $this->pdo->prepare('INSERT INTO users(email, name, phone) VALUE (:email, :name, :phone) ');
            $prepare->execute(['email' => $email, 'name' => $name, 'phone' => $phone]);
            //Получаю id нового пользователя
            $data = userExists($email);
            $id = $data[0]->id;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $id;
    }

    public function userExists($email)
    {
        $data = [];
        try {
            $prepare = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $prepare->execute(['email' => $email]);
            $data = $prepare->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $data;
    }

    public function orderAdd($id, $address, $comment, $payment, $callback)
    {
        $data = [];
        try {
            $prepare = $this->pdo->prepare('INSERT INTO orders(userId, address, comment, payment, callback) VALUE (:id, :address, :comment, :payment, :callback)');
            $prepare->execute(['id' => $id, 'address' => $address, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
            //Получаем номер заказа
            $stmt = $this->pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 1');
            $data = $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $data;
    }
}