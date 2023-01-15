<?php
require('db.php');
session_start();
$rand = rand(9999, 1000);
$error = '';

// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];
    // ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT username FROM account WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if ($key === hash('sha512', $row['username'])) {
        $_SESSION['login'] = true;
    }
}


if (isset($_SESSION["login"])) {
    header("location: login.php");
    exit;
}


if (isset($_POST['login'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $captcha = $_POST["captcha"];
    $confirmcaptcha = $_POST["confirmcaptcha"];

    // Vaslidasi captcha
    if ($captcha != $confirmcaptcha) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
        <strong> Captcha Salah </strong> ULANG!.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        // echo "<div class='alert alert-danger' role='alert' style='width: 300px; text-align:center; margin-left:530px; margin-top:15px; position:fixed;'>
        // Invalid Captcha Code!
        // </div>";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM account WHERE username = '$username'");
        $hitung = mysqli_num_rows($result);
        $pwd = mysqli_fetch_array($result);


        // cek username
        if ($hitung > 0) {
            // cek password
            // $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $pwd['password'])) {
                // set Session
                $_SESSION['name'] = $pwd['name'];
                $_SESSION['login'] = true;

                // Remember Me
                if (isset($_POST['remember'])) {
                    // Cookie
                    // setcookie('login', 'true', time() + 60);
                    setcookie('id', $row['id'], time() + 60);
                    setcookie('key', hash('sha512', $row['username']), time() + 60);
                }
                header("location: index.php");
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
        <strong>Incorrect password or username!</strong> Enter Again.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
            <strong>Incorrect password or username!</strong> Enter Again.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
    <title>Login Page</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    
    <link rel = "stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form>
        <h3>Login Admin</h3>

        <label for="username">Username</label>
        <input type="text" placeholder="Email or Phone" id="username">

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password">

        <label for="captcha"> Captcha </label>
        <input type="text" class="captcha" name="captcha" style="pointer-events: none;" value="<?php echo substr(uniqid(), 8);?>"></input>

         <label for="capthca"> Enter Capthca </label>
                        <input type="text" name="confirmcaptcha" id="captcha" required data_parsley_trigger="keyup" value="" required>
                        <input type="hidden" name="captcha-rand" value="<?php echo $rand; ?>">

        <button>Log In</button>
        <div class="form-footer mt-4">
                <a href="register.php">Daftar di sini!</a>
            </div>
    </form>
</body>
</html>
