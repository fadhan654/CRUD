<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['id'], $_POST['po_no'], $_POST['color'], $_POST['style_no'], $_POST['code_dest'], 
              $_POST['packing'], $_POST['size'], $_POST['qty'], $_POST['portion_value'], $_POST['source']) &&
        is_numeric($_POST['id']) && is_numeric($_POST['qty'])
    ) {
        $id = intval($_POST['id']);
        $po_no = trim($_POST['po_no']);
        $color = trim($_POST['color']);
        $style_no = trim($_POST['style_no']);
        $code_dest = trim($_POST['code_dest']);
        $packing = trim($_POST['packing']);
        $size = trim($_POST['size']);
        $qty = intval($_POST['qty']);
        $portion_value = trim($_POST['portion_value']);
        $source = $conn->real_escape_string($_POST['source']);

        $valid_sources = ['walmart', 'macys', 'gap', 'target'];
        if (!in_array($source, $valid_sources)) {
            echo json_encode(["status" => "error", "message" => "Vendor tidak valid"]);
            exit();
        }
        $table_name = "po_recap_" . $source;

        $stmt = $conn->prepare("UPDATE $table_name 
                                SET po_no=?, color=?, style_no=?, code_dest=?, packing=?, size=?, qty=?, portion_value=? 
                                WHERE id=?");
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Gagal menyiapkan statement: " . $conn->error]);
            exit();
        }

        $stmt->bind_param("ssssssisi", 
            $po_no, $color, $style_no, $code_dest, $packing, $size, $qty, $portion_value, $id
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui data: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap atau format salah."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode request tidak valid."]);
}

$conn->close();
?>