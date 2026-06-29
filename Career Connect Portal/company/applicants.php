<?php

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../auth/company-login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

/* ==========================================
   STATISTICS
========================================== */

$total_applicants = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id = o.id
         WHERE o.company_id = '$company_id'"
    )
)['total'];

$total_selected = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id = o.id
         WHERE o.company_id = '$company_id'
         AND a.status='Selected'"
    )
)['total'];

$total_shortlisted = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id = o.id
         WHERE o.company_id = '$company_id'
         AND a.status='Shortlisted'"
    )
)['total'];

$total_review = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id = o.id
         WHERE o.company_id = '$company_id'
         AND a.status='Under Review'"
    )
)['total'];

$rejected_count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id = o.id
         WHERE o.company_id='$company_id'
         AND a.status='Rejected'"
    )
);
$total_rejected = $rejected_count['total'] ?? 0;

/* ==========================================
   APPLICANTS
========================================== */

$applicants = mysqli_query(
    $conn,
    "SELECT
        a.id,
        a.status,
        a.applied_at,
        a.candidate_id,

        u.full_name,

        cp.profile_photo,
        cp.resume,

        o.opportunity_title

     FROM applications a

     INNER JOIN users u
     ON a.candidate_id = u.id

     LEFT JOIN candidate_profiles cp
     ON cp.user_id = u.id

     INNER JOIN opportunities o
     ON a.opportunity_id = o.id

     WHERE o.company_id = '$company_id'

     ORDER BY a.applied_at DESC"
);


/* ==========================================
   UPDATE APPLICATION STATUS
========================================== */

