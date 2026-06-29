<?php

include '../config/database.php';
include '../config/candidate-session.php';

/* ==========================================
   CANDIDATE SESSION
========================================== */

$candidate_id = $_SESSION['candidate_id'];

/* ==========================================
   FILTERS
========================================== */

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

$where = " WHERE a.candidate_id = '$candidate_id' ";

/* SEARCH */

if (!empty($search)) {

    $search = mysqli_real_escape_string($conn, $search);

    $where .= "
        AND (
            o.opportunity_title LIKE '%$search%'
            OR c.company_name LIKE '%$search%'
            OR o.skills_required LIKE '%$search%'
        )
    ";
}

/* STATUS */

if (!empty($status_filter)) {

    $status_filter = mysqli_real_escape_string(
        $conn,
        $status_filter
    );

    $where .= "
        AND a.status='$status_filter'
    ";
}

/* SORT */

$orderBy = " ORDER BY a.applied_at DESC ";

if ($sort == "oldest") {

    $orderBy = " ORDER BY a.applied_at ASC ";
}

if ($sort == "company") {

    $orderBy = " ORDER BY c.company_name ASC ";
}

if ($sort == "title") {

    $orderBy = " ORDER BY o.opportunity_title ASC ";
}

/* ==========================================
   APPLICATIONS QUERY
========================================== */

$query = mysqli_query(

    $conn,

    "SELECT

        a.id AS application_id,
        a.status,
        a.applied_at,

        o.id AS opportunity_id,
        o.opportunity_title,
        o.opportunity_type,
        o.location,
        o.salary,
        o.skills_required,
        o.deadline,

        c.company_name,
        c.company_logo,

        u.full_name

    FROM applications a

    INNER JOIN opportunities o
        ON a.opportunity_id = o.id

    INNER JOIN companies c
        ON o.company_id = c.user_id

    INNER JOIN users u
        ON c.user_id = u.id

    $where

    $orderBy"
);

/* ==========================================
   TOTAL COUNTS
========================================== */

$totalApplications = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total

         FROM applications

         WHERE candidate_id='$candidate_id'"
    )
);

$totalApplications =
    $totalApplications['total'];

/* ==========================================
   STATUS COUNTS
========================================== */

$underReview = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total

         FROM applications

         WHERE candidate_id='$candidate_id'

         AND status='Under Review'"
    )
)['total'];

$shortlisted = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total

         FROM applications

         WHERE candidate_id='$candidate_id'

         AND status='Shortlisted'"
    )
)['total'];

$selected = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total

         FROM applications

         WHERE candidate_id='$candidate_id'

         AND status='Selected'"
    )
)['total'];

$rejected = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total

         FROM applications

         WHERE candidate_id='$candidate_id'

         AND status='Rejected'"
    )
)['total'];

/* ==========================================
   STATUS BADGE FUNCTION
========================================== */

