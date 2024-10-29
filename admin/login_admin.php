<?php
session_start();
include('../db_connect.php');
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$username = $_POST['txt_fullname'];
	$pass = md5($_POST['txt_password']);
	$result = $connect->query("select * from admin where username = '$username' and password = '$pass'");
	if($result && $result->num_rows > 0)
	{
		$admin = $result->fetch_assoc();
		$_SESSION['admin'] = $admin['admin_id'];
		header("location: index.php");
		exit();
	}
	else
	{
		$error = 'Invalid username or password';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_login.css">
    <title>Login</title>
</head>
<body style="background-image: url('../img/81310.jpg');">
    <form method="post">
        <div class="login-box">
            <div class="login-header">
                <header>Admin Login</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="User Name" autocomplete="off" name="txt_fullname" id="txt_fullname" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Password" autocomplete="off" name="txt_password" id="txt_password" required>
            </div>
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check">
                    <label for="check">Remember me</label>
                </section>
                <section>
                    <a href="forgot-password.php">Forgot password</a>
                </section>
            </div>
            <div class="input-submit">
                <button style="background-color: #3B5D50;" class="submit-btn" type="submit" name="login_btn" id="login_btn"></button>
                <label for="submit">Sign In</label>
            </div>
            <div class="sign-up-link">
                <p>Don't have account? <a href="sign_up.php">Sign Up</a></p>
            </div>
            <br>
            <div>
                <?php if(isset($error)): ?>
                <p style="text-align: center; color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </form>
</body>
</html>