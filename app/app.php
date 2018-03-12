<?php
echo "Hi", "<bt>";
$dsn = "mysql:host=localhost;dbname=loftschool;charset=utf8";
$pdo = new PDO($dsn, "root", "");
$stmt = $pdo->query("SELECT * FROM aaa");
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
print_r($result);
die();