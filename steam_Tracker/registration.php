<?php
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // SAFE fetching
    $fname = $_POST["first_name"] ?? "";
    $lname = $_POST["second_name"] ?? "";
    $dob = $_POST["dob"] ?? "";
    $email = $_POST["email"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $password = $_POST["password"] ?? "";
    $cpassword = $_POST["cpassword"] ?? "";

    // Validation Logic (Kept exactly as you wrote it)
    if(empty($fname)){
        $error = "First name cannot be empty";
    }
    else if(strpos($email, "@") === false){
        $error = "Invalid email";
    }
    else if($password != $cpassword){
        $error = "Passwords do not match";
    }
    else{
        $birth = new DateTime($dob);
        $ref = new DateTime("2025-12-31"); // Updated to 2025 based on your logic
        $age = $birth->diff($ref)->y;

        if($birth > $ref || $age < 25){ // Added check if DOB is in future
            $error = "Age must be at least 25 years";
        }
    }

    $hasUpper = false;
    $hasLower = false;
    $hasNumber = false;
    $hasSpecial = false;

    for($i = 0; $i < strlen($password); $i++){
        $ch = $password[$i];
        if($ch >= 'A' && $ch <= 'Z') $hasUpper = true;
        else if($ch >= 'a' && $ch <= 'z') $hasLower = true;
        else if($ch >= '0' && $ch <= '9') $hasNumber = true;
        else $hasSpecial = true;
    }

    if(strlen($password) < 8 || !$hasUpper || !$hasLower || !$hasNumber || !$hasSpecial){
        $error = "Password must have uppercase, lowercase, number, special character and minimum 8 characters";
    }

    
    if($error == ""){
        $enc = md5($password);

        try {
            $conn = new PDO("mysql:host=localhost;dbname=PortalDB", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO UserData (firstname, lastname, dob, email, gender, password) 
                    VALUES (:fn, :ln, :dob, :em, :gen, :pass)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':fn' => $fname,
                ':ln' => $lname,
                ':dob' => $dob,
                ':em' => $email,
                ':gen' => $gender,
                ':pass' => $enc
            ]);

            header("Location: registration.php?success=1");
            exit();

        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { 
                $error = "This email is already registered.";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            background-color: lightpink;
            width:80%
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
            <button onclick="location.href='login.php'" class="btn">Login</button>
        </div>
        <div class="right">
            <?php
            if(isset($_GET["success"])){
                echo "<p style='color:green'><b>Registration Successful</b></p>";
                header("Refresh:2; url=registration.php");
            }

            if($error != ""){
                echo "<p style='color:red'>$error</p>";
                header("Refresh:2; url=registration.php");
            }
            ?>
            <form id="form" method="post">
                Enter First Name <input type="text" name="first_name" required>
                <br><br>

                Enter Last Name
                <input type="text" name="second_name" required><br> <br>

                Enter Date of Birth
                <input type="date" name="dob" required>
                <br><br>
                
                Enter Email
                <input type="email" name="email" required>
                <br><br> 
                Select Gender
                <input type="radio" name="gender" value="Male"> Male
                <input type="radio" name="gender" value="Female"> Female
                <input type="radio" name="gender" value="Others"> Others<br><br>
                
                Enter password 
                <input type="password" name="password" required>
                <br><br>
                
                Confirm password
                <input type="password" name="cpassword" required>
                <br><br>
                
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </form>
        </div>
    </div>
</body>

</html>