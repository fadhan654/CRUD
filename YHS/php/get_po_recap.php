<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Mendapatkan sumber data berdasarkan parameter "source"
$source = isset($_GET['source']) ? $_GET['source'] : null;

switch ($source) {
    case "walmart":
        $sql = "SELECT * FROM po_recap_walmart ORDER BY created_at DESC";
        break;
    case "macys":
        $sql = "SELECT * FROM po_recap_macys ORDER BY created_at DESC";
        break;
    case "gap":
        $sql = "SELECT * FROM po_recap_gap ORDER BY created_at DESC";
        break;
    case "target":
        $sql = "SELECT * FROM po_recap_target ORDER BY created_at DESC";
        break;

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