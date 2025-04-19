<?php 
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // فایل اتصال با MySQLi

$action = $_GET['action'] ?? '';

if ($action == 'list') {
    $result = mysqli_query($conn, "SELECT * FROM systems");
    $systems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $systems[] = $row;
    }
    echo json_encode($systems);
}

elseif ($action == 'add') {
    $data = json_decode(file_get_contents("php://input"), true);

    $name = mysqli_real_escape_string($conn, $data['name']);
    $date = mysqli_real_escape_string($conn, $data['last_service_date']);
    $cost = floatval($data['cost_per_second']);

    $query = "INSERT INTO systems (name, last_service_date, cost_per_second)
              VALUES ('$name', '$date', $cost)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}

elseif ($action == 'delete') {
    $id = intval($_GET['id']);

    $query = "DELETE FROM systems WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}

elseif ($action == 'update') {
    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents("php://input"), true);

    $name = mysqli_real_escape_string($conn, $data['name']);
    $date = mysqli_real_escape_string($conn, $data['last_service_date']);
    $cost = floatval($data['cost_per_second']);

    $query = "UPDATE systems SET name = '$name', last_service_date = '$date', cost_per_second = $cost WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>
