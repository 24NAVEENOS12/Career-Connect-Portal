<?php

include '../config/database.php';
include '../config/candidate-session.php';

$candidate_id = $_SESSION['candidate_id'];

/* ==========================================
   CANDIDATE DETAILS
========================================== */

$user_query = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     WHERE id='$candidate_id'"
);

$user = mysqli_fetch_assoc($user_query);

$candidate_name = $user['full_name'] ?? 'Candidate';

/* ==========================================
   PROFILE DETAILS
========================================== */

$profile_query = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_profiles
     WHERE user_id='$candidate_id'"
);

$profile = mysqli_fetch_assoc($profile_query);

/* ==========================================
   PROFILE COMPLETION
========================================== */

$total_fields = 10;
$completed_fields = 0;

if (!empty($profile['phone'])) $completed_fields++;
if (!empty($profile['dob'])) $completed_fields++;
if (!empty($profile['gender'])) $completed_fields++;
if (!empty($profile['location'])) $completed_fields++;
if (!empty($profile['about_me'])) $completed_fields++;
if (!empty($profile['profile_photo'])) $completed_fields++;
if (!empty($profile['resume'])) $completed_fields++;
if (!empty($profile['linkedin_url'])) $completed_fields++;
if (!empty($profile['github_url'])) $completed_fields++;
if (!empty($profile['portfolio_url'])) $completed_fields++;

$profile_percentage =
    round(($completed_fields / $total_fields) * 100);

/* ==========================================
   STATISTICS
========================================== */

$total_applied = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE candidate_id='$candidate_id'"
    )
)['total'];

$total_selected = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE candidate_id='$candidate_id'
         AND status='Selected'"
    )
)['total'];

$total_shortlisted = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE candidate_id='$candidate_id'
         AND status='Shortlisted'"
    )
)['total'];

$total_review = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE candidate_id='$candidate_id'
         AND status='Under Review'"
    )
)['total'];

$total_rejected = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE candidate_id='$candidate_id'
         AND status='Rejected'"
    )
)['total'];

/* ==========================================
   RECENT APPLICATIONS
========================================== */

$recent_applications = mysqli_query(
    $conn,
    "SELECT
        a.*,
        o.opportunity_title,
        u.company_name

     FROM applications a

     INNER JOIN opportunities o
     ON a.opportunity_id = o.id

     INNER JOIN users u
     ON o.company_id = u.id

     WHERE a.candidate_id='$candidate_id'

     ORDER BY a.applied_at DESC

     LIMIT 3"
);

/* ==========================================
   RECOMMENDED OPPORTUNITIES
========================================== */

