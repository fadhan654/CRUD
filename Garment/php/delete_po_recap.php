<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    die("Unauthorized access.");
}

$conn = new mysqli('localhost', 'root', '', 'youngstar_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM po_recap WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Data deleted successfully.";
    } else {
        echo "Failed to delete data.";
    }

    $stmt->close();
}

$conn->close();
?>