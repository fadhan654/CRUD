<?php
session_start();
$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);

$sql = "SELECT * FROM po_recap";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
$conn->close();
?>
