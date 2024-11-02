<?php
require 'condb.php';
session_start();

if (isset($_POST["username"], $_POST["email"], $_POST["password"], $_POST["droit"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $droit = $_POST["droit"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $etat = 'active'; 

    // Prepare the SQL statement with placeholders
    $insert_query = $conn->prepare("INSERT INTO users (nom, email, mot_de_passe, droit, etat) VALUES (?, ?, ?, ?, ?)");
    $insert_query->bind_param("sssss", $username, $email, $hashed_password, $droit, $etat);

    
    if ($insert_query->execute()) {
        $error = "User registered successfully!";
    } else {
        $error = "Error: " . $insert_query->error;
    }

    
    $insert_query->close();
} else {
    $error = "Please fill all required fields.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>

       
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="droit">Droit:</label>
                <select name="droit" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>

        <p>Back to login? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
