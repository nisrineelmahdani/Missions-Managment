<?php
header("Content-Type: application/json");

// Connection to the database
$host = "localhost";
$dbname = "missionsmanagment";
$username = "root";
$password = "";
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
/* this is php data object */
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}



$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND mot_de_passe=:password");
$stmt->execute(['email' => $email, 'password' => $password]);
$count = $stmt->rowCount();


if ($count > 0) {
    $json = array("status" => 200, "message" => "Success");
} else {
    $json = array("status" => 300, "message" => "Error");
}

// Add user data to the JSON response
$data = [
    
    "email" => $email,
    "password" => $password,
];

// Output JSON response
echo json_encode($data);

// Close the database connection

$pdo = null;
?>