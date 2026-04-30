<?php
// C:\xampp\htdocs\covoiturage-api\rides\getAvailableSeats.php
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/db.php';

$ride_id = isset($_GET['ride_id']) ? (int)$_GET['ride_id'] : 0;

if ($ride_id <= 0) {
    echo json_encode(["success" => false, "message" => "ride_id invalide"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, available_seats, total_seats, status 
    FROM rides 
    WHERE id = ? 
    LIMIT 1
");
$stmt->execute([$ride_id]);
$ride = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ride) {
    echo json_encode(["success" => false, "message" => "Trajet introuvable"]);
    exit;
}

echo json_encode([
    "success"         => true,
    "ride_id"         => (int)$ride['id'],
    "available_seats" => (int)$ride['available_seats'],
    "total_seats"     => (int)$ride['total_seats'],
    "status"          => $ride['status']
]);
