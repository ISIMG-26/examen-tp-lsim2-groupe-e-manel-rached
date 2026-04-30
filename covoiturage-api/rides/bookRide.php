<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$ride_id      = isset($input['ride_id']) ? (int)$input['ride_id'] : 0;
$passenger_id = isset($input['passenger_id']) ? (int)$input['passenger_id'] : 0;
$seats        = isset($input['seats']) ? (int)$input['seats'] : 1;

if ($ride_id <= 0 || $passenger_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ride_id et passenger_id requis"]);
    exit;
}

try {

    //  START TRANSACTION
    $pdo->beginTransaction();

    // Vérifier les places disponibles
    $check = $pdo->prepare("SELECT available_seats FROM rides WHERE id = ? FOR UPDATE");
    $check->execute([$ride_id]);
    $ride = $check->fetch();

    if (!$ride) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => "Trajet non trouvé"]);
        exit;
    }

    // seats 
    if ($ride['available_seats'] < $seats) {
        $pdo->rollBack();
        echo json_encode([
            "success" => false,
            "message" => "Pas assez de places disponibles"
        ]);
        exit;
    }

    
    $checkBooking = $pdo->prepare("
        SELECT seats_booked 
        FROM bookings 
        WHERE ride_id = ? AND passenger_id = ?
    ");
    $checkBooking->execute([$ride_id, $passenger_id]);
    $existing = $checkBooking->fetch();

    if ($existing) {

        
        $newSeatsBooked = $existing['seats_booked'] + $seats;

        $updateBooking = $pdo->prepare("
            UPDATE bookings 
            SET seats_booked = ? 
            WHERE ride_id = ? AND passenger_id = ?
        ");
        $updateBooking->execute([$newSeatsBooked, $ride_id, $passenger_id]);

    } else {

        // ➕ 
        $stmt = $pdo->prepare("
            INSERT INTO bookings (ride_id, passenger_id, seats_booked, booking_status) 
            VALUES (?, ?, ?, 'confirmed')
        ");
        $stmt->execute([$ride_id, $passenger_id, $seats]);
    }

    
    $update = $pdo->prepare("
        UPDATE rides 
        SET available_seats = available_seats - ? 
        WHERE id = ?
    ");
    $update->execute([$seats, $ride_id]);

    
    $pdo->commit();

    
    $getNew = $pdo->prepare("SELECT available_seats FROM rides WHERE id = ?");
    $getNew->execute([$ride_id]);
    $newSeats = (int)$getNew->fetchColumn();

    
    echo json_encode([
        "success" => true,
        "message" => "Réservation effectuée avec succès",
        "available_seats" => $newSeats
    ]);

} catch (Exception $e) {

    
    $pdo->rollBack();

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}