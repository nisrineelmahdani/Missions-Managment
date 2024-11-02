<?php
require 'condb.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission to change status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['new_status'])) {
    $user_id = $_POST['user_id'];
    $new_status = $_POST['new_status'];

    // Prepare the SQL query to update the user's status
    $update_query = $conn->prepare("UPDATE users SET etat=? WHERE id=?");
    $update_query->bind_param("si", $new_status, $user_id);

    if ($update_query->execute()) {
        $message = "Status updated successfully!";
    } else {
        $message = "Error updating status: " . $update_query->error;
    }

    $update_query->close();
}

// Fetch users
$users_query = "SELECT id, nom, email, etat FROM users WHERE droit='user'";
$users_result = mysqli_query($conn, $users_query);
$users = [];
while ($user = mysqli_fetch_assoc($users_result)) {
    $users[] = $user;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Status Management</title>
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

        .task-manager {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .left-bar {
            background-color: #2c3e50;
            color: white;
            width: 250px;
            padding: 20px;
        }

        .upper-part {
            display: flex;
            justify-content: space-between;
        }

        .actions {
            display: flex;
        }

        .circle, .circle-2 {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: white;
            margin-right: 10px;
        }

        .left-content {
            margin-top: 20px;
        }

        .action-list {
            list-style-type: none;
        }

        .item {
            margin: 10px 0;
            cursor: pointer;
            padding: 10px;
            transition: background 0.3s;
        }

        .item a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .item:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .page-content {
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
        }

        .header {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .tasks-wrapper {
            display: flex;
            flex-direction: column;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 5px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .right-bar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 20px;
            border-left: 2px solid #2c3e50;
        }

        .right-content {
            margin-top: 20px;
        }

        .right-content .item {
            display: block;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .right-content .item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
            transition: background 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .tag {
            padding: 3px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
            display: inline-block;
            width: 100px;
            text-align: center;
            font-weight: bold;
        }

        .terminee {
            background-color: #2ecc71;
        }

        .en_cours {
            background-color: #3498db;
        }

        .reportee {
            background-color: #f39c12;
        }

        .impossible {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="task-manager">
    <div class="left-bar">
            <div class="upper-part">
                <div class="actions">
                    <div class="circle"></div>
                    <div class="circle-2"></div>
                </div>
            </div>
            <div class="left-content">
                <ul class="action-list">
                <li class="item">
                        <a href="UsersConnected.php" class="span"><span>Users account</span></a>
                    </li>
                    <li class="item">
                        <a href="all_missions.php" class="span"><span>All Missions</span></a>
                    </li>
                   
                    <li class="item">
                        <a href="all_tasks.php" class="span"><span>All Tasks</span></a>
                    </li>
                    
                </ul>
            </div>
        </div>

        <div class="page-content">
            <div class="header">User Status Management: Activate or Desactivate Accounts</div>
            <div class="tasks-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>User Account</th>
                            <th>User Email</th>
                            <th>Account Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['nom']); ?></td>
                                <td><?= htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <!-- Form for changing status -->
                                    <form method="POST" action="admin.php">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <input type="hidden" name="new_status" value="<?= $user['etat'] == 'desactive' ? 'active' : 'desactive'; ?>">
                                        <button type="submit">
                                            <?= $user['etat'] == 'desactive' ? 'Activate' : 'Deactivate'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="right-bar">
        <div class="right-content">
        <a href="profileAdmin.php" class="item">Profile</a>
        <a href="settingsAdmin.php" class="item">Settings</a>
        <a href="help.php" class="item">Help</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
        </div>
    </div>
</body>
</html>
