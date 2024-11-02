<?php
require 'condb.php'; // Include your database connection

session_start();
$missions_query = "SELECT id, nom FROM missions";
$missions_result = mysqli_query($conn, $missions_query);

$tasks_query = "SELECT id, nom, priorite, statut, mission_id FROM tasks"; // Adjusted query to include mission_id
$tasks_result = mysqli_query($conn, $tasks_query);

$missions = [];
while ($mission = mysqli_fetch_assoc($missions_result)) {
    $missions[] = $mission; 
}

$tasks = [];
while ($task = mysqli_fetch_assoc($tasks_result)) {
    $tasks[] = $task; 
}

mysqli_close($conn);  // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f9;
            color: #333;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 0;
        }

        .task-manager {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .left-bar, .right-bar {
            background-color: #2c3e50;
            color: white;
            width: 250px;
            padding: 0px;
        }

        .left-bar {
            margin-right: 20px;
        }

        .right-bar {
            margin-left: 20px;
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

        .page-content {
            flex: 1;
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #ecf0f1;
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

        .page-content {
            padding: 20px;
            background-color: #ecf0f1; /* Light background for the content */
            border-radius: 8px; /* Rounded corners for the content area */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
        }
  table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            font-size: 1.1em; /* Slightly smaller font size */
            text-align: left; /* Align text to the left */
        }

        th, td {
            padding: 15px; /* Adjusted padding */
            border: 1px solid #ddd; /* Light gray border */
        }

        th {
            background-color: #3498db; /* Blue header background */
            color: white; /* White text */
            text-transform: uppercase; /* Uppercase for headers */
            font-weight: bold; /* Bold text */
            font-size: 1em; /* Header font size */
        }

        .mission-row {
            background-color: #ecf0f1; /* Light gray for mission rows */
            color: #2c3e50; /* Dark text */
            font-weight: bold; /* Bold text for mission names */
            font-size: 1.1em; /* Larger font size for mission rows */
        }

        .task-row {
            background-color: #ffffff; /* White for task rows */
            color: #333; /* Dark text */
        }

        .mission-row:hover {
            background-color: #bdc3c7; /* Gray on hover for mission rows */
        }

        .task-row:hover {
            background-color: #f5f5f5; /* Slightly darker gray on hover for task rows */
        }

        .task-row td {
            transition: background-color 0.3s ease; /* Smooth transition for background color */
        }

        .task-row td:hover {
            background-color: #d5d5d5; /* Darker gray on hover for task cells */
        }
    </style>
</head>
<body>
    <div class="task-manager">
     
        <div class="left-bar">
            <ul class="action-list">
                <li class="item"><a href="user.php">All Missions</a></li>
                <li class="item"><a href="mission.php">Create Mission</a></li>
                <li class="item"><a href="linkTaskToMission.php">shared tasks and missions</a></li>
                <li class="item"><a href="task.php">Create Tasks</a></li>
            </ul>
        </div>

        <div class="page-content">
            <div class="main-content">
                <h2>Missions and Their Tasks</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Mission Name</th>
                            <th>Task Name</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($missions as $mission) {
                            
                            $taskCount = 0;
                            foreach ($tasks as $task) {
                                if ($task['mission_id'] == $mission['id']) {
                                    $taskCount++;
                                }
                            }

                          
                            if ($taskCount > 0) {
                              
                                echo '<tr class="mission-row">';
                                echo '<td rowspan="' . $taskCount . '">' . $mission['nom'] . '</td>';

                             
                                $firstTask = true; 
                                foreach ($tasks as $task) {
                                    if ($task['mission_id'] == $mission['id']) {
                                        if ($firstTask) {
                                            
                                            echo '<td>' . $task['nom'] . '</td>';
                                            echo '<td>' . $task['priorite'] . '</td>';
                                            echo '<td>' . $task['statut'] . '</td>';
                                            echo '</tr>'; 
                                            $firstTask = false; 
                                        } else {
                                           
                                            echo '<tr class="task-row">';
                                            echo '<td>' . $task['nom'] . '</td>';
                                            echo '<td>' . $task['priorite'] . '</td>';
                                            echo '<td>' . $task['statut'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    

        <div class="right-bar">
    <h3>User Actions</h3>
    <div class="right-content">
        <a href="profile.php" class="item">Profile</a>
        <a href="settings.php" class="item">Settings</a>
        <a href="help.php" class="item">Help</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>
    </div>
    </div>
</body>
</html>
