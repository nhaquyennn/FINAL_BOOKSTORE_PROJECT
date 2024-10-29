<?php
session_start();
require "../db_connect.php";
$email = "";
$name = "";
$phone = "";
$errors = array();

//if user signup button
if(isset($_POST['signup_btn']))
{
    $name = mysqli_real_escape_string($connect, $_POST['txt_fullname']);
    $email = mysqli_real_escape_string($connect, $_POST['txt_email']);
    $password = mysqli_real_escape_string($connect, $_POST['txt_password']);
    $cpassword = mysqli_real_escape_string($connect, $_POST['txt_confirm_password']);
    $phone = mysqli_real_escape_string($connect, $_POST['txt_phone']);
    if($password !== $cpassword){
        $errors['txt_password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM admin WHERE email = '$email'";
    $res = mysqli_query($connect, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['txt_email'] = "Email that you have entered is already exist!";
    }
    $phone_check = "SELECT * FROM admin WHERE phone = '$phone'";
    $res1 = mysqli_query($connect, $phone_check);
    if(mysqli_num_rows($res1) > 0){
        $errors['txt_phone'] = "Numberphone that you have entered is already exist!";
    }
    if(count($errors) === 0){
        $encpass = md5($password);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO admin (username, password, email, status, code, phone)
                        VALUES ('$name', '$encpass', '$email', '$status', '$code', '$phone')";
        $data_check = mysqli_query($connect, $insert_data);
        if($data_check){
            // Thiết lập biến để hiển thị modal
            $showModal = true;
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
    
}

//if user click continue button in forgot password form
if(isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($connect, $_POST['txt_email']);
    $check_email = "SELECT * FROM admin WHERE email='$email'";
    $run_sql = mysqli_query($connect, $check_email);
    if(mysqli_num_rows($run_sql) > 0) {
        $code = rand(999999, 111111);
        $insert_code = "UPDATE admin SET code = $code WHERE email = '$email'";
        $run_query = mysqli_query($connect, $insert_code);
        if($run_query) {
            require "D:/wamp/www/phpmailer/PHPMailer-5.2.14/PHPMailerAutoload.php";
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nhaquyenvo2003@gmail.com'; // Tài khoản Gmail của bạn
            $mail->Password = '+++++-----'; // Mật khẩu hoặc Mật khẩu ứng dụng
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('nhaquyenvo2003@gmail.com', 'Nhã Quyên');
            $mail->addAddress($email); // Địa chỉ người nhận

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body    = "Your password reset code is $code";
            $mail->AltBody = "Your password reset code is $code";

            if($mail->send()) {
                $info = "We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code! Error: " . $mail->ErrorInfo;
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}
?>