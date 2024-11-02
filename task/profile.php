<?php
require 'condb.php';
session_start();




// Retrieve the user ID from the session
$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    // Redirect to login page if user ID is not set
    header("Location: login.php"); // Adjust the login page URL as necessary
    exit();
}

// Query to get the list of missions
$tasks_query = "SELECT id, nom , statut FROM tasks";
$tasks_result = mysqli_query($conn, $tasks_query);

// Check if user_id is set in the session



// Query to get the user details
$user_query = "SELECT id, nom, email, created_at FROM users WHERE id='$id'";
$users_result = mysqli_query($conn, $user_query);

// Fetch the user data if available
$user = mysqli_fetch_assoc($users_result);

// Query to get the count of missions created by the user
$missions_query = "SELECT COUNT(*) AS mission_count FROM missions WHERE user_id='$id'";
$missions_result = mysqli_query($conn, $missions_query);
$missions_data = mysqli_fetch_assoc($missions_result);
$missions_count = $missions_data['mission_count'];

// Query to get the count of tasks completed by the user
$tasks_query = "SELECT COUNT(*) AS task_count FROM tasks WHERE user_id='$id' AND statut='terminee'";
$tasks_result = mysqli_query($conn, $tasks_query);
$tasks_data = mysqli_fetch_assoc($tasks_result);
$tasks_count = $tasks_data['task_count'];

// Query to get the count of ongoing tasks by the user
$ongoing_tasks_query = "SELECT COUNT(*) AS ongoing_count FROM tasks WHERE user_id='$id' AND statut='en cours'";
$ongoing_tasks_result = mysqli_query($conn, $ongoing_tasks_query);
$ongoing_tasks_data = mysqli_fetch_assoc($ongoing_tasks_result);
$ongoing_tasks_count = $ongoing_tasks_data['ongoing_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
        }

        .profile-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            background-color: #ffffff;
            width: 100%;
            height: 100vh;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 70%;
            border: 5px solid #3498db;
            margin-bottom: 20px;
        }

        .profile-info h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .profile-info p {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 5px;
        }

        .profile-stats {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            width: 100%;
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }

        .stat-box h3 {
            font-size: 24px;
            color: #3498db;
        }

        .stat-box p {
            font-size: 14px;
            color: #7f8c8d;
        }

        .profile-actions {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        /* Responsiveness */
        @media screen and (max-width: 768px) {
            .profile-stats {
                flex-direction: column;
            }

            .stat-box {
                margin-bottom: 20px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="profile-page">
        <div class="profile-header">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
            <?php else: ?>
                <img src="images/default-profile-picture-avatar-user-icon-vector-46389216.jpg" alt="Default Profile Picture" class="profile-picture">
            <?php endif; ?>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['nom']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <p>Member since: <?php echo date('F d, Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-box">
                <h3><?php echo $missions_count; ?></h3>
                <p>Missions Created</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $tasks_count; ?></h3>
                <p>Tasks Completed</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $ongoing_tasks_count; ?></h3>
                <p>Ongoing Tasks</p>
            </div>
        </div>

        <div class="profile-actions">
            <a href="user.php" class="btn">dashboard Profile</a>
            <a href="settings.php" class="btn">Settings</a>
        </div>
    </div>
</body>
</html>
