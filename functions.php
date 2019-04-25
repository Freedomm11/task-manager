<?php

//Соединение с базой данных
function config()
{
    $host = 'localhost';
    $db   = 'task-manager';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return $pdo = new PDO($dsn, $user, $pass, $opt);
};


//Валидация: проверка данных на пустоту
function validate()
{
    foreach ($_POST as $input){
        if(empty($input)){
            $errorMessage = 'Пожалуйста, заполните все поля!';
            include 'errors.php';
            exit;
        }
    };
};


//Функция для соли
function generateSalt()
{
    $salt = '';
    $saltLength = 8; //длина соли
    for($i=0; $i<$saltLength; $i++) {
        $salt .= chr(mt_rand(33,126)); //символ из ASCII-table
    }
    return $salt;
};


//Проверка на ошибки execute
function errors($result)
{
    if(!$result) {
        $errorMessage = 'Ошибка данных';
        include 'errors.php';
        exit;
    }
};