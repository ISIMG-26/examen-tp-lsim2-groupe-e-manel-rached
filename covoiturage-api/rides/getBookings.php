<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;
if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(["success"=>false, "message"=>"user_id is required"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        r.id, 
        r.driver_id, 
        r.driver_name, 
        r.driver_phone,
        r.from_city, 
        r.to_city, 
        r.ride_date, 
        r.ride_time, 
        r.total_seats, 
        r.available_seats,
        r.price, 
        r.note, 
        r.status AS ride_status,
        r.created_at,

        b.id AS booking_id,
        b.booking_status,
        b.seats_booked,
        b.created_at AS booked_at
    FROM bookings b
    JOIN rides r ON b.ride_id = r.id
    WHERE b.passenger_id = ?
    ORDER BY b.created_at DESC
");

$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

echo json_encode([
    "success" => true,
    "message" => "Bookings fetched",
    "data" => $bookings
]);