<?php
header('Content-Type: application/json');

// 1. Database Connection
$conn = new mysqli("localhost", "root", "", "reflection_db");

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "error" => $conn->connect_error]);
    exit;
}

// 2. Get JSON Input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "error" => "No data received"]);
    exit;
}

// 3. Prepare and Bind
$stmt = $conn->prepare("INSERT INTO student_reflections (nama, kelas, kebiasaan_baik, perbaikan, perasaan, semangat) VALUES (?, ?, ?, ?, ?, ?)");

// "sssssi" means 5 strings and 1 integer
$stmt->bind_param("sssssi", 
    $data['nama'], 
    $data['kelas'], 
    $data['kebiasaan_baik'], 
    $data['perbaikan'], 
    $data['perasaan'], 
    $data['semangat']
);

// 4. Execute and Respond
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>