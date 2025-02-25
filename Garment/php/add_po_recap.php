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
    $po_no = $_POST['po_no'];
    $color = $_POST['color'];
    $style_no = $_POST['style_no'];
    $code_dest = $_POST['code_dest'];
    $size = $_POST['size'];
    $qty = $_POST['qty'];
    $portion_value = $_POST['portion_value'];

    $stmt = $conn->prepare("INSERT INTO po_recap (po_no, color, style_no, code_dest, size, qty, portion_value) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $po_no, $color, $style_no, $code_dest, $size, $qty, $portion_value);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Data added successfully.";
    } else {
        echo "Failed to add data.";
    }

    $stmt->close();
}

$conn->close();
?>