function getStatusClass($status)
{
    switch ($status) {

        case 'Under Review':
            return 'status-review';

        case 'Shortlisted':
            return 'status-shortlisted';

        case 'Selected':
            return 'status-selected';

        case 'Rejected':
            return 'status-rejected';

        default:
            return 'status-applied';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Applied Opportunities</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <link rel="stylesheet"
        href="../assets/css/candidate.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <?php include '../includes/candidate-sidebar.php'; ?>

    <div class="candidate-content">

        <!-- PAGE HEADER -->

        <div class="page-header">

            <div>

                <h1 class="page-title">
                    Applied Opportunities
                </h1>

                <!-- <p class="page-subtitle">
                    Track and manage all your applications.
                </p> -->

                <div class="title-line"></div>

            </div>

        </div>

        <!-- STATS CARDS -->

        <div class="row g-4 mb-4">

            <div class="col-md-3">

                <div class="stats-card">

                    <div class="stats-icon">

                        <i class="fa-solid fa-file-lines"></i>

                    </div>

                    <div>

                        <h2><?= $totalApplications ?></h2>

                        <h6>Total Applications</h6>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card selected-card">

                    <div class="stats-icon">

                        <i class="fa-solid fa-trophy"></i>

                    </div>

                    <div>

                        <h2><?= $selected ?></h2>

                        <h6>Selected</h6>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card shortlist-card">

                    <div class="stats-icon">

                        <i class="fa-solid fa-star"></i>

                    </div>

                    <div>

                        <h2><?= $shortlisted ?></h2>

                        <h6>Shortlisted</h6>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card review-card">

                    <div class="stats-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <h2><?= $underReview ?></h2>

                        <h6>Under Review</h6>

                    </div>

                </div>

            </div>

        </div>

        <!-- FILTER SECTION -->

        <div class="filter-card">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-lg-5">

                        <input

                            type="text"

                            name="search"

                            value="<?= htmlspecialchars($search) ?>"

                            class="form-control"

                            placeholder="Search">

                    </div>

                    <div class="col-lg-3">

                        <select

                            name="status"

                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Applied"
                                <?= ($status_filter == "Applied") ? 'selected' : '' ?>>
                                Applied
                            </option>

                            <option value="Under Review"
                                <?= ($status_filter == "Under Review") ? 'selected' : '' ?>>
                                Under Review
                            </option>

                            <option value="Shortlisted"
                                <?= ($status_filter == "Shortlisted") ? 'selected' : '' ?>>
                                Shortlisted
                            </option>

                            <option value="Selected"
                                <?= ($status_filter == "Selected") ? 'selected' : '' ?>>
                                Selected
                            </option>

                            <option value="Rejected"
                                <?= ($status_filter == "Rejected") ? 'selected' : '' ?>>
                                Rejected
                            </option>

                        </select>

                    </div>

                    <div class="col-lg-2">

                        <select

                            name="sort"

                            class="form-select">

                            <option value="latest"
                                <?= ($sort == "latest") ? 'selected' : '' ?>>
                                Latest
                            </option>

                            <option value="oldest"
                                <?= ($sort == "oldest") ? 'selected' : '' ?>>
                                Oldest
                            </option>

                            <option value="company"
                                <?= ($sort == "company") ? 'selected' : '' ?>>
                                Company
                            </option>

                            <option value="title"
                                <?= ($sort == "title") ? 'selected' : '' ?>>
                                Opportunity
                            </option>

                        </select>

                    </div>

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fa-solid fa-filter"></i>

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

        <!-- APPLICATION LIST -->

        <div class="applications-table">

            <div class="table-header">

                <div>OPPORTUNITY</div>
                <div>COMPANY</div>
                <div>TYPE</div>
                <div>LOCATION</div>
                <div>APPLIED ON</div>
                <div>STATUS</div>
                <div>ACTIONS</div>

            </div>

            <?php

            if (mysqli_num_rows($query) > 0) {

                while ($row = mysqli_fetch_assoc($query)) {

            ?>

                    <div class="table-row">

                        <!-- Opportunity -->

                        <div class="col-opportunity">

                            <div class="opportunity-box">

                                <?php if (!empty($row['company_logo'])) { ?>

                                    <img
                                        src="../uploads/company-logos/<?= $row['company_logo']; ?>"
                                        class="company-logo">

                                <?php } else { ?>

                                    <div class="company-logo-placeholder">

                                        <i class="fa-solid fa-building"></i>

                                    </div>

                                <?php } ?>

                                <div>

                                    <h6>

                                        <?= htmlspecialchars($row['opportunity_title']) ?>

                                    </h6>

                                    <!-- <small>

                                        Skills:
                                        <?= htmlspecialchars($row['skills_required']) ?>

                                    </small> -->

                                </div>

                            </div>

                        </div>

                        <!-- Company -->

                        <h6>

                            <?= htmlspecialchars($row['company_name']) ?>

                        </h6>

                        <!-- Type -->

                        <?php

                        $type = strtolower($row['opportunity_type']);

                        $typeClass = '';

                        if ($type == 'full-time') {
                            $typeClass = 'type-fulltime';
                        } elseif ($type == 'part-time') {
                            $typeClass = 'type-parttime';
                        } elseif ($type == 'internship') {
                            $typeClass = 'type-internship';
                        }

                        ?>

                        <h6>

                            <span class="type-badge <?= $typeClass ?>">

                                <?= $row['opportunity_type'] ?>

                            </span>

                        </h6>

                        <!-- Location -->

                        <h6>

                            <i class="fa-solid fa-location-dot me-2"></i>

                            <?= htmlspecialchars($row['location']) ?>

                        </h6>

                        <!-- Applied Date -->

                        <h6>

                            <i class="fa-regular fa-calendar me-2"></i>

                            <?= date(
                                "d M Y",
                                strtotime($row['applied_at'])
                            ); ?>

                        </h6>

                        <!-- Status -->

                        <h6>

                            <span
                                class="status-badge <?= getStatusClass($row['status']) ?>">

                                <?= $row['status'] ?>

                            </span>

                        </h6>

                        <!-- Action -->

                        <div>

                            <a
                                href="opportunities.php?id=<?= $row['opportunity_id'] ?>"
                                class="btn-details">

                                View Details

                            </a>

                        </div>

                    </div>

                <?php

                }
            } else {

                ?>

                <div class="empty-row">

                    No Applications Found

                </div>

            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>