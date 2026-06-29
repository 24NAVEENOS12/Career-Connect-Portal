<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Career Connect Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="assets/css/style.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="row min-vh-100 align-items-center">

        <div class="col-lg-12 text-center">

            <h1 class="fw-bold display-4 text-dark mb-2">
                Career Connect Portal
            </h1>

            <p class="lead text-secondary mb-5">
                Connecting Talent with Opportunities
            </p>

            <div class="row justify-content-center">

                <!-- Candidate Card -->

                <div class="col-lg-4 mb-4">

                    <div class="glass-card p-5 h-100">

                        <i class="fa-solid fa-user-graduate fa-4x mb-4"></i>

                        <h3 class="mb-3">
                            Candidate Portal
                        </h3>

                        <p class="mb-4">
                            Explore opportunities, build your profile and track applications.
                        </p>

                        <a href="auth/candidate-login.php"
                           class="btn btn-primary-custom">
                            Login
                        </a>

                        <a href="auth/candidate-register.php"
                           class="btn btn-light ms-2">
                            Register
                        </a>

                    </div>

                </div>

                <!-- Company Card -->

                <div class="col-lg-4 mb-4">

                    <div class="glass-card p-5 h-100">

                        <i class="fa-solid fa-building fa-4x mb-4"></i>

                        <h3 class="mb-3">
                            Company Portal
                        </h3>

                        <p class="mb-4">
                            Post opportunities and find the right candidates.
                        </p>

                        <a href="auth/company-login.php"
                           class="btn btn-primary-custom">
                            Login
                        </a>

                        <a href="auth/company-register.php"
                           class="btn btn-light ms-2">
                            Register
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>