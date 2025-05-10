<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$response = [
    'success' => false,
    'message' => ''
];

// Validación básica
$required = ['nombre', 'cedula', 'telefono', 'direccion', 'centro'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        $response['message'] = "El campo $field es requerido";
        echo json_encode($response);
        exit;
    }
}

// Verificar cédula única
$conn = conectarDB();
$stmt = $conn->prepare("SELECT id FROM votantes WHERE cedula = ?");
$stmt->bind_param("s", $data['cedula']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $response['message'] = 'La cédula ya está registrada';
    echo json_encode($response);
    exit;
}

// Insertar registro
$stmt = $conn->prepare("INSERT INTO votantes (nombre_completo, cedula, telefono, direccion, centro_electoral) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $data['nombre'], $data['cedula'], $data['telefono'], $data['direccion'], $data['centro']);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Registro guardado exitosamente';
} else {
    $response['message'] = 'Error al guardar: ' . $conn->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>