<?php

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../auth/company-login.php");
    exit();
}

$user_id = $_SESSION['company_id'];

/* ==========================================
   COMPANY DETAILS
========================================== */

$user_query = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($user_query);

$company_name = $user['company_name'] ?? 'Company';
$contact_person = $user['full_name'] ?? 'User';

$company_id = $user_id;

/* ==========================================
   DASHBOARD COUNTS
========================================== */

$opportunities_count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM opportunities
         WHERE company_id='$company_id'"
    )
);

$total_opportunities = $opportunities_count['total'] ?? 0;

/* Total Vacancies */

$vacancies_count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(vacancies) AS total
         FROM opportunities
         WHERE company_id='$company_id'
         AND status='Active'"
    )
);

$total_vacancies = $vacancies_count['total'] ?? 0;

/* Total Applicants */

$applicants_count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id=o.id
         WHERE o.company_id='$company_id'"
    )
);

$total_applicants = $applicants_count['total'] ?? 0;

/* Selected */

$selected_count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id=o.id
         WHERE o.company_id='$company_id'
         AND a.status='Selected'"
    )
);

$total_selected = $selected_count['total'] ?? 0;

/* ==========================================
   RECENT OPPORTUNITIES
========================================== */

$recent_opportunities = mysqli_query(
    $conn,
    "SELECT *
     FROM opportunities
     WHERE company_id='$company_id'
     ORDER BY created_at DESC
     LIMIT 3"
);

/* ==========================================
   RECENT APPLICANTS
========================================== */

$recent_applicants = mysqli_query(
    $conn,
    "SELECT
        a.*,
        u.full_name,
        o.opportunity_title
     FROM applications a

     INNER JOIN users u
     ON a.candidate_id=u.id

     INNER JOIN opportunities o
     ON a.opportunity_id=o.id

     WHERE o.company_id='$company_id'

     ORDER BY a.applied_at DESC

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
        <?php echo strtoupper($company_name); ?> Dashboard
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

    <?php include '../includes/company-sidebar.php'; ?>

    <div class="company-content">

        <!-- Dashboard Heading -->

        <div class="dashboard-header">

            <h1 class="dashboard-title">
                <?php echo strtoupper($company_name); ?> Dashboard
            </h1>

            <div class="title-line"></div>

        </div>

        <!-- Welcome Section -->

        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3>
                        🏢 Welcome Back,
                        <?php echo htmlspecialchars($contact_person); ?> !
                    </h3>

                    <p>
                        Manage opportunities, track applicants and grow your hiring process.
                    </p>

                </div>

                <div class="col-md-4">

                    <div class="today-box">

                        <small>
                            📅 Today's Date
                        </small>

                        <h5>
                            <?php echo date("d M Y"); ?>
                        </h5>

                    </div>

                </div>

            </div>

        </div>


        <!-- Statistics Cards -->

        <div class="row g-4 mb-4">

            <!-- Opportunities -->

            <div class="col-lg-3 col-md-6">

                <div class="stats-box">

                    <div class="stats-top">

                        <div class="stats-icon blue">

                            <i class="fa-solid fa-briefcase"></i>

                        </div>

                        <div class="stats-info">

                            <h2><?php echo $total_opportunities; ?></h2>

                            <h6>Opportunities Posted</h6>

                        </div>

                    </div>

                    <p class="stats-desc">

                        Total opportunities you have posted

                    </p>

                </div>

            </div>

            <!-- Vacancies -->

            <div class="col-lg-3 col-md-6">

                <div class="stats-box">

                    <div class="stats-top">

                        <div class="stats-icon orange">

                            <i class="fa-solid fa-users"></i>

                        </div>

                        <div class="stats-info">

                            <h2><?php echo $total_vacancies; ?></h2>

                            <h6>Total Vacancies</h6>

                        </div>

                    </div>

                    <p class="stats-desc">

                        Open positions available

                    </p>

                </div>

            </div>

            <!-- Applicants -->

            <div class="col-lg-3 col-md-6">

                <div class="stats-box">

                    <div class="stats-top">

                        <div class="stats-icon purple">

                            <i class="fa-regular fa-user"></i>

                        </div>

                        <div class="stats-info">

                            <h2><?php echo $total_applicants; ?></h2>

                            <h6>Total Applicants</h6>

                        </div>

                    </div>

                    <p class="stats-desc">

                        Candidates applied so far

                    </p>

                </div>

            </div>

            <!-- Selected -->

            <div class="col-lg-3 col-md-6">

                <div class="stats-box">

                    <div class="stats-top">

                        <div class="stats-icon green">

                            <i class="fa-regular fa-star"></i>

                        </div>

                        <div class="stats-info">

                            <h2><?php echo $total_selected; ?></h2>

                            <h6>Selected</h6>

                        </div>

                    </div>

                    <p class="stats-desc">

                        Candidates you selected

                    </p>

                </div>

            </div>

        </div>

        <!-- Bottom Section -->

        <div class="row g-4">

            <!-- Recent Opportunities -->

            <div class="col-lg-6">

                <div class="section-card">

                    <div class="section-header">

                        <h5>
                            💼 Recent Opportunities
                        </h5>

                        <a href="manage-opportunities.php">
                            View All
                        </a>

                    </div>

                    <?php
                    if (mysqli_num_rows($recent_opportunities) > 0) {
                        while ($row = mysqli_fetch_assoc($recent_opportunities)) {
                    ?>

                            <div class="opportunity-item">

                                <div>

                                    <h6>
                                        <?php echo $row['opportunity_title']; ?>
                                    </h6>

                                    <small>

                                        <?php echo $row['opportunity_type']; ?>

                                        •

                                        <?php echo $row['vacancies']; ?> Vacancies

                                    </small>

                                </div>

                                <?php

                                $statusClass =
                                    ($row['status'] == "Active")
                                    ? "status-active"
                                    : "status-closed";

                                ?>

                                <span class="status-badge <?= $statusClass ?>">

                                    <?= $row['status'] ?>

                                </span>

                            </div>

                        <?php
                        }
                    } else {
                        ?>

                        <p class="text-center mb-0">
                            No Opportunities Found
                        </p>

                    <?php
                    }
                    ?>

                </div>

            </div>

            <!-- Recent Applicants -->

            <div class="col-lg-6">

                <div class="section-card">

                    <div class="section-header">

                        <h5>
                            👥 Recent Applicants
                        </h5>

                        <a href="applicants.php">
                            View All
                        </a>

                    </div>

                    <?php
                    mysqli_data_seek($recent_applicants, 0);

                    if (mysqli_num_rows($recent_applicants) > 0) {
                        while ($row = mysqli_fetch_assoc($recent_applicants)) {

                            $initial =
                                strtoupper(substr($row['full_name'], 0, 1));
                    ?>

                            <div class="applicant-item">

                                <div class="applicant-left">

                                    <div class="avatar">

                                        <?php echo $initial; ?>

                                    </div>

                                    <div>

                                        <h6>
                                            <?php echo $row['full_name']; ?>
                                        </h6>

                                        <small>
                                            Applied for
                                            <?php echo $row['opportunity_title']; ?>
                                        </small>

                                    </div>

                                </div>

                                <?php

                                $status = strtolower(str_replace(' ', '-', $row['status']));

                                ?>

                                <span class="status-badge <?= $status ?>">
                                    <?= $row['status'] ?>
                                </span>

                            </div>

                        <?php
                        }
                    } else {
                        ?>

                        <p class="text-center mb-0">
                            No Applicants Found
                        </p>

                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>

    </div>

</body>

</html>