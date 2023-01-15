<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    require('Config/database.php');
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

        if (!empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($repass))) {
            if ($password == $repass) {
                if (cek_nama($username, $conn) == 0) {
                    $pass = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO account (username, email, password) VALUES ('" . $username . "', '" . $email . "', '" . $pass . "')";
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
    <form action="daftar.php" method="post">
        <h3>Register Admin</h3>

        <?php if ($error != '') { ?>
            <div class="alert alert-danget" role="alert"> <?= $error; ?> </div>
        <?php } ?>

        <label for="email"> Email </label>
        <input type="email" class="form-control" placeholder="Email" id="InputEmail" name="email">

        <label for="username">Username</label>
        <input type="text" class="form-control" placeholder="Username" id="username" name="username">

        <label for="password">Password</label>
        <input type="password" class="form-control" placeholder="Password" id="InputPassword" name="password">
        <?php if ($validate != '') { ?>
            <p class="text-danger"> <?= $validate; ?> </p>
        <?php } ?>

        <label for="password"> RePassword</label>
        <input type="password" class="form-control" placeholder="Password" id="InputRePassword" name="repassword">
        <?php if ($validate != '') { ?>
            <p class="text-danger"> <?= $validate; ?> </p>
        <?php } ?>

        <button type="submit" name="submit" id="submit">Register</button>
        <div class="form-footer mt-4">
            <a href="login.php">Masuk di sini!</a>
        </div>
    </form>
</body>
</html>