<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "user_id is required"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, full_name, email, phone 
    FROM users 
    WHERE id = ? 
    LIMIT 1
");

$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Profile fetched",
    "data" => [
        "id" => (int)$user["id"],
        "full_name" => $user["full_name"],
        "email" => $user["email"],
        "phone" => $user["phone"]
    ]
]);