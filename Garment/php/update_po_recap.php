<?php
session_start();
$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("UPDATE po_recap SET po_no=?, color=?, style_no=?, code_dest=?, packing=?, size=?, qty=?, portion_value=? WHERE id=?");
    $stmt->bind_param("ssssssisi", $_POST['po_no'], $_POST['color'], $_POST['style_no'], $_POST['code_dest'], $_POST['packing'], $_POST['size'], $_POST['qty'], $_POST['portion_value'], $_POST['id']);
    $stmt->execute();
    echo "Data berhasil diperbarui.";
}
$conn->close();
?>
