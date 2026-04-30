<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$from = isset($_GET["from"]) ? trim($_GET["from"]) : "";
$to   = isset($_GET["to"]) ? trim($_GET["to"]) : "";

$sql = "SELECT
          id, driver_id, driver_name, driver_phone,
          from_city, to_city, from_lat, from_lng, to_lat, to_lng,
          ride_date, ride_time, total_seats, available_seats,
          price, note, status, created_at
        FROM rides
        WHERE status IN ('active','full')";
$params = [];

if ($from !== "") { $sql .= " AND from_city LIKE ?"; $params[] = "%$from%"; }
if ($to !== "")   { $sql .= " AND to_city LIKE ?";   $params[] = "%$to%"; }

$sql .= " ORDER BY ride_date ASC, ride_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rides = $stmt->fetchAll();

echo json_encode([
  "success" => true,
  "message" => "Rides fetched",
  "data" => $rides
]);