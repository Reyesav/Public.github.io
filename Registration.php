<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="style2.css">
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

    if(isset($_POST["submit"])){
      $LastName = $_POST["LastName"];
      $FirstName = $_POST["FirstName"];
      $email = $_POST["Email"];
      $password = $_POST["password"];
      $RepeatPassword = $_POST["repeat_password"];
 
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $errors = array();
      if (empty($LastName) OR empty($FirstName) OR empty($email) OR empty($password) OR empty($RepeatPassword)) {
        array_push($errors, "All fields are required");
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Email is not valid");
      }
      if(strlen($password)<8) {
        array_push($errors, "Password must be at least 8 characters long");
      }
      if($password!= $RepeatPassword){
        array_push($errors, "Password does not match");
      }

      require_once "database.php";
      $sql = "SELECT * FROM user WHERE email = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "s", $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $rowCount = mysqli_num_rows($result);
      if ($rowCount>0) {
        array_push($errors, "Email Already Exist!");
      }
 
      if (count($errors)>0){
        foreach($errors as $error) {
          echo"<div class='alert alert-danger'>$error</div>";
        }
      } else {
        require_once "database.php";
        $sql = "INSERT INTO user(Last_Name, First_Name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
          mysqli_stmt_bind_param($stmt, "ssss", $LastName, $FirstName, $email, $passwordHash);
          mysqli_stmt_execute($stmt);
          echo "<div class='alert alert-success'> You are Registered Successfully! </div>";
          // Store user's first name in session
          $_SESSION["user"] = "yes";
          $_SESSION["firstname"] = $FirstName;
          // Redirect to index.php
          header("Location: index.php");
          exit(); // Ensure script execution stops here
        } else {
          die("Something went wrong");
        }
      }
    }
    ?>

<h2>Create New Account</h2>
<form action="registration.php" method="post">
            <div class="form-group">
                <label for="LastName"></label>
                <p style="color: gold">Last Name:</p>
                <input type="text" class="form-control" name="LastName">
            </div>
            <div class="form-group">
                <label for="FirstName"></label>
                <p style="color: gold">First Name:</p>
                <input type="text" class="form-control" name="FirstName">
            </div>
            <div class="form-group">
                <label for="Email"></label>
                <p style="color: gold">Email:</p>
                <input type="email" class="form-control" name="Email">
            </div>
            <div class="form-group">
                <label for="Password"></label>
                <p style="color: gold">Password:</p>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <label for="Repeat_Password"></label>
                <p style="color: gold">Repeat Password:</p>
                <input type="password" class="form-control" name="repeat_password">
            </div>
            <div class="form-group">
            <label for="ContactNumber"></label>
                 <p style="color: gold">Contact Number:</p>
                <input type="tel" class="form-control" name="ContactNumber" id="ContactNumber">
      </div>
      <div>
    <p style="color: gold">Already registered? <a href="login.php"> Login Here</a></p>
    </div>
     
      <div class="form-btn">
      <a href="contact.php"></a><input type="submit" class="hero-btn" value="Submit" name="submit">
      </div>

  <script>
    document.getElementById("registrationForm").addEventListener("submit", function(event) {
      var lastName = document.getElementById("LastName").value.trim();
      var firstName = document.getElementById("FirstName").value.trim();
      var email = document.getElementById("Email").value.trim();
      var password = document.getElementById("password").value;
      var repeatPassword = document.getElementById("repeat_password").value;
      var contactNumber = document.getElementById("ContactNumber").value.trim();

      if (lastName === "" || firstName === "" || email === "" || password === "" || repeatPassword === "" || contactNumber === "" || country === "") {
        alert("All fields are required");
        event.preventDefault();
        return;
      }

      if (!validateEmail(email)) {
        alert("Email is not valid");
        event.preventDefault();
        return;
      }

      if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        event.preventDefault();
        return;
      }

      if (password !== repeatPassword) {
        alert("Passwords do not match");
        event.preventDefault();
        return;
      }

      if (!/^\d{1,10}$/.test(contactNumber)) {
      alert("Contact number must have maximum 10 digits");
      event.preventDefault();
      return;
    }

    });

    function validateEmail(email) {
      var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
  </script>
</body>
</html>
