<?php
// auth/updateProfile.php
// UPDATE operation — satisfies MySQL UPDATE requirement
require_once "../config/cors.php";
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$user_id   = isset($input['user_id'])   ? (int)$input['user_id']     : 0;
$full_name = isset($input['full_name']) ? trim($input['full_name'])   : '';
$phone     = isset($input['phone'])     ? trim($input['phone'])       : '';

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "user_id requis"]);
    exit;
}
if ($full_name === '') {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Le nom est requis"]);
    exit;
}
if ($phone !== '' && !preg_match('/^\d{8}$/', $phone)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Téléphone invalide (8 chiffres)"]);
    exit;
}

try {
    // UPDATE users SET ...
    $stmt = $pdo->prepare("
        UPDATE users 
        SET full_name = ?, phone = ?
        WHERE id = ?
    ");
    $stmt->execute([$full_name, $phone, $user_id]);

    // Return updated profile
    $fetch = $pdo->prepare("SELECT id, full_name, email, phone FROM users WHERE id = ?");
    $fetch->execute([$user_id]);
    $user = $fetch->fetch();

    echo json_encode([
        "success" => true,
        "message" => "Profil mis à jour",
        "data"    => [
            "id"        => (int)$user['id'],
            "full_name" => $user['full_name'],
            "email"     => $user['email'],
            "phone"     => $user['phone']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
