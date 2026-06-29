<?php

include '../config/database.php';

$success = false;
$error = "";

if(isset($_POST['register']))
{
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password)
    {
        $error = "Passwords do not match.";
    }
    else
    {
        $check_email = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if(mysqli_num_rows($check_email) > 0)
        {
            $error = "Email already registered.";
        }
        else
        {
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert = mysqli_query(
                $conn,
                "INSERT INTO users
                (
                    full_name,
                    email,
                    password,
                    role
                )
                VALUES
                (
                    '$full_name',
                    '$email',
                    '$hashed_password',
                    'candidate'
                )"
            );

            if($insert)
            {
                $success = true;
            }
            else
            {
                $error = "Registration failed.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Candidate Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
    overflow:hidden;
}

.top-header{
    height:65px;
    background:rgba(255,255,255,0.92);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
}

.portal-title{
    font-size:17px;
    font-weight:700;
    color:#0f172a;
}

.portal-title i{
    color:#2563eb;
    margin-right:8px;
}

.home-link{
    color:#0f172a;
    font-weight:600;
    text-decoration:none;
}

.main-area{
    height:calc(100vh - 115px);
    display:flex;
    justify-content:center;
    align-items:center;
}

/* REDUCED CARD SIZE */

.register-card{
    width:100%;
    max-width:460px;
    padding:18px 22px !important;
}

/* SMALLER ICON */

.profile-circle{
    width:65px;
    height:65px;
    border-radius:50%;
    background:#2563eb;
    margin:auto;
    display:flex;
    justify-content:center;
    align-items:center;
}

.profile-circle i{
    color:white;
    font-size:26px;
}

/* SMALLER TITLE */

.form-heading{
    font-size:22px;
    font-weight:700;
    text-align:center;
    margin-top:12px;
    margin-bottom:0;
}

.heading-line{
    width:55px;
    height:3px;
    background:#2563eb;
    margin:8px auto 12px;
    border-radius:20px;
}

.description{
    text-align:center;
    font-size:10px;
    margin-bottom:07px;
    line-height:1.4;
}

/* SMALLER LABELS */

label{
    font-size:14px;
    font-weight:500;
}

/* SMALLER INPUTS */

.input-group{
    margin-bottom:07px !important;
}

.input-group-text{
    background:white;
    border-right:none;
    height:44px;
}

.form-control{
    height:44px !important;
    border-left:none;
    font-size:14px;
}

.form-control:focus{
    box-shadow:none;
}

/* SMALLER BUTTON */

.btn-register{
    height:42px;
    font-size:15px;
    font-weight:600;
}

/* LOGIN TEXT */

.login-text{
    margin-top:12px;
    font-size:14px;
}

.footer{
    height:50px;
    background:rgba(255,255,255,0.92);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.footer-title{
    font-size:13px;
    font-weight:600;
    margin:0;
}

.footer-copy{
    font-size:11px;
    margin:0;
}

.success-icon{
    font-size:55px;
    color:#22c55e;
}

.countdown{
    font-size:45px;
    font-weight:700;
    color:#22c55e;
}

@media(max-width:768px){

    body{
        overflow:auto;
    }

    .top-header{
        padding:0 15px;
    }

    .portal-title{
        font-size:15px;
    }

    .home-link{
        font-size:14px;
    }

    .main-area{
        height:auto;
        padding:20px 0;
    }

    .register-card{
        margin:20px;
        max-width:100%;
    }
}

</style>

</head>

<body>

<!-- Header -->

<div class="top-header">

    <div class="portal-title">
        <i class="fa-solid fa-users"></i>
        Career Connect Portal
    </div>

    <a href="../index.php" class="home-link text-decoration-none">
        <i class="fa-solid fa-house"></i>
        Back to Home
    </a>

</div>

<!-- Main Content -->

<div class="main-area">

<?php if($success){ ?>

<div class="glass-card register-card text-center">

    <i class="fa-solid fa-circle-check success-icon mb-3"></i>

    <h3 class="mb-3">
        Registration Successful
    </h3>

    <p>
        Your account has been created successfully.
    </p>

    <div id="countdown" class="countdown">
        5
    </div>

    <p class="mt-2">
        You will be redirected to Login Page...
    </p>

</div>

<script>

let count = 5;

let timer = setInterval(function(){

    count--;

    document.getElementById("countdown").innerHTML = count;

    if(count <= 0)
    {
        clearInterval(timer);

        window.location.href = "candidate-login.php";
    }

},1000);

</script>

<?php } else { ?>

<div class="glass-card register-card">

    <div class="profile-circle">
        <i class="fa-regular fa-user"></i>
    </div>

    <div class="form-heading">
        Candidate Registration
    </div>

    <div class="heading-line"></div>

    <div class="description text-white">
        Create your account to find and apply for the best opportunities.
    </div>

    <?php if($error!=""){ ?>

    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>

    <?php } ?>

    <form method="POST">

        <label class="mb-2">
            Candidate Name
        </label>

        <div class="input-group mb-3">

            <span class="input-group-text">
                <i class="fa-regular fa-user"></i>
            </span>

            <input
                type="text"
                name="full_name"
                class="form-control"
                placeholder="Enter your full name"
                required>

        </div>

        <label class="mb-2">
            Email
        </label>

        <div class="input-group mb-3">

            <span class="input-group-text">
                <i class="fa-regular fa-envelope"></i>
            </span>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Enter your email address"
                required>

        </div>

        <label class="mb-2">
            Password
        </label>

        <div class="input-group mb-3">

            <span class="input-group-text">
                <i class="fa-solid fa-lock"></i>
            </span>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter your password"
                required>

        </div>

        <label class="mb-2">
            Confirm Password
        </label>

        <div class="input-group mb-4">

            <span class="input-group-text">
                <i class="fa-solid fa-lock"></i>
            </span>

            <input
                type="password"
                name="confirm_password"
                class="form-control"
                placeholder="Confirm your password"
                required>

        </div>

        <button
            type="submit"
            name="register"
            class="btn btn-primary-custom btn-register w-100">

            <i class="fa-solid fa-user-plus me-2"></i>
            Register

        </button>

        <div class="text-center login-text">

            Already have an account?

            <a href="candidate-login.php">
                Login
            </a>

        </div>

    </form>

</div>

<?php } ?>

</div>

<!-- Footer -->

<div class="footer">

    <p class="footer-title">
        Career Connect Portal
    </p>

    <p class="footer-copy">
        © 2025 All Rights Reserved.
    </p>

</div>

</body>

</html>