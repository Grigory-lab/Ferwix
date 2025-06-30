<?php
    include "DB.php"; //включение файла с подключением к БД
    $entered = 0;

    if (isset($_SESSION['login-a'])) { //если переменная с логином установлена, то переменная entered становится 1
        $login = $_SESSION['login-a'];
        $entered = 1;
    }
    elseif (isset($_SESSION['login-r']))
    {
        $login = $_SESSION['login-r'];
        $entered = 1;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name-c']) && !empty($_POST['phone-c']) && !empty($_POST['mail-c'])
    && !empty($_POST['text-c'])) { //сохранение данных из формы в переменные сессии
        $_SESSION['name-c'] = htmlspecialchars($_POST['name-c']);
        $_SESSION['phone-c'] = htmlspecialchars($_POST['phone-c']);
        $_SESSION['mail-c'] = htmlspecialchars($_POST['mail-c']);
        $_SESSION['text-c'] = htmlspecialchars($_POST['text-c']);
    } 

    if (isset($_SESSION['name-c'])) { //Сохранение переменных сессии в другие переменные
        $namec = $_SESSION['name-c'];
        $phonec = $_SESSION['phone-c'];
        $mailc = $_SESSION['mail-c'];
        $textc = $_SESSION['text-c'];
    }

    if (isset($_POST['finish-c']) && isset($_SESSION['name-c']) && isset($_SESSION['phone-c']) && isset($_SESSION['mail-c'])
    && isset($_SESSION['text-c'])) { //условие "если переменные сессии установлены"
        if ($entered == 1) {
            $sql_id = "SELECT Client_ID FROM clients WHERE Login = '$login'";
            $result_id = mysqli_query($conn, $sql_id);
            $row_client = mysqli_fetch_assoc($result_id);
            $client_id = $row_client['Client_ID'];
            $sql = "INSERT INTO comments (Full_name, Phone_number, Mail, Text, Client_ID) VALUES 
            ('$namec', '$phonec', '$mailc', '$textc', '$client_id')";
            $result = mysqli_query($conn, $sql);
        }
        else {
            $sql = "INSERT INTO comments (Full_name, Phone_number, Mail, Text, Client_ID) VALUES 
            ('$namec', '$phonec', '$mailc', '$textc', NULL)";
            $result = mysqli_query($conn, $sql);
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferwix</title>
    <link rel="stylesheet" href="main.css"> <!-- подключение css -->
</head>
<body>
    <header class="header"> <!-- шапка сайта -->
        <div class="container header__content">
            <img src="img/logo.png" alt="Лого" width="100px" height="100px">
            <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item"><a class="menu__link" href="Main.php">Главная</a></li>
                    <li class="menu__item"><a class="menu__link" href="Masters.php">Мастера</a></li>
                    <li class="menu__item"><a class="menu__link" href="Services.php">Услуги</a></li>
                    <li class="menu__item"><a class="menu__link" href="Callback.php">Обратная связь</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="container-2"> <!-- блок с формой обратной связи -->
            <section class="standart-block"> 
                <h1>Обратная связь</h1>
                <p>Задайте нам свой вопрос</p>
                <form class="form-section" method="post">
                    <div class="form-call">
                        <div class="form-nonspecial">
                            <input type="text" placeholder="ФИО [Иванов Иван Иванович]" class="enter-FIO" name="name-c" id="only-letters" required title="Введите своё ФИО">
                            <input type="text" placeholder="Телефон [8 (000) 000 00 00]" class="enter-phone" name="phone-c" id="c-phone" required title="Формат: 8 (XXX) XXX XX XX" pattern="8 \(\d{3}\) \d{3} \d{2} \d{2}">                    
                            <input type="email" placeholder="Эл. почта [alex@gmail.com]" class="enter-mail" name="mail-c" required >
                        </div>
                        <textarea type="text" placeholder="Ваш вопрос" class="enter-question" name="text-c" required></textarea>
                    </div>
                    <input type="submit" value="Отправить" class="finish" name="finish-c">
                </form>
            </section>
        </div>

        <section class="contacts"> <!-- секция контактов -->
            <div class="container-2">
                <h2>Контакты</h2>
                <div class="contacts-all">
                    <p>+7(999) 323 99 19</p>
                    <p>https://web.telegram.org/</p>
                    <p>https://vk.com/</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer"> <!-- подвал сайта -->
        <div class="container-2">
            <p>Ferwix 2025</p>
        </div>
    </footer>

    <script>
        document.getElementById('only-letters').addEventListener('input', function () {
            this.value = this.value.replace(/[^A-Za-zА-Яа-яЁё\s]/g, ''); // Удаление всего, кроме букв и пробелов
        });

        document.getElementById('c-phone').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9()+\s]/g, ''); // Удаление всего, кроме цифр, +, () и пробелов
        });
    </script>
</body>
</html>