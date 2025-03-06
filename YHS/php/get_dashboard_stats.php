<?php
session_start();
$conn = new mysqli('127.0.0.1', 'root', '', 'youngstar_db', 3307);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Hitung total PO Recap
$totalPOQuery = "SELECT COUNT(*) as total FROM po_recap";
$totalPOResult = $conn->query($totalPOQuery);
$totalPO = $totalPOResult->fetch_assoc()['total'];

// Hitung total Users
$totalUsersQuery = "SELECT COUNT(*) as total FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

// Data PO Recap Per Bulan
$poPerMonthQuery = "SELECT MONTH(created_at) as month, COUNT(*) as count FROM po_recap GROUP BY month";
$poPerMonthResult = $conn->query($poPerMonthQuery);
$months = [];
$poCounts = [];
while ($row = $poPerMonthResult->fetch_assoc()) {
    $months[] = "Bulan " . $row["month"];
    $poCounts[] = $row["count"];
}

// Data PO berdasarkan Kategori
$categoryQuery = "SELECT category, COUNT(*) as count FROM po_recap GROUP BY category";
$categoryResult = $conn->query($categoryQuery);
$categories = [];
$categoryCounts = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row["category"];
    $categoryCounts[] = $row["count"];
}

echo json_encode([
    "totalPO" => $totalPO,
    "totalUsers" => $totalUsers,
    "months" => $months,
    "poCounts" => $poCounts,
    "categories" => $categories,
    "categoryCounts" => $categoryCounts
]);

$conn->close();
?>