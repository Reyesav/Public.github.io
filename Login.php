<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="index.php">
</head>
<body>
<section class="header">
        <nav>
            <img src="image/Eye.png"></a>
            <div class="logoName">
                <h1>Arise</h1>
            </div>
            </nav>
</section>

    <div class="container">
        <?php
            session_start();
            if(isset($_SESSION["user"])){
                header("Location: index.php");
                exit(); // Ensure script execution stops here if user is already logged in
            }
        
            if (isset($_POST["login"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];
                require_once "database.php";

                $sql = "SELECT * FROM user WHERE email = ?"; 
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);

                if ($user) {
                    if (password_verify($password, $user["PASSWORD"])) { 
                        // Store user's first name in session
                        $_SESSION["user"] = "yes";
                        $_SESSION["firstname"] = $user["FIRSTNAME"];
                        // Redirect to index.php
                        header("Location: index.php");
                        exit(); // Ensure script execution stops here
                    } else {
                        echo "<div class='alert alert-danger'>Invalid email or password</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Invalid email or password</div>";
                }
            }
        ?>

        <form action="Login.php" method="post">
            <div class="form-group">
                <label for="email"></label>
                <p style="color: gold">Email</p>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password"></label>
                <p style="color: gold">Password</p>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div>
            <p style="color: gold">Create New User <a href="Registration.php">Register Here</a ></p>
        </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="hero-btn">
            </div>
        </form>
    </div>
</body>
</html>
