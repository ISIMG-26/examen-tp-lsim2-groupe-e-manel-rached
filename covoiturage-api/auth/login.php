<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$email = trim($input["email"] ?? "");
$password = trim($input["password"] ?? "");

if ($email === "" || $password === "") {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Email and password required"
    ]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, full_name, email, phone, password_hash FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password_hash"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Invalid credentials"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "data" => [
        "id" => (int)$user["id"],
        "full_name" => $user["full_name"],
        "email" => $user["email"],
        "phone" => $user["phone"]
    ]
]);