<?php
session_start();
header('Content-Type: application/json');

// Koneksi ke database
$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);

// Cek koneksi database
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit();
}

// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['po_no'], $_POST['color'], $_POST['style_no'], $_POST['code_dest'], $_POST['packing'], $_POST['size'], $_POST['qty'], $_POST['portion_value'], $_POST['source'])) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        exit();
    }

    // Sanitasi input
    $po_no = $conn->real_escape_string($_POST['po_no']);
    $color = $conn->real_escape_string($_POST['color']);
    $style_no = $conn->real_escape_string($_POST['style_no']);
    $code_dest = $conn->real_escape_string($_POST['code_dest']);
    $packing = $conn->real_escape_string($_POST['packing']);
    $size = $conn->real_escape_string($_POST['size']);
    $qty = intval($_POST['qty']);
    $portion_value = $conn->real_escape_string($_POST['portion_value']);
    $source = $conn->real_escape_string($_POST['source']);

    // Tentukan tabel
    $valid_sources = ['walmart', 'macys', 'gap', 'target'];
    if (!in_array($source, $valid_sources)) {
        echo json_encode(["status" => "error", "message" => "Vendor tidak valid"]);
        exit();
    }
    $table_name = "po_recap_" . $source;

    // Siapkan query SQL
    $stmt = $conn->prepare("INSERT INTO $table_name (po_no, color, style_no, code_dest, packing, size, qty, portion_value, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssis", $po_no, $color, $style_no, $code_dest, $packing, $size, $qty, $portion_value);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data: " . $stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>