$recommended = mysqli_query(
    $conn,
    "SELECT
        o.*,
        u.company_name

     FROM opportunities o

     INNER JOIN users u
     ON o.company_id = u.id

     WHERE o.status='Active'

     ORDER BY o.created_at DESC

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
        Candidate Dashboard
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <link rel="stylesheet"
        href="../assets/css/company.css">

    <link rel="stylesheet"
        href="../assets/css/candidate.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <?php include '../includes/candidate-sidebar.php'; ?>

    <div class="company-content">

        <div class="dashboard-header">

            <h1 class="dashboard-title">

                Candidate Dashboard

            </h1>

            <div class="title-line"></div>

        </div>

        <!-- Welcome Card -->

        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3>

                        Welcome Back,
                        <?php echo htmlspecialchars($candidate_name); ?> 👋

                    </h3>

                    <p>

                        Track your applications and opportunities.

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

        <div class="row g-4">

            <div class="col-lg col-md-6">

                <div class="stats-box d-flex justify-content-between align-items-center">

                    <div class="stats-icon applied-icon">

                        <i class="fa-solid fa-file-lines"></i>

                    </div>

                    <div class="stats-content">

                        <h2><?php echo $total_applied; ?></h2>

                        <h6>Applied Opportunities</h6>

                    </div>

                </div>

            </div>

            <div class="col-lg col-md-6">

                <div class="stats-box d-flex justify-content-between align-items-center">

                    <div class="stats-icon selected-icon">

                        <i class="fa-solid fa-trophy"></i>

                    </div>

                    <div class="stats-content">

                        <h2><?php echo $total_selected; ?></h2>

                        <h6>Selected</h6>

                    </div>

                </div>

            </div>

            <div class="col-lg col-md-6">

                <div class="stats-box d-flex justify-content-between align-items-center">

                    <div class="stats-icon shortlisted-icon">

                        <i class="fa-solid fa-star"></i>

                    </div>

                    <div class="stats-content">

                        <h2><?php echo $total_shortlisted; ?></h2>

                        <h6>Shortlisted</h6>

                    </div>

                </div>

            </div>

            <div class="col-lg col-md-6">

                <div class="stats-box d-flex justify-content-between align-items-center">

                    <div class="stats-icon review-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div class="stats-content">

                        <h2><?php echo $total_review; ?></h2>

                        <h6>Under Review</h6>

                    </div>

                </div>

            </div>

            <div class="col-lg col-md-6">

                <div class="stats-box d-flex justify-content-between align-items-center">

                    <div class="stats-icon rejected-icon">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div class="stats-content">

                        <h2><?php echo $total_rejected; ?></h2>

                        <h6>Rejected</h6>

                    </div>

                </div>

            </div>

        </div>

        <!-- Profile + Recent Applications -->

        <div class="dashboard-row">

            <div class="profile-card">

                <h5>

                    <i class="fa-solid fa-user me-2"></i>

                    Profile Completion

                </h5>

                <div class="profile-content">

                    <!-- Left Side -->

                    <div class="progress-circle">

                        <svg width="180" height="180">

                            <circle class="bg"
                                cx="90"
                                cy="90"
                                r="70"></circle>

                            <circle class="progress"
                                cx="90"
                                cy="90"
                                r="70"
                                style="--progress:<?php echo $profile_percentage; ?>">
                            </circle>

                        </svg>

                        <div class="progress-text">

                            <?php echo $profile_percentage; ?>%

                        </div>

                    </div>

                    <!-- Right Side -->

                    <div class="profile-info">

                        <p>

                            Complete your profile to improve your visibility and increase your chances of getting selected.

                        </p>

                        <a href="candidate-profile-edit.php" class="complete-btn">

                            Complete Profile

                        </a>

                    </div>

                </div>

            </div>

            <div class="recent-card">

                <h5>

                    <i class="fa-solid fa-clock-rotate-left me-2"></i>

                    Recent Applications

                </h5>

                <?php

                if (mysqli_num_rows($recent_applications) > 0) {

                    while ($app = mysqli_fetch_assoc($recent_applications)) {

                        $status_class = "status-applied";

                        if ($app['status'] == 'Under Review') {
                            $status_class = "status-review";
                        }

                        if ($app['status'] == 'Shortlisted') {
                            $status_class = "status-shortlisted";
                        }

                        if ($app['status'] == 'Selected') {
                            $status_class = "status-selected";
                        }

                        if ($app['status'] == 'Rejected') {
                            $status_class = "status-rejected";
                        }

                ?>

                        <div class="application-item">

                            <div class="application-top">

                                <div class="application-title">

                                    <?php echo htmlspecialchars($app['opportunity_title']); ?>

                                </div>

                                <span class="application-status <?php echo $status_class; ?>">

                                    <?php echo $app['status']; ?>

                                </span>

                            </div>

                            <div class="application-company">

                                <?php echo strtoupper($app['company_name']); ?>

                            </div>

                            <div class="d-flex justify-content-between mt-1">

                                <span class="application-date">

                                    Applied on
                                    <?php echo date("d M Y", strtotime($app['applied_at'])); ?>

                                </span>

                                <span class="application-time">

                                    <i class="fa-regular fa-clock"></i>

                                    <?php echo floor((time() - strtotime($app['applied_at'])) / 86400); ?>
                                    days ago

                                </span>

                            </div>

                        </div>

                <?php

                    }
                } else {

                    echo "<p>No applications found.</p>";
                }

                ?>

            </div>

        </div>

        <!-- Recommended Opportunities -->

        <div class="recommended-card">

            <h5>

                <i class="fa-solid fa-briefcase me-2"></i>

                Recommended Opportunities

            </h5>

            <table class="recommended-table">

                <thead>

                    <tr>

                        <th>Opportunity</th>

                        <th>Company</th>

                        <th>Location</th>

                        <th>Salary</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    if (mysqli_num_rows($recommended) > 0) {

                        while ($opp = mysqli_fetch_assoc($recommended)) {

                    ?>

                            <tr>

                                <td>

                                    <?php echo htmlspecialchars($opp['opportunity_title']); ?>

                                </td>

                                <td>

                                    <?php echo htmlspecialchars($opp['company_name']); ?>

                                </td>

                                <td>

                                    <?php echo htmlspecialchars($opp['location']); ?>

                                </td>

                                <td>

                                    <?php echo htmlspecialchars($opp['salary']); ?>

                                </td>

                                <td>

                                    <a
                                        href="opportunities.php"
                                        class="btn-view-details">

                                        View Details

                                    </a>

                                </td>

                            </tr>

                        <?php

                        }
                    } else {

                        ?>

                        <tr>

                            <td colspan="5">

                                No opportunities available.

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>