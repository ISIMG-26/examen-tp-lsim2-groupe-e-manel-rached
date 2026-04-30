<?php
// rides/deleteRide.php
// DELETE operation — satisfies MySQL DELETE requirement
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$ride_id   = isset($input['ride_id'])   ? (int)$input['ride_id']   : 0;
$driver_id = isset($input['driver_id']) ? (int)$input['driver_id'] : 0;

if ($ride_id <= 0 || $driver_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ride_id et driver_id requis"]);
    exit;
}

try {
    // Security: only the owner can delete their ride
    $check = $pdo->prepare("SELECT id, driver_id FROM rides WHERE id = ? LIMIT 1");
    $check->execute([$ride_id]);
    $ride = $check->fetch();

    if (!$ride) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Trajet introuvable"]);
        exit;
    }

    if ((int)$ride['driver_id'] !== $driver_id) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Non autorisé"]);
        exit;
    }

    // DELETE associated bookings first (foreign key safety)
    $delBookings = $pdo->prepare("DELETE FROM bookings WHERE ride_id = ?");
    $delBookings->execute([$ride_id]);

    // DELETE the ride
    $delRide = $pdo->prepare("DELETE FROM rides WHERE id = ? AND driver_id = ?");
    $delRide->execute([$ride_id, $driver_id]);

    echo json_encode([
        "success" => true,
        "message" => "Trajet supprimé avec succès"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
