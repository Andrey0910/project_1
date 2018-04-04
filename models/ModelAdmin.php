<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 20:44
 */

namespace App;

class ModelAdmin
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

    public function getAll()
    {
        $data = [];
        try {
            $prepare = $this->pdo->prepare('SELECT u.email, u.name, u.phone, o.address, o.comment FROM users u, orders o WHERE u.id = o.userId ORDER BY u.id');
            $prepare->execute();
            $data = $prepare->fetchAll(\PDO::FETCH_OBJ);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $data;
    }
}