<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$driverName = trim($input["driverName"] ?? "");
$from       = trim($input["from"] ?? "");
$to         = trim($input["to"] ?? "");
$date       = trim($input["date"] ?? "");
$time       = trim($input["time"] ?? "");
$seats      = (int)($input["seats"] ?? 0);
$price      = (float)($input["price"] ?? 0);
$phone      = trim($input["phone"] ?? "");
$note       = trim($input["note"] ?? "");
$driverId   = (int)($input["driver_id"] ?? 0); // IMPORTANT

if ($driverId <= 0 || $driverName === "" || $from === "" || $to === "" || $date === "" || $time === "" || $phone === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing required fields (driver_id, name, from, to, date, time, phone)"]);
    exit;
}
if ($seats <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Seats must be greater than 0"]);
    exit;
}
if ($price < 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Price must be >= 0"]);
    exit;
}

try {
    $availableSeats = $seats;
    $status = ($availableSeats > 0) ? "active" : "full";

    $stmt = $pdo->prepare("
        INSERT INTO rides (
            driver_id, driver_name, driver_phone,
            from_city, to_city, ride_date, ride_time,
            total_seats, available_seats, price, note, status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $driverId, $driverName, $phone,
        $from, $to, $date, $time,
        $seats, $availableSeats, $price, $note, $status
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Ride posted successfully",
        "data" => ["id" => (int)$pdo->lastInsertId()]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
}