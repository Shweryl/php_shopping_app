<?php
session_start();
require("config/config.php");
require("config/common.php");
if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phone'])
        || empty($_POST['address'])
    ) {
        if (empty($_POST['name'])) {
            $nameErr = "Name is required";
        }
        if (empty($_POST['email'])) {
            $emailErr = "Email is required";
        }
        if (empty($_POST['password'])) {
            $passwordErr = "Password is required";
        }
        if (empty($_POST['phone'])) {
            $phoneErr = "Phone is required";
        }
        if (empty($_POST['address'])) {
            $addressErr = "Address is required";
        }
    }elseif(!empty($_POST['password']) && strlen($_POST['password']) < 4){
        $passwordErr = "Password should be more than 4 characters. ";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(
            array(
                ":email" => $email,
            )
        );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert('Email is already used')</script>";
        } else {
            $stmt =  $pdo->prepare("INSERT INTO users(name, email, password, role, phone, address) VALUE(:name, :email, :password, :role, :phone, :address)");
            $result = $stmt->execute(
                array(
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':role' => 0
                )
            );
            if($result){
                echo "<script>alert('Successfully registered. You can now log in.');window.location.href='login.php';</script>;";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>VE SHoP</title>

    <!--
		CSS
		============================================= -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!-- Start Header Area -->
    <header class="header_area sticky-header">
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light main_box">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="index.html">
                        <h2>VE SHoP</h2>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto">
                            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        </ul>
                    </div>
            </nav>
        </div>

    </header>
    <!-- End Header Area -->

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Login/Register</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Login/Register</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Login Box Area =================-->
    <section class="login_box_area section_gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login_form_inner w-100">
                        <h3>Register New Account</h3>
                        <form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate">
                            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control <?php echo $nameErr? 'border border-danger' : ''; ?>" id="name" name="name" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="email" class="form-control  <?php echo $emailErr? 'border border-danger' : ''; ?>" id="name" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control <?php echo $phoneErr? 'border border-danger' : ''; ?>" id="name" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control <?php echo $addressErr? 'border border-danger' : ''; ?>" id="name" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Adddress'">
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="password" class="form-control <?php echo $passwordErr? 'border border-danger' : ''; ?>" id="name" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" value="submit" class="primary-btn">Register</button>
                                <a type="button" href="login.php" value="submit" class="primary-btn text-white">Log in</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->

    <!-- start footer Area -->
    <footer class="footer-area section_gap pt-0 pb-5">
        <div class="container">
            <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
                <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved.
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>
            </div>
        </div>
    </footer>
    <!-- End footer Area -->


    <script src="js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/nouislider.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <!--gmaps Js-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
    <script src="js/gmaps.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>