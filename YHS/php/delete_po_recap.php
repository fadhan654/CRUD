<?php
session_start();
header('Content-Type: application/json');

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit();
}

// Koneksi ke database
$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]);
    exit();
}

// Pastikan request menggunakan metode POST dan ID serta source dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['source'])) {
    $id = intval($_POST['id']);
    $source = $conn->real_escape_string($_POST['source']);
    
    // Pastikan source valid
    $valid_sources = ['walmart', 'macys', 'gap', 'target'];
    if (!in_array($source, $valid_sources)) {
        echo json_encode(["status" => "error", "message" => "Vendor tidak valid"]);
        exit();
    }
    $table_name = "po_recap_" . $source;

    // Query DELETE
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus data. ID tidak ditemukan."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Request tidak valid atau data tidak lengkap."]);
}

$conn->close();
?>