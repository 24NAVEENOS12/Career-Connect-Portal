<?php

include '../config/database.php';
include '../config/candidate-session.php';

if (!isset($_SESSION['candidate_id'])) {

    header("Location: ../auth/candidate-login.php");
    exit();
}

$candidate_id = $_SESSION['candidate_id'];

$success = "";
$error = "";

/* ==========================================
   APPLY OPPORTUNITY
========================================== */

if (isset($_GET['apply'])) {

    $opportunity_id = (int) $_GET['apply'];

    $check_apply = mysqli_query(
        $conn,
        "SELECT id
         FROM applications
         WHERE candidate_id = '$candidate_id'
         AND opportunity_id = '$opportunity_id'"
    );

    if (mysqli_num_rows($check_apply) == 0) {

        $insert_apply = mysqli_query(
            $conn,
            "INSERT INTO applications
            (
                opportunity_id,
                candidate_id,
                status
            )
            VALUES
            (
                '$opportunity_id',
                '$candidate_id',
                'Applied'
            )"
        );

        if ($insert_apply) {

            $success = "Opportunity applied successfully.";
        }
    }
}

/* ==========================================
   REJECT OPPORTUNITY
========================================== */

if (isset($_GET['reject'])) {

    $opportunity_id = (int) $_GET['reject'];

    $check_reject = mysqli_query(
        $conn,
        "SELECT id
         FROM rejected_opportunities
         WHERE candidate_id = '$candidate_id'
         AND opportunity_id = '$opportunity_id'"
    );

    if (mysqli_num_rows($check_reject) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO rejected_opportunities
            (
                candidate_id,
                opportunity_id
            )
            VALUES
            (
                '$candidate_id',
                '$opportunity_id'
            )"
        );
    }

    header("Location: opportunities.php");
    exit();
    
}

$search = mysqli_real_escape_string(
    $conn,
    $_GET['search'] ?? ''
);

$type = mysqli_real_escape_string(
    $conn,
    $_GET['type'] ?? ''
);

$sort = $_GET['sort'] ?? 'latest';


$where = "WHERE o.status='Active'";


if(!empty($search))
{
    $where .= " AND (

        o.opportunity_title LIKE '%$search%'

        OR c.company_name LIKE '%$search%'

        OR o.skills_required LIKE '%$search%'

    )";
}


if(!empty($type))
{
    $where .= " AND o.opportunity_type='$type'";
}


$order = ($sort == 'oldest')

    ? "ORDER BY o.created_at ASC"

    : "ORDER BY o.created_at DESC";

/* ==========================================
   FETCH ALL OPPORTUNITIES
========================================== */

