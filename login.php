<?php
include "config.php";
$message ="";
if(isset($_POST['login'])){
    $uname = mysqli_real_escape_string($mysqli,$_POST['email']);
    $password = mysqli_real_escape_string($mysqli,$_POST['password']);
    if ($uname != "" && $password != ""){
        $sql_query = "select name from users where email='".$uname."' and password='".$password."'";
        $result = mysqli_fetch_array(mysqli_query($mysqli,$sql_query))['name'];
        if(isset($result)){
            $_SESSION['uname'] = $result;
            header('Location: seestore.php');
        }else{
            $message.="<div class='alert alert-danger' role='alert'>
                        Invalid Username/Password
                      </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTStore</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
    <header>
    <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <img class="img img-responsive" src="https://dtstore.s3.amazonaws.com/DTS_0.png" style="width:auto;height:200px;"/>
                </div>
            </div>
        </div>
        
    </header>

    <section>
    <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h1>Welcome to DTS Store</h1>
                    </div>
                    <div class="col-md-4">
                        <a href="register.php" class="btn btn-success">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <?php echo $message; ?>
                <form action="" method="POST">
            <div class="form-group">
              <label for="email">Email</label>
              <input class="form-control" type="email" name="email"> </input>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input class="form-control" type="password" name="password"> </input>
            </div>
            <input type="submit" class="btn btn-info btn-block mt-4" name="login" value="Masuk" />
          </form>
                </div>
            </div>
        </div>
    </section>

</body>
</html>