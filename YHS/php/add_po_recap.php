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
    // Validasi input
    $requiredFields = ['po_no', 'color', 'style_no', 'code_dest', 'packing', 'size', 'qty', 'portion_value', 'source'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field])) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap: $field"]);
            exit();
        }
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
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Gagal menyiapkan statement: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("ssssssis", $po_no, $color, $style_no, $code_dest, $packing, $size, $qty, $portion_value);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data: " . $stmt->error]);
    }
    $stmt->close();
}

// Endpoint untuk mendapatkan semua PO No
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'get_po_numbers') {
    $source = $conn->real_escape_string($_GET['source']);
    $table_name = "po_recap_" . $source;

    $result = $conn->query("SELECT DISTINCT po_no FROM $table_name");
    if (!$result) {
        echo json_encode(["status" => "error", "message" => "Gagal mengambil data PO No: " . $conn->error]);
        exit();
    }
    $poNumbers = [];
    while ($row = $result->fetch_assoc()) {
        $poNumbers[] = $row['po_no']; // Hanya ambil nilai PO No
    }
    echo json_encode($poNumbers);
    exit();
}

// Endpoint untuk mendapatkan data berdasarkan PO No
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'get_po_data' && isset($_GET['po_no']) && isset($_GET['source'])) {
    $po_no = $conn->real_escape_string($_GET['po_no']);
    $source = $conn->real_escape_string($_GET['source']);
    $table_name = "po_recap_" . $source;

    $stmt = $conn->prepare("SELECT * FROM $table_name WHERE po_no = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Gagal menyiapkan statement: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $po_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $poData = $result->fetch_assoc();
    
    if ($poData) {
        echo json_encode($poData);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan untuk PO No: $po_no"]);
    }
    $stmt->close();
    exit();
}

// Tutup koneksi
$conn->close();
?>