$opportunities = mysqli_query(

    $conn,

    "SELECT

        o.*,

        c.company_name,

        c.company_logo,

        c.id AS company_profile_id

    FROM opportunities o

    LEFT JOIN companies c

    ON o.company_id = c.user_id

    $where

    $order"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Opportunities
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

        <!-- PAGE TITLE -->

        <div class="dashboard-header">

            <h1 class="dashboard-title">

                Opportunities

            </h1>

            <div class="title-line"></div>

        </div>

        <!-- SUCCESS MESSAGE -->

        <?php if ($success != "") { ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check me-2"></i>

                <?php echo $success; ?>

            </div>

        <?php } ?>

        <!-- SEARCH -->

        <div class="opportunity-filters">

            <form method="GET" class="filter-form">

                <input
                    type="text"
                    name="search"
                    class="filter-input"
                    placeholder="Search"
                    value="<?= $_GET['search'] ?? '' ?>">

                <select
                    name="type"
                    class="filter-select">

                    <option value="">All Types</option>
                    <option value="Full-Time">Full-Time</option>
                    <option value="Part-Time">Part-Time</option>
                    <option value="Internship">Internship</option>

                </select>

                <select
                    name="sort"
                    class="filter-select">

                    <option value="latest">Latest</option>
                    <option value="oldest">Oldest</option>

                </select>

                <button
                    type="submit"
                    class="filter-btn">

                    <i class="fa-solid fa-filter"></i>

                    Filter

                </button>

            </form>

        </div>

        <!-- OPPORTUNITY GRID -->

        <div class="opportunity-grid">

            <?php

            if (mysqli_num_rows($opportunities) > 0) {

                while ($row = mysqli_fetch_assoc($opportunities)) {

                    $opportunity_id =
                        $row['id'];

                    $check_applied =
                        mysqli_query(

                            $conn,

                            "SELECT id

                         FROM applications

                         WHERE candidate_id='$candidate_id'

                         AND opportunity_id='$opportunity_id'"
                        );

                    $is_applied =
                        mysqli_num_rows($check_applied) > 0;

            ?>

                    <div class="opportunity-card">

                        <!-- HEADER -->

                        <div class="opportunity-header">

                            <div style="display:flex;align-items:center;gap:12px;">

                                <?php

                                if (!empty($row['company_logo'])) {

                                ?>

                                    <img
                                        src="../uploads/company-logos/<?php echo $row['company_logo']; ?>"
                                        style="
                                width:55px;
                                height:55px;
                                border-radius:12px;
                                object-fit:cover;
                                background:white;
                                padding:4px;">

                                <?php

                                } else {

                                ?>

                                    <div style="
                            width:55px;
                            height:55px;
                            border-radius:12px;
                            background:#2563eb;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            color:white;">

                                        <i class="fa-solid fa-building"></i>

                                    </div>

                                <?php } ?>

                                <div>

                                    <h6 style="
                            margin:0;
                            color:#ffffff;
                            font-weight:700;">

                                        <?php
                                        echo $row['company_name'];
                                        ?>

                                    </h6>

                                </div>

                            </div>

                        </div>

                        <!-- TITLE -->

                        <h5 class="opportunity-title">

                            <?php
                            echo $row['opportunity_title'];
                            ?>

                        </h5>

                        <div class="opportunity-type">

                            (<?php echo $row['opportunity_type']; ?>)

                        </div>

                        <div class="card-divider"></div>

                        <!-- DETAILS -->

                        <div class="opportunity-details">

                            <div class="detail-row">

                                <div class="detail-label">

                                    📍 Location

                                </div>

                                <div class="detail-value">

                                    <?php echo $row['location']; ?>

                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">

                                    👥 Vacancies

                                </div>

                                <div class="detail-value">

                                    <?php echo $row['vacancies']; ?>

                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">

                                    💰 Salary

                                </div>

                                <div class="detail-value">

                                    <?php echo $row['salary']; ?>

                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">

                                    🛠 Skills

                                </div>

                                <div class="detail-value">

                                    <?php echo $row['skills_required']; ?>

                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">

                                    📝 Description

                                </div>

                                <div class="detail-value">

                                    <?php echo substr($row['description'], 0, 120); ?>...

                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">

                                    📅 Deadline

                                </div>

                                <div class="detail-value">

                                    <?php echo date("d M Y", strtotime($row['deadline'])); ?>

                                </div>

                            </div>

                        </div>

                        <div class="card-divider"></div>

                        <!-- BUTTONS -->

                        <div class="action-buttons">

                            <a
                                href="view-company.php?id=<?php echo $row['company_id']; ?>"
                                class="btn-edit">

                                <i class="fa-solid fa-building me-1"></i>

                                View Company

                            </a>

                            <?php if ($is_applied) { ?>

                                <span class="btn-success px-3 py-2 rounded">

                                    <i class="fa-solid fa-circle-check me-1"></i>

                                    Applied

                                </span>

                            <?php } else { ?>

                                <a
                                    href="opportunities.php?apply=<?php echo $row['id']; ?>"
                                    class="btn-toggle">

                                    <i class="fa-solid fa-paper-plane me-1"></i>

                                    Apply

                                </a>

                            <?php } ?>

                            <a
                                href="opportunities.php?reject=<?php echo $row['id']; ?>"
                                class="btn-delete"
                                onclick="return confirm('Hide this opportunity?');">

                                <i class="fa-solid fa-xmark me-1"></i>

                                Reject

                            </a>

                        </div>

                    </div>

                <?php

                }
            } else {

                ?>

                <div class="alert alert-info">

                    No opportunities available.

                </div>

            <?php } ?>

        </div>

    </div>

</body>

</html>