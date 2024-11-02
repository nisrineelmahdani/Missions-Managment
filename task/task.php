<?php
require 'condb.php';
session_start();


$missions_query = "SELECT id, nom FROM missions";
$missions_result = mysqli_query($conn, $missions_query);


if (!$missions_result || mysqli_num_rows($missions_result) == 0) {
    $error = "No missions available. Please create a mission before adding tasks.";
}


if (isset($_POST['mission_id'])) {
    $_SESSION['mission_id'] = $_POST['mission_id'];
}

if (!isset($_SESSION['mission_id'])) {
    $error = "No mission selected. Please select a mission before creating a task.";
}


if (isset($_POST["task_name"], $_POST["description"], $_POST["priority"], $_POST["status"])) {
    if (isset($_SESSION['mission_id'])) {
        $task_name =  $_POST["task_name"];
        $description =  $_POST["description"];
        $priority =  $_POST["priority"];
        $status =  $_POST["status"];
        $user_id = $_SESSION['user_id']; 
        $mission_id = $_SESSION['mission_id']; 

       
        $check_mission_sql = "SELECT * FROM missions WHERE id='$mission_id'";
        $result = mysqli_query($conn, $check_mission_sql);
        
        if (mysqli_num_rows($result) > 0) {
            $sql = "INSERT INTO tasks (nom, description, priorite, statut, user_id, mission_id) 
                    VALUES('$task_name', '$description', '$priority', '$status', '$user_id', '$mission_id')";
            
            if (mysqli_query($conn, $sql)) {
                $success_message = "Task registration successful!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        } else {
            $error = "The selected mission does not exist.";
        }
    } else {
        $error = "No mission selected for the task.";
    }
}


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tasks</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <style>
        <style>
      * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            
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
            display: flex; /* Use flexbox */
            padding: 20px;
            background-color: #ecf0f1;
            justify-content: space-between; /* Space between content */
        }

        .form-container {
            flex: 1; /* Allow this to grow */
            display: flex;
            justify-content: center; /* Center the form horizontally */
            align-items: center; /* Center the form vertically */
        }

        .form-container form {
            width: 400px; /* Set a fixed width for the form */
            background: white; /* Optional: add background for the form */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 24px;
            margin-bottom: 20px;
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
        .container {
            text-align: center; /* Center the heading and form */
        }

        form {
            background-color: white; /* Form background */
            padding: 20px; /* Padding inside the form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            width: 300px; /* Fixed width for the form */
            margin: 0 auto; /* Center the form */
        }

        input[type="text"],
        textarea,
        select {
            width: 100%; /* Full width */
            padding: 10px; /* Padding inside the inputs */
            margin: 10px 0; /* Margin above and below inputs */
            border: 1px solid #ccc; /* Border */
            border-radius: 4px; /* Rounded corners */
        }

        button {
            width: 100%; /* Full width */
            padding: 10px; /* Padding inside the button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            border: none; /* Remove border */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
        }

        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
    </style>
    </style>
</head>
<body>
    <div class="task-manager">
        <div class="left-bar">
            <ul class="action-list">
                <li class="item"><a href="user.php">All Missions</a></li>
                <li class="item"><a href="mission.php">Create Mission</a></li>
                <li class="item"><a href="linkTaskToMission.php">shared Tasks and missions</a></li>
                <li class="item"><a href="task.php">Create Tasks</a></li>
            </ul>
        </div>

        <div class="page-content">
            <div class="form-container">
                <form action="task.php" method="POST">
                    <div class="header">Create Tasks</div>
                    
                    <?php if (!empty($error)): ?>
                        <div class="error-message"><?= htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?= htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>

                    <!-- Dropdown for selecting a mission -->
                    <label for="mission">Select Mission:</label>
                    <select name="mission_id" required onchange="this.form.submit();">
                        <option value="" disabled selected>Select Mission</option>
                        <?php
                        if ($missions_result) {
                            while ($mission = mysqli_fetch_assoc($missions_result)) {
                                $selected = (isset($_SESSION['mission_id']) && $_SESSION['mission_id'] == $mission['id']) ? 'selected' : '';
                                echo '<option value="'.htmlspecialchars($mission['id']).'" '. $selected .'>'.htmlspecialchars($mission['nom']).'</option>';
                            }
                        }
                        ?>
                    </select>

                    <!-- If a mission is selected, show the task creation form -->
                    <?php if (isset($_SESSION['mission_id'])): ?>
                        <input type="text" name="task_name" placeholder="Task Name" required>
                        <textarea name="description" placeholder="Task Description" required></textarea>
                        <select name="priority" required>
                            <option value="" disabled selected>Select Priority</option>
                            <option value="basse">Low</option>
                            <option value="moyenne">Medium</option>
                            <option value="haute">High</option>
                        </select>
                        <select name="status" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="en cours">In Progress</option>
                            <option value="terminee">Completed</option>
                            <option value="reportee">Delayed</option>
                            <option value="impossible">Impossible</option>
                        </select>
                        <button type="submit">Create Task</button>
                    <?php endif; ?>
                </form>
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
