<?php
    include "DB.php";

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit("Нет ID.");
    }

    $id = intval($_GET['id']);
    $query = "SELECT Image FROM services WHERE Service_ID = $id"; //получение картинки из БД
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $imageData = $row['Image'];

        $finfo = new finfo(FILEINFO_MIME_TYPE);   // Используется PHP-функция для определения MIME-типа
        $mimeType = $finfo->buffer($imageData);

        header("Content-Type: $mimeType");
        echo $imageData;
    } else {
        http_response_code(404);
        exit("Картинка не найдена.");
    }
?>