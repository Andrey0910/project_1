<?php
$dsn = "mysql:host=localhost;dbname=loftschool;charset=utf8";
$pdo = new PDO($dsn, "root", "");
$stmt = $pdo->query('SELECT u.email, u.name, u.phone, o.address, o.comment FROM users u, orders o WHERE u.id = o.userId ORDER BY u.id');
$data = $stmt->fetchAll(pdo::FETCH_OBJ);
echo "<table>";
echo "<tr>"
, "<td>", "email", "</td>"
, "<td>", "name", "</td>"
, "<td>", "phone", "</td>"
, "<td>", "address", "</td>"
, "<td>", "comment", "</td>"
, "</tr>";
foreach ($data as $item){
    echo "<tr>"
    , "<td>", $item->email, "</td>"
    , "<td>", $item->name, "</td>"
    , "<td>", $item->phone, "</td>"
    , "<td>", $item->address, "</td>"
    , "<td>", $item->comment, "</td>"
    , "</tr>";
}
echo "</table>";