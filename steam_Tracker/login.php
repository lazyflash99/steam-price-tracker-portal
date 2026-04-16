<?php
session_start();
$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $enc = md5($password);
    
    try {
        $conn = new PDO("mysql:host=localhost;dbname=PortalDB", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt = $conn->prepare("SELECT * FROM UserData WHERE email = :em AND password = :pass");
        $stmt->execute([':em' => $email, ':pass' => $enc]);

        if($stmt->rowCount() > 0){
             
             $_SESSION["login_success"] = "Login Successful!";
             header("Location: login.php");
             exit();
        } else {
             $error = "Invalid email or password";
        }

    } catch(PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <style>
        .top {
            padding: 1%;
            border: solid;
            background-image: url(https://imgs.search.brave.com/1XV8WVuxoJlHJgnohyM0G9z3vWAtlNlsv7MV_eAfNtk/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy84/LzgwL0lJVEcuanBn);
            background-size: cover;
            background-position: center -600px;
            font-size: x-large;
        }

        .both {
            display: flex;
            height: 100vh;
        }

        .left {
            align-items: center;
            width: 20%;
            background-color: lightblue;

        }

        .right {
            padding: 1.5%;
            width: 80%;
            background-color: lightpink;
        }

        .image {
            display: block;
            margin: auto;
        }
    </style>
    <div class="top" , align="center">
        <b> Mehta Family School of Data Science and Artificial Intelligence
            <br>
            IIT Guwahati
        </b>
        <br>
        Welcome to our Information Portal
    </div>
    <div class="both">
        <div class="left" align="center">
            <br>
            <button onclick="location.href='about.php'" class="btn">About Us</button>
            <br>
            <br>
            <button onclick="location.href='registration.php'" class="btn">Registration</button>
        </div>
        <div class="right">
            <?php
                if(isset($_SESSION["login_success"])){
                    echo "<p style='color:green'><b>".$_SESSION["login_success"]."</b></p>";
                    unset($_SESSION["login_success"]); // remove after showing
                }

                if($error != ""){
                    echo "<p style='color:red'>$error</p>";
                }
            ?>
            <form method="post">
                Email
                <input type="text" name="email" required>
                <br><br>

                Password
                <input type="password" name="password" required>
                <br><br>    
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>