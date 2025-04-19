<?php
header('Content-Type: application/json');
include 'db.php'; // MySQLi connection

$action = $_GET['action'] ?? '';

if ($action === 'add') {
    $data = json_decode(file_get_contents("php://input"), true);

    $system_id = intval($data['system_id']);
    $start_time = mysqli_real_escape_string($conn, $data['start_time']);
    $end_time = mysqli_real_escape_string($conn, $data['end_time']);
    $amount = floatval($data['amount']);

    $query = "INSERT INTO payments (system_id, start_time, end_time, amount) VALUES ($system_id, '$start_time', '$end_time', $amount)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}

elseif ($action === 'get') {
    $system_id = isset($_GET['system_id']) ? intval($_GET['system_id']) : 0;

    if ($system_id > 0) {
        $query = "SELECT * FROM payments WHERE system_id = $system_id ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        $payments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $payments[] = $row;
        }

        echo json_encode($payments);
    } else {
        echo json_encode(['status' => 'invalid_system_id']);
    }
}
?>
