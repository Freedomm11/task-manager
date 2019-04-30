<?php
session_start();
//Если сессия и куки пустые, тогда делаем переадрессацию на авторизацию
if (empty($_SESSION['auth']) and empty($_COOKIE['key']) )
{
    header('Location: login-form.php');exit;
}

require_once "functions.php";

$id = $_GET['id'];

//подготовка и выполнение запроса к БД
$pdo = config();
$stmt = $pdo->prepare('SELECT * FROM article WHERE id = ?');
$stmt->execute([$id]);
$result = $stmt->fetch();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Show</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
      <img src="assets/media/uploads/<? echo $result['image'];?>" alt="" width="400">
      <h2><? echo $result['title'];?></h2>
      <p><? echo $result['description'];?></p>
    </div>
  </body>
</html>