if (isset($_POST['update_status'])) {

    $application_id = (int)$_POST['application_id'];

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    mysqli_query(

        $conn,

        "UPDATE applications

         SET status='$status'

         WHERE id='$application_id'"
    );

    header("Location: applicants.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Applicants
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <link rel="stylesheet"
        href="../assets/css/company.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ==========================================
   STAT CARD ICONS
========================================== */

        .stats-icon {

            width: 60px;

            height: 60px;

            border-radius: 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #fff;

            font-size: 26px;
        }

        .applicants-icon {

            background: linear-gradient(135deg,
                    #2563eb,
                    #1d4ed8);
        }

        .selected-icon {

            background: linear-gradient(135deg,
                    #16a34a,
                    #15803d);
        }

        .shortlisted-icon {

            background: linear-gradient(135deg,
                    #f59e0b,
                    #f4b400);
        }

        .review-icon {

            background: linear-gradient(135deg,
                    #9333ea,
                    #7e22ce);
        }

        .rejected-icon {

            background: linear-gradient(135deg,
                    #ef4444,
                    #dc2626);
        }

        .stats-content {

            text-align: right;
        }

        .stats-content h2 {

            margin: 0;

            font-size: 28px;

            font-weight: 700;
        }

        .stats-content p {

            margin: 0;

            font-size: 14px;
        }

        .applicant-stats {

            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 20px;

            margin-top: 20px;

            margin-bottom: 25px;
        }

        .applicant-stat-card {

            background: #071d49;

            color: white;

            border-radius: 20px;

            padding: 18px 22px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .applicant-stat-card h3 {

            margin: 0;

            font-size: 24px;

            font-weight: 700;
        }

        .applicant-stat-card p {

            margin: 0;

            font-size: 14px;
        }

        .applicant-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 25px;
        }

        .applicant-card {

            background: #071d49;

            color: white;

            position: relative;

            border-radius: 20px;

            padding: 25px;

            text-align: center;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .applicant-photo {

            width: 110px;

            height: 110px;

            border-radius: 50%;

            object-fit: cover;

            /* display:flex;
    justify-content:center;
    align-items:center; */

            border: 4px solid #2563eb;

            margin-bottom: 15px;
        }

        .status-badge {

            display: inline-block;

            position: absolute;

            top: 20px;

            right: 20px;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            margin-bottom: 15px;

            background: #2563eb;
        }

        .status-applied {

            background: #2563eb;
        }

        .status-review {

            background: #9333ea;
        }

        .status-shortlisted {

            background: #f59e0b;
        }

        .status-selected {

            background: #16a34a;
        }

        .status-rejected {

            background: #ef4444;
        }

        .applicant-name {

            font-size: 18px;

            font-weight: 700;

            margin-bottom: 10px;
        }

        .applied-for {

            font-size: 14px;

            color: #d1d5db;

            margin-bottom: 20px;
        }

        .resume-btn {

            display: inline-block;

            background: #2563eb;

            color: white;

            text-decoration: none;

            padding: 10px 16px;

            border-radius: 10px;

            font-size: 14px;

            transition: .3s;
        }

        .resume-btn:hover {

            background: #1d4ed8;

            color: white;
        }

        .no-resume {

            display: inline-block;

            background: #ef4444;

            color: white;

            padding: 10px 16px;

            border-radius: 10px;

            font-size: 14px;
        }

        .status-dropdown {

            height: 40px;

            font-size: 13px;

            padding: 4px 35px 4px 10px;

            border-radius: 8px;
        }

        .update-status-btn {

            height: 35px;

            font-size: 13px;

            font-weight: 600;
        }

        @media(max-width:1200px) {

            .applicant-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }
        }

        @media(max-width:768px) {

            .applicant-stats {

                grid-template-columns:
                    repeat(2, 1fr);
            }

            .applicant-grid {

                grid-template-columns: 1fr;
            }
        }
    </style>

</head>

<body>

    <?php include '../includes/company-sidebar.php'; ?>

    <div class="company-content">

        <div class="dashboard-header">

            <h1 class="dashboard-title">

                Applicants

            </h1>

            <div class="title-line"></div>

        </div>

        <!-- Statistics -->

        <div class="applicant-stats">

            <!-- Total Applicants -->

            <div class="applicant-stat-card">

                <div class="stats-icon applicants-icon">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div class="stats-content">

                    <p>Total Applicants</p>

                    <h3><?php echo $total_applicants; ?></h3>

                </div>

            </div>

            <!-- Selected -->

            <div class="applicant-stat-card">

                <div class="stats-icon selected-icon">

                    <i class="fa-solid fa-trophy"></i>

                </div>

                <div class="stats-content">

                    <p>Selected</p>

                    <h3><?php echo $total_selected; ?></h3>

                </div>

            </div>

            <!-- Shortlisted -->

            <div class="applicant-stat-card">

                <div class="stats-icon shortlisted-icon">

                    <i class="fa-solid fa-star"></i>

                </div>

                <div class="stats-content">

                    <p>Shortlisted</p>

                    <h3><?php echo $total_shortlisted; ?></h3>

                </div>

            </div>

            <!-- Under Review -->

            <div class="applicant-stat-card">

                <div class="stats-icon review-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div class="stats-content">

                    <p>Under Review</p>

                    <h3><?php echo $total_review; ?></h3>

                </div>

            </div>

            <!-- Rejected -->

            <div class="applicant-stat-card">

                <div class="stats-icon rejected-icon">

                    <i class="fa-solid fa-circle-xmark"></i>

                </div>

                <div class="stats-content">

                    <p>Rejected</p>

                    <h3><?php echo $total_rejected; ?></h3>

                </div>

            </div>

        </div>

        <!-- Applicant Cards -->

        <div class="applicant-grid">

            <?php

            if (mysqli_num_rows($applicants) > 0) {

                while ($row = mysqli_fetch_assoc($applicants)) {

            ?>

                    <div class="applicant-card">

                        <?php

                        if (!empty($row['profile_photo'])) {

                        ?>

                            <img
                                src="../uploads/profile-photos/<?php echo $row['profile_photo']; ?>"
                                class="applicant-photo">

                        <?php

                        } else {

                        ?>

                            <img
                                src="../assets/images/default-user.png"
                                class="applicant-photo">

                        <?php } ?>

                        <?php

                        $statusClass = "status-applied";

                        if ($row['status'] == "Under Review") {
                            $statusClass = "status-review";
                        } elseif ($row['status'] == "Shortlisted") {
                            $statusClass = "status-shortlisted";
                        } elseif ($row['status'] == "Selected") {
                            $statusClass = "status-selected";
                        } elseif ($row['status'] == "Rejected") {
                            $statusClass = "status-rejected";
                        }

                        ?>

                        <span class="status-badge <?= $statusClass ?>">

                            <?= $row['status']; ?>

                        </span>

                        <div class="applicant-name">

                            <?php echo htmlspecialchars($row['full_name']); ?>

                        </div>

                        <div class="applied-for">

                            Applied For:

                            <br>

                            <strong>

                                <?php echo htmlspecialchars($row['opportunity_title']); ?>

                            </strong>

                        </div>

                        <?php

                        if (!empty($row['resume'])) {

                        ?>
                            <a href="view-candidate.php?id=<?php echo $row['candidate_id']; ?>"
                                class="btn btn-info">

                                <i class="fa-solid fa-user"></i>

                                View Candidate

                            </a>

                            <a
                                href="../uploads/resumes/<?php echo $row['resume']; ?>"
                                target="_blank"
                                class="resume-btn">

                                <i class="fa-solid fa-file-pdf me-2"></i>

                                View Resume

                            </a>

                        <?php

                        } else {

                        ?>

                            <span class="no-resume">

                                Resume Not Uploaded

                            </span>

                        <?php } ?>


                        <form method="POST" class="mt-3">

                            <input
                                type="hidden"
                                name="application_id"
                                value="<?= $row['id']; ?>">

                            <select
                                name="status"
                                class="form-select form-select-sm status-dropdown mb-2">

                                <option value="Applied"
                                    <?= $row['status'] == "Applied" ? "selected" : "" ?>>
                                    Applied
                                </option>

                                <option value="Under Review"
                                    <?= $row['status'] == "Under Review" ? "selected" : "" ?>>
                                    Under Review
                                </option>

                                <option value="Shortlisted"
                                    <?= $row['status'] == "Shortlisted" ? "selected" : "" ?>>
                                    Shortlisted
                                </option>

                                <option value="Selected"
                                    <?= $row['status'] == "Selected" ? "selected" : "" ?>>
                                    Selected
                                </option>

                                <option value="Rejected"
                                    <?= $row['status'] == "Rejected" ? "selected" : "" ?>>
                                    Rejected
                                </option>

                            </select>

                            <button
                                type="submit"
                                name="update_status"
                                class="btn btn-success update-status-btn w-100"

                                <i class="fa-solid fa-check"></i>

                                Update Status

                            </button>

                        </form>

                    </div>

                <?php

                }
            } else {

                ?>

                <div class="section-card">

                    No Applicants Found

                </div>

            <?php } ?>

        </div>

    </div>

</body>

</html>