<?php
    include "DB.php"; //включение файла с подключением к БД

    if (isset($_POST['logout'])) { //если кнопка выхода нажата
        session_unset(); //уничтожение переменных сессии
        session_destroy();  //уничтожение сессии
        header("Location: Main.php"); 
        exit();
    }
    $error = 0;

    $masters_query = "SELECT Master_ID, Full_name FROM masters"; // Получение списка мастеров
    $masters_result = mysqli_query($conn, $masters_query);

    $services_query = "SELECT Service_ID, Name FROM services";     // Получение списка услуг
    $services_result = mysqli_query($conn, $services_query);

    if (isset($_SESSION['login-a'])) { //Сохранение переменной логина сессии в другую переменную
        $login = $_SESSION['login-a'];
    }
    else
    {
        $login = $_SESSION['login-r'];
    }
    $sql = "SELECT Full_name, Login, Mail, Phone_number FROM clients WHERE Login = '$login'"; //запрос на получение данных пользователя
    $result = mysqli_query($conn, $sql); //сохранение результата
    $client = mysqli_fetch_assoc($result);


    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['master']) && !empty($_POST['service']) && !empty($_POST['date'])
    && !empty($_POST['time-e'])) { //сохранение данных из формы в переменные сессии
        $_SESSION['master'] = htmlspecialchars($_POST['master']);
        $_SESSION['service'] = htmlspecialchars($_POST['service']);
        $_SESSION['date'] = htmlspecialchars($_POST['date']);
        $_SESSION['time-e'] = htmlspecialchars($_POST['time-e']);
    } 

    if (isset($_SESSION['master'])) { //Сохранение переменных сессии в другие переменные
        $me = $_SESSION['master'];
        $se = $_SESSION['service'];
        $de = $_SESSION['date'];
        $te = $_SESSION['time-e'];
    }

    if (isset($_POST['entry']) && isset($_SESSION['master']) && isset($_SESSION['service']) && isset($_SESSION['date'])
    && isset($_SESSION['time-e'])) { //условие "если переменные сессии установлены"
        $sql_id = "SELECT Client_ID FROM clients WHERE Login = '$login'";
        $result_id = mysqli_query($conn, $sql_id);
        $row_client = mysqli_fetch_assoc($result_id);
        $client_id = $row_client['Client_ID'];

        $sqlm_id = "SELECT Master_ID FROM masters WHERE Master_ID = '$me'";
        $m_result = mysqli_query($conn, $sqlm_id);
        $row_master = mysqli_fetch_assoc($m_result);
        $master_id = $row_master['Master_ID'];

        $sqls_id = "SELECT Service_ID FROM services WHERE Service_ID = '$se'";
        $s_result = mysqli_query($conn, $sqls_id);
        $row_service = mysqli_fetch_assoc($s_result);
        $service_id = $row_service['Service_ID'];

        $sqlt_id = "SELECT Time_slot_ID FROM time_slots WHERE Time = '$te'";
        $t_result = mysqli_query($conn, $sqlt_id);
        $row_time = mysqli_fetch_assoc($t_result);
        $time_id = $row_time['Time_slot_ID'];

        $sqlp = "SELECT Date, Master_ID, Time_slot_ID FROM entries WHERE Date = '$de' AND Master_ID = '$master_id' AND Time_slot_ID = '$time_id'";
        $resultp = mysqli_query($conn, $sqlp);

        if (mysqli_num_rows ($resultp) == 0) {
            $sql = "INSERT INTO entries (Date, Master_ID, Service_ID, Time_slot_ID, Client_ID) VALUES 
            ('$de', '$master_id', '$service_id', '$time_id', '$client_id')";
            $result = mysqli_query($conn, $sql);
        }
        else //случай если такая же запись уже существует
        { 
            $error = 2;
            $_SESSION['error'] = 2;
            session_unset(); //уничтожение переменных сессии
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
    <main class="main container-2">
        <section class="account-info"> <!-- секция для информации о пользователе -->
            <h1>Личный кабинет</h1>
            <p>ФИО: <?php echo htmlspecialchars($client["Full_name"]); ?></p>
            <p>Логин: <?php echo htmlspecialchars($client["Login"]); ?></p>
            <p>Эл. почта: <?php echo htmlspecialchars($client["Mail"]); ?></p>
            <p>Телефон: <?php echo htmlspecialchars($client["Phone_number"]); ?></p>
            <form method="post">
                <button name="logout">Выйти из аккаунта</button> 
            </form>                                   
        </section>

        <section class="standart-block accountp"> <!-- секция с формой записи -->
            <h3>Запишитесь</h3>
            <form class="form-section" method="post">
                <div class="form-account">
                    <div class="admin-in">
                        <p>Мастер</p>
                        <select class="table" name="master" required>
                            <option value="">Выберите мастера</option>
                            <?php while ($row = mysqli_fetch_assoc($masters_result)) : ?>
                                <option value="<?php echo $row['Master_ID']; ?>">
                                    <?php echo htmlspecialchars($row['Full_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="admin-in">
                        <p>Услуга</p>
                        <select class="table" name="service" required>
                            <option value="">Выберите услугу</option>
                            <?php while ($row = mysqli_fetch_assoc($services_result)) : ?>
                                <option value="<?php echo $row['Service_ID']; ?>">
                                    <?php echo htmlspecialchars($row['Name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="admin-in">
                        <p>Дата</p>
                        <input type="date" class="table" name="date" required>
                    </div>
                    <input type="hidden" name="time-e" id="selected-time" required>
                    <div class="admin-in-2">
                        <p>Время</p>
                        <div id="time-buttons"> <!-- временные слоты -->
                            <input type="button" value="13:00" class="add-a" name="time">
                            <input type="button" value="14:00" class="add-a" name="time">
                            <input type="button" value="15:00" class="add-a" name="time">
                            <input type="button" value="16:00" class="add-a" name="time">                            
                        </div>
                    </div>
                </div>
                <input type="submit" value="Записаться" class="finish" name="entry">
            </form>
        </section>
    </main>

    <footer class="footer"> <!-- подвал сайта -->
        <div class="container-2">
            <p>Ferwix 2025</p>
        </div>
    </footer>

    <script>
        const timeButtons = document.querySelectorAll('.add-a');
        const selectedTime = document.getElementById('selected-time');

        timeButtons.forEach(button => {
            button.addEventListener('click', () => {
                
                timeButtons.forEach(btn => btn.classList.remove('active'));

                button.classList.add('active');

                selectedTime.value = button.value;
            });
        });

        let error = <?php echo json_encode($error); ?>; //преобразование переменной для JS

        if (error === 2) { //вывод сообщения при ошибке
            alert('У этого мастера уже есть запись на выбранное время. Пожалуйста выберите другое время или дату');
        }
    </script>
</body>
</html>