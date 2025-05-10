<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
$response = ['success' => false];

if ($id > 0) {
    $conn = conectarDB();
    $stmt = $conn->prepare("DELETE FROM votantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'ID inválido';
}

echo json_encode($response);
?>