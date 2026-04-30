<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;
if ($user_id <= 0) {
  http_response_code(400);
  echo json_encode(["success"=>false,"message"=>"user_id is required"]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT
    id, driver_id, driver_name, driver_phone,
    from_city, to_city, from_lat, from_lng, to_lat, to_lng,
    ride_date, ride_time, total_seats, available_seats,
    price, note, status, created_at
  FROM rides
  WHERE driver_id = ?
  ORDER BY created_at DESC
");
$stmt->execute([$user_id]);

echo json_encode([
  "success" => true,
  "message" => "User rides fetched",
  "data" => $stmt->fetchAll()
]);