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
    $po_no = $_POST['po_no'];
    $color = $_POST['color'];
    $style_no = $_POST['style_no'];
    $code_dest = $_POST['code_dest'];
    $size = $_POST['size'];
    $qty = $_POST['qty'];
    $portion_value = $_POST['portion_value'];

    $stmt = $conn->prepare("UPDATE po_recap SET po_no = ?, color = ?, style_no = ?, code_dest = ?, size = ?, qty = ?, portion_value = ? WHERE id = ?");
    $stmt->bind_param("sssssisi", $po_no, $color, $style_no, $code_dest, $size, $qty, $portion_value, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Data updated successfully.";
    } else {
        echo "Failed to update data.";
    }

    $stmt->close();
}

$conn->close();
?>