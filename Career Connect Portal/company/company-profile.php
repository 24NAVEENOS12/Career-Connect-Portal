<?php

include '../config/database.php';

if (!isset($view_mode)) {
    include '../config/company-session.php';
} else {
    include '../config/candidate-session.php';
}



if (!isset($_SESSION['company_id'])) {

    header("Location: ../auth/company-login.php");
    exit();
}

$user_id = $_SESSION['company_id'];

/* ==========================================
   COMPANY DETAILS
========================================== */

$companyQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM companies
     WHERE user_id='$user_id'"
);

$company = mysqli_fetch_assoc($companyQuery);

/* ==========================================
   ACTIVE OPPORTUNITIES
========================================== */

$opportunities = mysqli_query(
    $conn,
    "SELECT *
     FROM opportunities
     WHERE company_id='$user_id'
     ORDER BY created_at DESC
     LIMIT 3"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        Company Profile

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <link rel="stylesheet"
        href="../assets/css/company.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <?php

    if (isset($view_mode) && $view_mode == "candidate") {
        include '../includes/candidate-sidebar.php';
    } else {
        include '../includes/company-sidebar.php';
    }

    ?>

    <div class="company-content">

        <!-- PAGE HEADER -->

        <div class="profile-page-header">

            <div>

                <h1 class="profile-page-title">

                    Company Profile

                </h1>

                <div class="title-line"></div>

            </div>

            <?php if (!isset($view_mode)) { ?>

                <a href="company-profile-edit.php"
                    class="btn-edit">
                    Edit Profile
                </a>

            <?php } ?>

        </div>

        <!-- COMPANY BANNER -->

        <div class="company-banner-card">

            <div class="company-banner"

                style="background-image:url('../uploads/company-covers/<?=
                                                                        !empty($company['cover_image'])
                                                                            ? $company['cover_image']
                                                                            : 'default-cover.jpg';
                                                                        ?>');">

                <div class="banner-overlay">

                    <!-- COMPANY LOGO -->

                    <div class="company-logo-box">

                        <?php if (!empty($company['company_logo'])) { ?>

                            <img
                                src="../uploads/company-logos/<?=
                                                                $company['company_logo']; ?>"
                                class="company-banner-logo">

                        <?php } else { ?>

                            <div class="default-logo">

                                <i class="fa-solid fa-building"></i>

                            </div>

                        <?php } ?>

                    </div>

                    <!-- COMPANY INFO -->

                    <div class="banner-content">

                        <h2>

                            <?= $company['company_name'] ?? 'Company Name'; ?>

                        </h2>

                        <p class="industry-name">

                            <?= $company['industry'] ?? 'Industry'; ?>

                        </p>

                        <div class="banner-details">

                            <span>

                                <i class="fa-solid fa-location-dot text-danger"></i>

                                <?= $company['headquarters'] ?? 'Location'; ?>

                            </span>

                            <span>

                                <i class="fa-solid fa-globe text-success"></i>

                                <?= $company['website'] ?? 'Website'; ?>

                            </span>

                            <span>

                                <i class="fa-solid fa-users text-warning"></i>

                                <?= $company['total_employees'] ?? '0'; ?>

                                Employees

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- ABOUT COMPANY -->

        <div class="profile-card">

            <div class="section-heading">

                <div class="section-icon blue">

                    <i class="fa-solid fa-building"></i>

                </div>

                <h4>

                    About Company

                </h4>

            </div>

            <div class="about-company-text">

                <?=
                !empty($company['about_company'])
                    ? nl2br($company['about_company'])
                    : 'Company description not added yet.';
                ?>

            </div>

        </div>

        <!-- COMPANY HIGHLIGHTS -->

        <div class="profile-card">

            <div class="section-heading">

                <div class="section-icon green">

                    <i class="fa-solid fa-chart-column"></i>

                </div>

                <h4>

                    Company Highlights

                </h4>

            </div>

            <div class="highlights-grid">

                <!-- Founded -->

                <div class="highlight-box founded">

                    <div class="highlight-icon">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <h2>

                        <?= $company['founded_year'] ?? 'N/A'; ?>

                    </h2>

                    <p>

                        Founded

                    </p>

                </div>

                <!-- Offices -->

                <div class="highlight-box offices">

                    <div class="highlight-icon">

                        <i class="fa-solid fa-earth-americas"></i>

                    </div>

                    <h2>

                        <?= !empty($company['office_locations'])
                            ? $company['office_locations']
                            : 'N/A'; ?>

                    </h2>

                    <p>

                        Offices

                    </p>

                </div>

                <!-- Employees -->

                <div class="highlight-box employees">

                    <div class="highlight-icon">

                        <i class="fa-solid fa-users"></i>

                    </div>

                    <h2>

                        <?= $company['total_employees'] ?? '0'; ?>+

                    </h2>

                    <p>

                        Employees

                    </p>

                </div>

                <!-- Projects -->

                <div class="highlight-box projects">

                    <div class="highlight-icon">

                        <i class="fa-solid fa-briefcase"></i>

                    </div>

                    <h2>

                        <?= $company['projects_completed'] ?? '0'; ?>

                    </h2>

                    <p>

                        Projects

                    </p>

                </div>

            </div>

        </div>

        <!-- WHY JOIN US -->

        <div class="profile-card">

            <div class="section-heading">

                <div class="section-icon orange">

                    <i class="fa-solid fa-star"></i>

                </div>

                <h4>

                    Why Join Us

                </h4>

            </div>

            <div class="benefits-grid">

                <?php

                if (!empty($company['why_join_us'])) {

                    $benefits = explode(',', $company['why_join_us']);

                    $icons = [

                        'fa-chart-line',
                        'fa-house',
                        'fa-sack-dollar',
                        'fa-graduation-cap',
                        'fa-heart',
                        'fa-users'

                    ];

                    foreach ($benefits as $index => $benefit) {

                ?>

                        <div class="benefit-card">

                            <div class="benefit-icon">

                                <i class="fa-solid <?= $icons[$index % count($icons)] ?>"></i>

                            </div>

                            <h6>

                                <?= trim($benefit); ?>

                            </h6>

                        </div>

                <?php

                    }
                } else {

                    echo "

                <div class='empty-content'>

                    Benefits not added yet

                </div>

                ";
                }

                ?>

            </div>

        </div>
        <!-- COMPANY CULTURE -->

        <div class="row g-4">

            <!-- Company Culture -->

            <div class="col-lg-6">

                <div class="profile-card h-100">

                    <div class="section-heading">

                        <div class="section-icon purple">

                            <i class="fa-solid fa-people-group"></i>

                        </div>

                        <h4>Company Culture</h4>

                    </div>

                    <p>

                        <?=
                        !empty($company['company_culture'])
                            ? nl2br(htmlspecialchars($company['company_culture']))
                            : 'No company culture added.';
                        ?>

                    </p>

                </div>

            </div>

            <!-- Technologies Used -->

            <div class="col-lg-6">

                <div class="profile-card h-100">

                    <div class="section-heading">

                        <div class="section-icon blue">

                            <i class="fa-solid fa-laptop-code"></i>

                        </div>

                        <h4>Technologies Used</h4>

                    </div>

                    <div class="tech-stack">

                        <?php

                        $techs = explode(
                            ',',
                            $company['technologies_used'] ?? ''
                        );

                        foreach ($techs as $tech) {

                            if (trim($tech) != "") {

                                echo '<span class="tech-badge">'
                                    . htmlspecialchars(trim($tech))
                                    . '</span>';
                            }
                        }

                        ?>

                    </div>

                </div>

            </div>

        </div>



    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>