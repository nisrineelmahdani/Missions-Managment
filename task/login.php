<?php


require 'condb.php';  
session_start();

function generatecsrf(){
    return bin2hex(random_bytes(32));
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generatecsrf();
}



// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];  
         
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = generatecsrf(); }

   
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the account is active
        if ($user['etat'] == 'active') {
            $storedHash = $user['mot_de_passe'];  

           
            if (password_verify($password, $storedHash)) {
              
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["username"] = $user["nom"];  

                // Redirect to api_affiche.php after successful login
             
                header("Location: api_affiche.php");
                exit(); 
            } else {
               
                echo json_encode(["status" => 300, "message" => "Invalid password."]);
                exit();
            }
        } else {
          
            echo json_encode(["status" => 300, "message" => "Account is inactive. Please contact admin."]);
            exit();
        }
    } else {
       
        echo json_encode(["status" => 300, "message" => "No account found with this email."]);
        exit();
    }


    mysqli_close($conn);
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="main.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="api_affiche.php" method="POST"> 
  
       
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
          
           
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        </form>
       
    </div>
</body>
</html>
