<?php
function getNotebooks($conn) {
    $sql = "SELECT * FROM notebook LIMIT 10";
    $result = mysqli_query($conn, $sql);

    $notebooks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notebooks[] = $row;
    }

    echo json_encode($notebooks, JSON_UNESCAPED_UNICODE);
}

function getNotebookById($conn, $id) {
    $sql = "SELECT * FROM notebook WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(mysqli_fetch_assoc($result), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Запись не найдена"], JSON_UNESCAPED_UNICODE);
    }
}

function createNotebook($conn) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['full_name']) || empty($data['number']) || empty($data['email'])) {
        http_response_code(400);
        echo json_encode(["message" => "В записи отсутствуют обязательные поля"], JSON_UNESCAPED_UNICODE);
        return;
    }

    $name = mysqli_real_escape_string($conn, $data['full_name']);
    $company = mysqli_real_escape_string($conn, $data['company'] ?? '');
    $phone = mysqli_real_escape_string($conn, $data['number']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $birthdate = mysqli_real_escape_string($conn, $data['birthdate'] ?? null);
    $photo = mysqli_real_escape_string($conn, $data['photo'] ?? '');

    $sql = "INSERT INTO notebook (full_name, company, number, email, birthdate, photo) 
            VALUES ('$name', '$company', '$phone', '$email', '$birthdate', '$photo')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "Запись успешно создана"], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Ошибка при создании записи"], JSON_UNESCAPED_UNICODE);
    }
}

function updateNotebook($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = mysqli_real_escape_string($conn, $data['full_name']);
    $company = mysqli_real_escape_string($conn, $data['company'] ?? '');
    $phone = mysqli_real_escape_string($conn, $data['number']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $birthdate = mysqli_real_escape_string($conn, $data['birthdate'] ?? null);
    $photo = mysqli_real_escape_string($conn, $data['photo'] ?? '');

    $sql = "UPDATE notebook SET 
            full_name = '$name', company = '$company', number = '$phone', 
            email = '$email', birthdate = '$birthdate', photo = '$photo' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "Запись обновлена"], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Ошибка при обновлении записи"], JSON_UNESCAPED_UNICODE);
    }
}

function deleteNotebook($conn, $id) {
    $sql = "DELETE FROM notebook WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "Запись удалена"], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Ошибка при удалении записи"], JSON_UNESCAPED_UNICODE);
    }
}
?>