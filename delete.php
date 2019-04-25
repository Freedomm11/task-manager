<?php

require_once "functions.php";

$id = $_GET['id'];

//подготовка и выполнение запроса к БД
$pdo = config();
$stmt = $pdo->prepare('DELETE FROM article WHERE id = ?');
$stmt->execute([$id]);

// переадресация на главную
header('Location: /list.php'); exit;