<?php


require_once '../../autoload.php';

$dsn = 'mysql:host=localhost;dbname=vexpenses';
$db = new PDO($dsn, 'root', '');
$sql = "SELECT nome FROM contatos";
$result = $db->prepare($sql);
$result->execute();
$count = $result->rowCount();
$res = $result->fetchAll(PDO::FETCH_COLUMN);


echo json_encode($res);