<?php
require 'condb.php';
session_start();

// Assuming $id is fetched from session
$id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT nom, email FROM users WHERE id='$id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Initialize variables for feedback
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Update name and email
    $update_query = "UPDATE users SET nom='$nom', email='$email' WHERE id='$id'";
    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }

    // Update password if provided
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_query = "UPDATE users SET password='$hashed_password' WHERE id='$id'";
            if (mysqli_query($conn, $password_query)) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $message = "New password and confirmation do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            padding: 40px;
        }
        .settings-page {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .message {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="settings-page">
        <h1>Account Settings</h1>
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="nom">Name</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password">Current Password</label>
            <input type="password" id="password" name="password" placeholder="Enter current password">

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">

            <button type="submit" class="btn">Update Settings</button>
        </form>
    </div>
</body>
</html>
