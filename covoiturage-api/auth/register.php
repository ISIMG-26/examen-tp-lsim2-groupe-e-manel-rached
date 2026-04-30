<?php
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$full_name = trim($input["full_name"] ?? "");
$email     = trim($input["email"] ?? "");
$phone     = trim($input["phone"] ?? "");
$password  = trim($input["password"] ?? "");

if ($full_name === "" || $email === "" || $phone === "" || $password === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email"]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters"]);
    exit;
}

// check duplicate email or phone
$check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ? LIMIT 1");
$check->execute([$email, $phone]);
if ($check->fetch()) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Email or phone already exists"]);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users(full_name, email, phone, password_hash) VALUES(?,?,?,?)");
$stmt->execute([$full_name, $email, $phone, $password_hash]);

echo json_encode([
    "success" => true,
    "message" => "Register successful",
    "data" => [
        "id" => (int)$pdo->lastInsertId(),
        "full_name" => $full_name,
        "email" => $email,
        "phone" => $phone
    ]
]);