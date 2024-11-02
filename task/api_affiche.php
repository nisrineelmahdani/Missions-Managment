<?php
require 'condb.php';
session_start();

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

// Establish a connection to the database
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    // Prepare and execute the query to find the user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Check if the account is active
        if ($user['etat'] == 'active') {
            // Verify the entered password against the stored hash
            if (password_verify($password, $user['mot_de_passe'])) {
                // Password matches, start the session and store user details
                $_SESSION["user_id"] = $user["id"];  // Store user ID in session
                $_SESSION["email"] = $user["email"];
                $_SESSION["username"] = $user["nom"];  // Store the username as well

                // Redirect to the user info page after successful login
                header("Location: api_affiche.php");
                exit();
            } else {
                // Invalid password
                echo json_encode(["status" => 300, "message" => "Invalid password."]);
                exit();
            }
        } else {
            // Account is inactive
            echo json_encode(["status" => 300, "message" => "Account is inactive. Please contact admin."]);
            exit();
        }
    } else {
        // No account found with this email
        echo json_encode(["status" => 300, "message" => "No account found with this email."]);
        exit();
    }
}

// Fetch user information to display after successful login
if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $userInfo = $stmt->fetch();

    if ($userInfo) {
        // Prepare user data for the frontend
        $data_array = array(
            'email' => $userInfo['email'],
            'username' => $userInfo['nom'],
            // Add more fields as necessary
        );
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "User not logged in.";
    exit();
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #0000FF;
            margin-bottom: 20px;
        }
        .user-info {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
        }
        .user-info p {
            margin: 10px 0;
            font-size: 16px;
        }
        .user-info p span {
            font-weight: bold;
            color: #1e88e5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Dashboard</h1>
        <div class="user-info">
            <?php foreach ($data_array as $key => $val) : ?>
                <p><span><?php echo ucfirst($key); ?>:</span> <?php echo htmlspecialchars($val); ?></p>
            <?php endforeach; ?>
        </div>
        <a href="user.php" class="btn">Go to My Dashboard</a>
    </div>
</body>
</html>
