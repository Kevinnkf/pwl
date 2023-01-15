<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="Asset/Css/style.css">
    <title>Registration Page</title>
</head>
<body>
<?php 
    require('db.php');
    session_start();
    
    $error = '';
    $validate = '';
    if(isset($_SESSION['user']) ) header('Location: index.php');
    
    if(isset($_POST['submit']) ){
        $username = stripslashes($_POST['username']);
        $username = mysqli_real_escape_string($conn, $username);
        $name = stripslashes($_POST['name']);
        $name = mysqli_real_escape_string($conn, $name);
        $email = stripslashes($_POST['email']);
        $email = mysqli_real_escape_string($conn, $email);
        $password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $repass = stripslashes($_POST['repassword']);
        $repass = mysqli_real_escape_string($conn, $repass);

        if(!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($repass))){
            if($password == $repass){
                if(cek_nama($name, $conn) == 0){
                    $pass = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO account (username, email, password) VALUES ('" .$username. "', '" .$email. "', '" .$pass. "')";
                    $result = mysqli_query($conn, $query);
                    if($result){
                        $_SESSION['username'] = $username;
                        header('Location: login.php');
                    } else{
                        $error =  'Register user gagal!';
                    }
                } else{
                    $error = 'Username sudah terdaftar!';
                }
            } else{
                $validate = 'Password tidak sama!';
            }
        } else{
            $error = "Data tidak boleh kosong";
        }

    }
    function cek_nama($username, $conn){
        $nama = mysqli_real_escape_string($conn, $username);
        $query = "SELECT * FROM users WHERE username = '$nama'";
        if($result = mysqli_query($conn, $query)) return mysqli_num_rows($result);
    }
    ?>
<section class="container-fluid mb-4" >
    <section class="row justify-content-center">
        <section class="col-12 col-sm-6 col-md-4">
            <form class="form-container" action="register.php" method="POST">
                <h4 class="text-center font-weigth-bold"> SIGN UP </h4>
                <?php if($error != ''){ ?>
                        <div class="alert alert-danger" role="alert"> <?= $error; ?>  </div>
                <?php } ?>

                <div class="form-group">
                    <label for="name"> Nama </label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Masukan Nama anda">
                </div>
                <div class="form-group">
                    <label for="InputEmail"> Email </label>
                    <input type="email" class="form-control" name="email" aria-describedby="emailHelp" id="InputEmail" placeholder="Masukan Email anda">
                </div>  
                <div class="form-group">
                    <label for="username"> Username </label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Masukan Username anda">
                </div>
                <div class="form-group">
                    <label for="name"> Password </label>
                    <input type="password" class="form-control" name="password" id="InputPassword" placeholder="Masukan Password anda">
                    <?php if($validate != ''){?>
                        <p class="text-danger"> <?=$validate;?> </p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="name"> Password </label>
                    <input type="password" class="form-control" name="repassword" id="InputRePassword" placeholder="Re-password">
                    <?php if($validate != ''){?>
                        <p class="text-danger"> <?=$validate;?> </p>
                    <?php } ?>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block"> Register </button>
                <div class="form-footer mt-2"> 
                    <p> Sudah punya account?</p>
                    <a href="login.php"> Login Disini!</a>
                </div>
            </form>
        </section>
    </section>
</section>
</body>
</html>