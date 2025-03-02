<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$source = isset($_GET['source']) ? $_GET['source'] : null;
if ($source == "walmart") {
    $sql = "SELECT * FROM po_recap_walmart ORDER BY created_at DESC";
} elseif ($source == "macys") {
    $sql = "SELECT * FROM `po_recap_macys` ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM po_recap ORDER BY created_at DESC"; // Semua Data
}

$result = $conn->query($sql);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(["data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
}

$conn->close();
?>
