<?php

session_start();

include '../config/database.php';

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE email='$email'
         AND role='candidate'"
    );

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {
            $_SESSION['candidate_id'] = $user['id'];
            $_SESSION['candidate_name'] = $user['full_name'];
            $_SESSION['candidate_role'] = $user['role'];

            session_regenerate_id(true);

            header("Location: ../candidate/candidate-dashboard.php");
            exit();
        } else {
            $error = "Invalid Email or Password.";
        }
    } else {
        $error = "Invalid Email or Password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Candidate Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            overflow: hidden;
        }

        .top-header {
            height: 65px;
            background: rgba(255, 255, 255, 0.92);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }

        .portal-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .portal-title i {
            color: #2563eb;
            margin-right: 8px;
        }

        .home-link {
            color: #0f172a;
            font-weight: 600;
            text-decoration: none;
        }

        .main-area {
            height: calc(100vh - 115px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            padding: 18px 22px !important;
        }

        .profile-circle {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #2563eb;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-circle i {
            color: white;
            font-size: 26px;
        }

        .form-heading {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin-top: 12px;
            margin-bottom: 0;
        }

        .heading-line {
            width: 55px;
            height: 3px;
            background: #2563eb;
            margin: 8px auto 12px;
            border-radius: 20px;
        }

        .description {
            text-align: center;
            font-size: 13px;
            margin-bottom: 15px;
            color: white;
        }

        .input-group-text {
            background: white;
            border-right: none;
        }

        .form-control {
            height: 44px !important;
            border-left: none;
            font-size: 14px;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn-login {
            height: 42px;
            font-size: 15px;
            font-weight: 600;
        }

        .footer {
            height: 50px;
            background: rgba(255, 255, 255, 0.92);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .footer-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
        }

        .footer-copy {
            font-size: 11px;
            margin: 0;
        }

        @media(max-width:768px) {

            body {
                overflow: auto;
            }

            .top-header {
                padding: 0 15px;
            }

            .portal-title {
                font-size: 15px;
            }

            .home-link {
                font-size: 14px;
            }

            .main-area {
                height: auto;
                padding: 20px 0;
            }

            .login-card {
                margin: 20px;
            }
        }
    </style>

</head>

<body>

    <!-- HEADER -->

    <div class="top-header">

        <div class="portal-title">

            <i class="fa-solid fa-users"></i>

            Career Connect Portal

        </div>

        <a href="../index.php"
            class="home-link">

            <i class="fa-solid fa-house"></i>

            Back to Home

        </a>

    </div>

    <!-- MAIN -->

    <div class="main-area">

        <div class="glass-card login-card">

            <div class="profile-circle">

                <i class="fa-regular fa-user"></i>

            </div>

            <div class="form-heading text-white">

                Candidate Login

            </div>

            <div class="heading-line"></div>

            <div class="description">

                Login to access opportunities and track applications.

            </div>

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <?php echo $error; ?>

                </div>

            <?php } ?>

            <form method="POST">

                <label class="text-white mb-2">

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

                <label class="text-white mb-2">

                    Password

                </label>

                <div class="input-group mb-4">

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

                <button
                    type="submit"
                    name="login"
                    class="btn btn-primary-custom btn-login w-100">

                    <i class="fa-solid fa-right-to-bracket me-2"></i>

                    Login

                </button>

                <div class="text-center mt-3 text-white">

                    Don't have an account?

                    <a href="candidate-register.php">

                        Register

                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- FOOTER -->

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