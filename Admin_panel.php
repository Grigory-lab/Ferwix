<?php
    include "DB.php"; //включение файла с подключением к БД

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table'])) {

        $prev_table = $_SESSION['last_table'] ?? '';

        $current_table = $_POST['table'];

        if ($prev_table !== $current_table) { //сброс атрибута и значения если выбрана другая таблица
            unset($_POST['attribute']);
            unset($_POST['value']);
            $selected_attribute = '';
        }

        $_SESSION['last_table'] = $current_table; //сохранение значения последней выбранной таблицы
    }

    if (isset($_POST['show_all_tables'])) { //если кнопка вывода таблиц нажата
        echo '<section class="standart-block"><h2>Результат:</h2>';

        $tablesResult = mysqli_query($conn, "SHOW TABLES"); //запрос на вывод таблиц
        if ($tablesResult) {
            while ($tableRow = mysqli_fetch_row($tablesResult)) {
                $tableName = $tableRow[0]; //извлечение имени таблицы
                echo "<h3>Таблица: $tableName</h3>"; //вывод названия таблицы

                $dataResult = mysqli_query($conn, "SELECT * FROM `$tableName`");
                if ($dataResult && mysqli_num_rows($dataResult) > 0) {
                    echo "<table border='1' cellpadding='5' cellspacing='0'>"; //построение таблицы
                    echo "<tr>";

                    $fieldNames = [];
                    while ($fieldInfo = mysqli_fetch_field($dataResult)) { //вывод названий атрибутов
                        if ($fieldInfo->name === "Image") continue; // пропуск поля Image
                        $fieldNames[] = $fieldInfo->name; //сохранение названий
                        echo "<th>" . htmlspecialchars($fieldInfo->name) . "</th>";
                    }
                    echo "</tr>";

                    while ($row = mysqli_fetch_assoc($dataResult)) { //цикл заполнения строк
                        echo "<tr>";
                        foreach ($fieldNames as $fieldName) {
                            echo "<td>" . htmlspecialchars($row[$fieldName]) . "</td>"; //заполнение ячейки
                        }
                        echo "</tr>";
                    }
                    echo "</table><br>";
                } else {
                    echo "<p>Таблица пуста или ошибка.</p>";
                }
            }
        } else {
            echo "<p>Ошибка при получении списка таблиц.</p>";
        }
        echo '</section>';
    }

    $tables_query = "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() 
    AND table_type = 'BASE TABLE'";     // Получение списка таблиц
    $tables_result = mysqli_query($conn, $tables_query);

    $selected_table = $_POST['table'] ?? ''; // Получение выбранной таблицы и атрибута
    $selected_attribute = $_POST['attribute'] ?? '';

    $attributes_result = null;  // Получение списка атрибутов выбранной таблицы
    if (!empty($selected_table)) {
        $attributes_result = mysqli_query($conn, "SHOW COLUMNS FROM `$selected_table`");
    }

    $values_result = null;  // Получение списка значений по выбранному атрибуту
    if (!empty($selected_table) && !empty($selected_attribute)) {
        $values_result = mysqli_query($conn, "SELECT DISTINCT `$selected_attribute` FROM `$selected_table`");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) { //получение значений формы
        $table = $_POST['table'] ?? '';
        $attribute = $_POST['attribute'] ?? '';
        $value = $_POST['value'] ?? '';
        $new_value = $_POST['new_value'] ?? '';
        $action = $_POST['action'];

        if (!empty($table) && !empty($attribute)) {
            if ($action === 'Удалить строку' && !empty($value)) {
                $stmt = $conn->prepare("DELETE FROM `$table` WHERE `$attribute` = ?"); //удаление строки
                $stmt->bind_param("s", $value);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }

            if ($action === 'Изменить значение' && !empty($value) && !empty($new_value)) { 
                $stmt = $conn->prepare("UPDATE `$table` SET `$attribute` = ? WHERE `$attribute` = ?"); //изменение значения
                $stmt->bind_param("ss", $new_value, $value);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }

            if ($action === 'Добавить строку с знач.' && !empty($new_value)) {
                $check_stmt = $conn->prepare("SELECT COUNT(*) FROM `$table` WHERE `$attribute` = ?"); //предотвращение добавления дубликата
                $check_stmt->bind_param("s", $new_value);
                $check_stmt->execute();
                $check_stmt->bind_result($count);
                $check_stmt->fetch();
                $check_stmt->close();

                if ($count == 0) { 
                    $columns_res = mysqli_query($conn, "SHOW COLUMNS FROM `$table`"); //получение списка атрибутов
                    $columns = [];
                    $primaryKey = null;

                    while ($col = mysqli_fetch_assoc($columns_res)) {
                        if ($col['Key'] === 'PRI') {
                            $primaryKey = $col['Field'];
                            continue; // основной ID не включается в массив атрибутов
                        }
                        $columns[] = $col['Field']; //добавление атрибутов в массив
                    }

                    $columns_list = '`' . implode('`, `', $columns) . '`'; //создание строки для запроса
                    $values = array_fill(0, count($columns), null); //массив из null значений, чтобы все остальные атрибуты были null

                    $attr_index = array_search($attribute, $columns); //нахождение выбранного атрибута
                    if ($attr_index !== false) {
                        $values[$attr_index] = $new_value; //ввод значения
                    }

                    $placeholders = rtrim(str_repeat("?, ", count($columns)), ', '); //знаки ? для VALUES в запросе
                    $stmt = $conn->prepare("INSERT INTO `$table` ($columns_list) VALUES ($placeholders)"); //подготовление запроса на добавление

                    $types = str_repeat("s", count($columns)); // определение количества колонок для запроса
                    $stmt->bind_param($types, ...$values); //привязка значений к запросу
                    $stmt->execute();
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit; 
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['view'])) { 
    $view = $_POST['view']; //запись переменной представления

    echo '<section class="standart-block"><h2>Представление ' . htmlspecialchars($view) . ':</h2>';

    $result = mysqli_query($conn, "SELECT * FROM `$view`"); //запрос получения таблицы представления

    if ($result && mysqli_num_rows($result) > 0) { //вывод данных представления
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>";
        while ($field = mysqli_fetch_field($result)) { //определение столбцов
            echo "<th>" . htmlspecialchars($field->name) . "</th>";
        }
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) { //построчный вывод данных
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Нет данных или ошибка в представлении.</p>";
    }
    echo '</section>';
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
        <section class="standart-block adminp">
            <h1>Административная панель</h1>
            <form class="form-section" method="post"> <!-- форма административной панели -->
                <div class="form-admin">
                    <div class="admin-in"> <!-- поля выбора значений -->
                        <p>Таблица</p>
                        <select name="table" onchange="this.form.submit()">
                            <option value=""></option>
                            <?php while ($row = mysqli_fetch_assoc($tables_result)) : ?>
                                <option value="<?= $row['table_name'] ?>" <?= ($row['table_name'] === $selected_table) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['table_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="admin-in">
                        <p>Атрибут</p>
                        <select name="attribute" onchange="this.form.submit()" <?= empty($attributes_result) ? 'disabled' : '' ?>>
                            <option value=""></option>
                            <?php if ($attributes_result): ?>
                                <?php while ($attr = mysqli_fetch_assoc($attributes_result)) : ?>
                                    <option value="<?= $attr['Field'] ?>" <?= ($attr['Field'] === $selected_attribute) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($attr['Field']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="admin-in">
                        <p>Выберите значение (изменение, удаление)</p>
                        <select name="value" <?= empty($values_result) ? 'disabled' : '' ?>>
                            <option value=""></option>
                            <?php if ($values_result): ?>
                                <?php while ($val = mysqli_fetch_row($values_result)) : ?>
                                    <option value="<?= htmlspecialchars($val[0]) ?>"><?= htmlspecialchars($val[0]) ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="admin-in">
                        <p>Введите значение (добавление, изменение)</p>                    
                        <input type="text" class="value-2" name="new_value">                    
                    </div>
                    <div class="admin-act"> <!-- кнопки действий -->
                        <input type="submit" name="action" value="Добавить строку с знач." class="add-act">
                        <input type="submit" name="action" value="Изменить значение" class="change-act">
                        <input type="submit" name="action" value="Удалить строку" class="delete-act">                                                
                    </div>
                    <div class="presses"> <!-- кнопки выполнения представлений -->
                        <input type="submit" name="show_all_tables" value="Вывести все таблицы" class="pres-0">
                        <input type="button" name="view" value="Вывести клиентов" onclick="submitView('V1')">
                        <input type="button" name="view" value="Вывести услуги" onclick="submitView('V2')">
                        <input type="button" name="view" value="Вывести мастеров" onclick="submitView('V3')">
                        <input type="button" name="view" value="Вывести клиентов записанных на 07.12.2025" onclick="submitView('V4')">
                        <input type="button" name="view" value="Вывести косметику" onclick="submitView('V5')">
                        <input type="button" name="view" value="Доступные временные слоты на мелирование 13.12.25" onclick="submitView('V6')">
                        <input type="button" name="view" value="Сотрудники и выполненные услуги 01.11.25-05.11.25" onclick="submitView('V7')">
                        <input type="button" name="view" value="Вывести общую сумму дохода 01.12.25-09.12.25" onclick="submitView('V8')">
                        <input type="button" name="view" value="Вывести самые популярные услуги" onclick="submitView('V9')">
                        <input type="button" name="view" value="Вывести косметику в наличии" onclick="submitView('V10')">
                        <input type="button" name="view" value="Клиенты посетившие сайт более 3 раз" onclick="submitView('V11')">

                        <input type="hidden" name="view" id="viewInput"> <!-- невидимое поле для передачи значения представления к PHP -->
                    </div>                    
                </div>
            </form>
        </section>
    </main>

    <footer class="footer"> <!-- подвал сайта -->
        <div class="container-2">
            <p>Ferwix 2025</p>
        </div>
    </footer>


    <script>
        function submitView(viewName) {
            document.getElementById('viewInput').value = viewName; //присвоение значения представления невидимому полю
            document.forms[0].submit(); // отправка формы
        }
    </script>
    
</body>
</html>