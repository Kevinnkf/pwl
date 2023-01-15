<?php
require('db.php');
session_start();

$error = '';
$validate = '';
if (isset($_SESSION['user'])) header('Location: index.php');

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($conn, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $repass = stripslashes($_POST['repassword']);
    $repass = mysqli_real_escape_string($conn, $repass);

    if (! !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($repass))) {
        if ($password == $repass) {
            if (cek_nama($name, $conn) == 0) {
                $pass = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO account (email, username, password) VALUES ('$email', '$username', '$password')";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    $_SESSION['username'] = $username;
                    header('Location: login.php');
                } else {
                    $error =  'Register user gagal!';
                }
            } else {
                $error = 'Username sudah terdaftar!';
            }
        } else {
            $validate = 'Password tidak sama!';
        }
    } else {
        $error = "Data tidak boleh kosong";
    }
}
function cek_nama($username, $conn)
{
    $nama = mysqli_real_escape_string($conn, $username);
    $query = "SELECT * FROM account WHERE username = '$nama'";
    if ($result = mysqli_query($conn, $query)) return mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Register Page</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="formreg">


        <form>
            <h3>Register</h3>
            <?php if ($error != '') { ?>
                <div class="alert alert-danger" role="alert"> <?= $error; ?> </div>
            <?php } ?>

            <label for="Username">Username</label>
            <input type="text" placeholder="Email or Phone" id="username" required>

            <label for="Email">Email</label>
            <input type="text" placeholder="Email or Phone" id="InputEmail" required>

            <label for="password">Password</label>
            <input type="password" placeholder="Password" id="InputPassword" required>
            <?php if ($validate != '') { ?>
                <p class="text-danger"> <?= $validate; ?> </p>
            <?php } ?>

            <label for="repassword">Re-Enter Password</label>
            <input type="password" placeholder="Re-Enter Password" id="InputRePassword" required>
            <?php if ($validate != '') { ?>
                <p class="text-danger"> <?= $validate; ?> </p>
            <?php } ?>

            <button type="submit" name="submit" value="register">Register</button>
            <div class="form-footer mt-4">
                <a href="login.php">Login di sini!</a>
            </div>
        </form>
    </div>
</body>

</html>