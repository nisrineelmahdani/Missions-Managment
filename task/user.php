<?php

require 'condb.php';
session_start();

$id = $_SESSION['user_id'] ?? null;

if (!$id) {

    header("Location: login.php"); 
    exit();
}


$tasks_query = "SELECT id, nom , statut FROM tasks";
$tasks_result = mysqli_query($conn, $tasks_query);


if (!$tasks_result || mysqli_num_rows($tasks_result) == 0) {
    $error = "No missions available. Please create a mission to see it here.";
}




 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5); /* Dynamic background gradient */
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
            padding: 0;
        }

        .item {
            margin: 10px 0;
            cursor: pointer;
            padding: 10px;
            transition: background 0.3s;
        }

        .item a {
            color: white; /* White text for links */
            text-decoration: none; /* Remove underline */
            display: block; /* Make the entire area clickable */
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

        .task {
            display: flex;
            align-items: center;
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }

        .task:hover {
            background: #f1c40f;
        }

        .label-text {
            flex: 1;
            margin-left: 10px;
        }

        .tag {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            color: white;
        }

        .approved {
            background-color: #2ecc71; /* Green for approved tasks */
        }

        .progress {
            background-color: #3498db; /* Blue for in-progress tasks */
        }

        .review {
            background-color: #f39c12; /* Orange for tasks in review */
        }

        .waiting {
            background-color: #e74c3c; /* Red for waiting tasks */
        }

        .right-bar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 20px;
        }

        .right-content {
            margin-top: 20px;
        }

        .members {
            display: flex;
            flex-wrap: wrap;
        }

        .members img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin: 5px;
            border: 2px solid white;
            transition: transform 0.3s;
        }

        .members img:hover {
            transform: scale(1.1);
        }

        .btn {
            background-color: #e74c3c; /* Red background color */
            color: white; /* White text color */
            border: none; /* Remove border */
            padding: 10px 15px; /* Add padding */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Increase font size */
            cursor: pointer; /* Change cursor on hover */
            transition: background 0.3s, transform 0.3s; /* Transition for smooth effect */
            text-decoration: none; /* Remove underline for links */
            display: inline-block; /* Ensure it's block for hover effects */
            width: 100%; /* Full width */
            text-align: center; /* Center text */
        }

        .btn:hover {
            background-color: #c0392b; /* Darker red on hover */
            transform: scale(1.05); /* Slightly enlarge button on hover */
        }
        .right-bar {
    width: 250px;
    background-color: #34495e; /* Consistent background color */
    color: white;
    padding: 20px;
    border-left: 2px solid #2c3e50; /* Optional: for a better separation */
}

.right-content {
    margin-top: 20px;
}

.right-content .item {
    display: block; /* Make links block elements */
    color: white; /* White text */
    padding: 10px; /* Add padding */
    margin: 10px 0; /* Spacing between items */
    border-radius: 4px; /* Rounded corners */
    text-decoration: none; /* Remove underline */
    transition: background 0.3s; /* Smooth background change */
}

.right-content .item:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Background on hover */
}

.btn {
    background-color: #e74c3c; /* Red background color */
    color: white; /* White text color */
    border: none; /* Remove border */
    padding: 10px 15px; /* Add padding */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Increase font size */
    cursor: pointer; /* Change cursor on hover */
    transition: background 0.3s, transform 0.3s; /* Transition for smooth effect */
    text-decoration: none; /* Remove underline for links */
    display: inline-block; /* Ensure it's block for hover effects */
    width: 100%; /* Full width */
    text-align: center; /* Center text */
}

.btn:hover {
    background-color: #c0392b; /* Darker red on hover */
    transform: scale(1.05); /* Slightly enlarge button on hover */
}
.tag {
    padding: 3px; /* Increased padding for the frame */
    border-radius: 5px; /* Rounded corners */
    font-size: 14px; /* Adjusted font size */
    color: white;
    display: inline-block;
    width: 100px; /* Fixed width for the status box */
    text-align: center; /* Center the text */
    font-weight: bold; /* Make the text bold */
}

.terminee {
    background-color: #2ecc71; /* Green for approved */
}

.en_cours {
    background-color: #3498db; /* Blue for in-progress */
}

.reportee {
    background-color: #f39c12; /* Orange for review */
}

.impossible {
    background-color: #e74c3c; /* Red for waiting */
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
                        <a href="user.php" class="span"><span>All Missions</span></a>
                    </li>
                    <li class="item">
                        <a href="mission.php" class="span"><span>Create Mission</span></a>
                    </li>
                    <li class="item">
                        <a href="linkTaskToMission.php" class="span"><span>Shared tasks and missions</span></a>
                    </li>
                    <li class="item">
                        <a href="task.php" class="span"><span>Create Tasks</span></a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="page-content">
            <div class="header">Task Management</div>
            <div class="tasks-wrapper">
    <?php
    if ($tasks_result) {
        while ($task = mysqli_fetch_assoc($tasks_result)) {
            $status_class = '';
            switch ($task['statut']) {
                case 'en cours':
                    $status_class = 'en_cours';
                    break;
                case 'terminee':
                    $status_class = 'terminee';
                    break;
                case 'reportee':
                    $status_class = 'reportee';
                    break;
                case 'impossible':
                    $status_class = 'impossible';
                    break;
                default:
                    $status_class = ''; 
            }
            
            // Ensure proper PHP tag usage
            echo '<div class="task">';
            echo '<input class="task-item" name="task" type="checkbox" id="item-' . htmlspecialchars($task['id']) . '">';
            echo '<label for="item-' . htmlspecialchars($task['id']) . '">';
            echo '<span class="label-text">' . htmlspecialchars($task['nom']) . '</span>';
       
            echo '</label>';
            echo '<span class="tag ' . htmlspecialchars($status_class) . '">';
            echo htmlspecialchars($task['statut']);
            echo '</span>';
            echo '</div>';
        }
    }
    ?>
</div>

        </div>

        <div class="right-bar">
    <h3>User Actions</h3>
    <div class="right-content">
        <a href="profile.php" class="item">Profile</a>
        <a href="settings.php" class="item">Settings</a>
       
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

    </div>
</body>
</html>
