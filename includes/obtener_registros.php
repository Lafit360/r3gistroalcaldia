<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$conn = conectarDB();
$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $search = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM votantes 
                           WHERE nombre_completo LIKE ? OR cedula LIKE ? OR centro_electoral LIKE ?
                           ORDER BY fecha_registro DESC");
    $stmt->bind_param("sss", $search, $search, $search);
} else {
    $stmt = $conn->prepare("SELECT * FROM votantes ORDER BY fecha_registro DESC");
}

$stmt->execute();
$result = $stmt->get_result();
$registros = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

echo json_encode($registros);
?>