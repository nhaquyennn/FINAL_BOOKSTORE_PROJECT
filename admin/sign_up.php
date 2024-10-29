<?php
session_start();
include('../db_connect.php');
include('control.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_login.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Sign Up</title>
</head>
<body style="background-image: url('../img/81310.jpg');">
    <form action="sign_up.php" method="POST" autocomplete="" id="registrationForm">
        <div class="login-box">
            <div class="login-header">
                <header>Sign Up</header>
            </div>
            <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Full Name" autocomplete="off" name="txt_fullname" id="txt_fullname" required>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Phone" autocomplete="off" name="txt_phone" id="txt_phone" required>
                <small class="error-message" id="errorMessage">Số điện thoại không hợp lệ. Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.</small>
            </div>
            <div class="input-box">
                <input type="email" class="input-field" placeholder="E-mail" autocomplete="off" name="txt_email" id="txt_email" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Password" autocomplete="off" name="txt_password" id="txt_password" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Confirm Password" autocomplete="off" name="txt_confirm_password" id="txt_confirm_password" required>
            </div>
            
            <div class="input-submit">
                <button style="background-color: #3B5D50; color:white" class="submit-btn" type="submit" name="signup_btn" id="signup_btn">Sign Up</button>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Thông báo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-success" role="alert">
                                <strong>Thành công!</strong> Bạn đã đăng ký thành công.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="link login-link text-center" style="margin-top: 10px">Already a member? <a href="login_admin.php">Login here</a></div>
        </div>
    </form>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- File JavaScript của bạn -->
    <script src="js/main_3.js"></script>

    <?php if (isset($showModal) && $showModal === true): ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#successModal").modal("show");
        $("#successModal").on("hidden.bs.modal", function () {
            window.location.href = "login_admin.php";
        });
    });
    </script>
    <?php endif; ?>
    
</body>
</html>