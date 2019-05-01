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


//Проверка авторизации
function check_auth()
{
    $pdo = config();
    //Если пустая переменная auth из сессии ИЛИ она равна false (для авторизованного она true).
    if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
        //Проверяем, не пустые ли нужные нам куки
        if ( !empty($_COOKIE['email']) and !empty($_COOKIE['key']) ) {
            //Пишем email и ключ из КУК в переменные (для удобства работы):
            $email = $_COOKIE['email'];
            $key = $_COOKIE['key']; //ключ из кук (аналог пароля, в базе поле cookie)

            //Формируем и отсылаем SQL запрос:
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND cookie = ?');
            $stmt->execute([$email, $key]);
            $result = $stmt->fetch();

            //Если база данных вернула не пустой ответ - значит пара email-ключ_к_кукам подошла.
            if (!empty($result)) {
                //Пишем в сессию информацию о том, что мы авторизовались:
                $_SESSION['auth'] = true;
                //Пишем в сессию email и id пользователя
                $_SESSION['id'] = $result['id'];
                $_SESSION['email'] = $result['email'];
            }
        }
        else {
            //Если сессия и куки пустые, тогда делаем переадрессацию на авторизацию
            header('Location: login-form.php');exit;
        }
    }
};
