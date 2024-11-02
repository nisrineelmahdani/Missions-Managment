<?php
require 'condb.php';

session_start();
$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    // Redirect to login page if user ID is not set
    header("Location: login.php"); // Adjust the login page URL as necessary
  
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
if (isset($_POST["mission_name"], $_POST["description"])) {
 $mission_name= $_POST["mission_name"];
 $description=$_POST["description"];
 $user_id = $_SESSION['user_id']; 
$sql = "Insert into missions (nom, description,user_id) Values('$mission_name', '$description','$user_id')";
if (mysqli_query($conn, $sql)) {
    $error= " mission Registration successful!";
} else {
    $error=  "Error: " . $sql . "<br>" . mysqli_error($conn);
  
}
}

else {
    
  $error= "Please fill all required fields.";
}
/* close the db*/
mysqli_close($conn);


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
            font-size: 24px; /* Adjusted font size */
            margin-bottom: 20px; /* Spacing below the header */
            color: #2c3e50; /* Header text color */
            text-align: center; /* Center the header text */
            font-weight: 700; /* Bold text */
            
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
        .top-content {
    margin-top: 0px; /* Similar to .left-content */
    padding: 20px; /* Add padding to match the layout */
    background-color: #2c3e50; /* Match the left bar color */
    color: white; /* Text color for contrast */
    border-radius: 0px; /* Rounded corners */
    font-size: 20px;
    text-align: center;
}

    </style>
</head>
<body>
    
<!-- <div  class="top-content">Welcome, <?php echo htmlspecialchars($user_name); ?>!</div> <!-- Welcome message -->
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
                        <a href="linkTaskToMission.php" class="span"><span>shared tasks and missions</span></a>
                    </li>
                    <li class="item">
                        <a href="task.php" class="span"><span>Create Tasks</span></a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="page-content">
            <div class="form-container">
                <form action="mission.php" method="POST">
                    <div class="header">Create Missions</div>
                    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                    <input type="text" name="mission_name" placeholder="Mission Name" required>
                    <textarea name="description" placeholder="Mission Description" required></textarea>
                    <button type="submit">Create Task</button>
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
