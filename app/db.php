<?php
// подключаем настройки базы данных
$config = include (__DIR__ . DIRECTORY_SEPARATOR . 'config.php');

//
$pdoConfig = (object)$config["db"];

try {
    //Connect to MySQL using the PDO object.
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s', $pdoConfig->host,$pdoConfig->dbname),
        $pdoConfig->username,
        $pdoConfig->password
    );
} catch (PDOException $e) {
    echo "Error connect to database: " . $e->getMessage() . "\n";
    return null;
}

//Our SQL statement, which will select a list of tables from the current MySQL database.
$sql = "SHOW TABLE";

//Prepare our SQL statement,
$statement = $pdo->prepare($sql);

//Execute the statement.
$statement->execute();

if ($statement->errorCode() !== "00000") {
    echo implode(" ", $statement->errorInfo()) . "\n";
    return null;
}

//Fetch the rows from our statement.
$tables = $statement->fetchAll(PDO::FETCH_NUM);

//Loop through our table names.
foreach($tables as $table){
    //Print the table name out onto the page.
    echo $table[0], "\n";
}