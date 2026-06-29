```php
<?php

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../auth/company-login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

/* ==========================================
   DELETE OPPORTUNITY
========================================== */

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM opportunities
         WHERE id='$id'
         AND company_id='$company_id'"
    );

    header("Location: manage-opportunities.php");
    exit();
}

/* ==========================================
   OPEN / CLOSE OPPORTUNITY
========================================== */

if (isset($_GET['toggle'])) {

    $id = (int) $_GET['toggle'];

    $check = mysqli_query(
        $conn,
        "SELECT status
         FROM opportunities
         WHERE id='$id'
         AND company_id='$company_id'"
    );

    $row = mysqli_fetch_assoc($check);

    if ($row['status'] == 'Active') {

        $new_status = 'Closed';
    } else {

        $new_status = 'Active';
    }

    mysqli_query(
        $conn,
        "UPDATE opportunities
         SET status='$new_status'
         WHERE id='$id'
         AND company_id='$company_id'"
    );

    header("Location: manage-opportunities.php");
    exit();
}

/* ==========================================
   STATISTICS
========================================== */

$total_opportunities = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM opportunities
         WHERE company_id='$company_id'"
    )
)['total'];

$active_opportunities = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM opportunities
         WHERE company_id='$company_id'
         AND status='Active'"
    )
)['total'];

$closed_opportunities = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM opportunities
         WHERE company_id='$company_id'
         AND status='Closed'"
    )
)['total'];

$total_applications = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications a
         INNER JOIN opportunities o
         ON a.opportunity_id=o.id
         WHERE o.company_id='$company_id'"
    )
)['total'];

/* ==========================================
   OPPORTUNITIES
========================================== */

$opportunities = mysqli_query(
    $conn,
    "SELECT *
     FROM opportunities
     WHERE company_id='$company_id'
     ORDER BY created_at DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Manage Opportunities
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

    <div class="company-content ">

        <!-- PAGE TITLE -->

        <div class="dashboard-header">

            <h1 class="dashboard-title" style="margin-top:-25px;">
                Manage Opportunities
            </h1>

            <div class="title-line"></div>

        </div>

        <!-- OPPORTUNITY CARDS -->

        <div class="opportunity-grid">

            <?php

            if (mysqli_num_rows($opportunities) > 0) {

                while ($row = mysqli_fetch_assoc($opportunities)) {

            ?>

                    <div class="opportunity-card">

                        <div class="opportunity-header">

                            <h5 class="opportunity-title">

                                <?php echo htmlspecialchars($row['opportunity_title']); ?>

                            </h5>

                            <span class="<?php echo ($row['status'] == 'Active') ? 'status-active' : 'status-closed'; ?>">

                                <?php echo $row['status']; ?>

                            </span>

                        </div>

                        <div class="opportunity-type">

                            (<?php echo $row['opportunity_type']; ?>)

                        </div>

                        <div class="card-divider"></div>

                        <div class="opportunity-details">

                            <div class="detail-row">
                                <span class="detail-label">📍 Location</span>
                                <span class="detail-value">
                                    <?php echo htmlspecialchars($row['location']); ?>
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">👥 Vacancies</span>
                                <span class="detail-value">
                                    <?php echo $row['vacancies']; ?>
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">💰 Salary</span>
                                <span class="detail-value">
                                    <?php echo htmlspecialchars($row['salary']); ?>
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">🛠 Skills</span>
                                <span class="detail-value">
                                    <?php echo htmlspecialchars($row['skills_required']); ?>
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">📝 Description</span>
                                <span class="detail-value">
                                    <?php echo substr($row['description'], 0, 80); ?>...
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">📅 Deadline</span>
                                <span class="detail-value">
                                    <?php echo date("d M Y", strtotime($row['deadline'])); ?>
                                </span>
                            </div>

                        </div>

                        <div class="action-buttons">

                            <a href="edit-opportunity.php?id=<?php echo $row['id']; ?>"
                                class="btn-edit">

                                Edit

                            </a>

                            <a href="manage-opportunities.php?toggle=<?php echo $row['id']; ?>"
                                class="btn-toggle">

                                <?php echo ($row['status'] == 'Active') ? 'Close' : 'Open'; ?>

                            </a>

                            <a href="manage-opportunities.php?delete=<?php echo $row['id']; ?>"
                                class="btn-delete"
                                onclick="return confirm('Are you sure you want to delete this opportunity?')">

                                Delete

                            </a>

                        </div>

                    </div>

            <?php

                }
            } else {

                echo "
            <div class='section-card text-center'>
                <h5>No Opportunities Posted Yet</h5>
                <p>Create your first opportunity to attract candidates.</p>
            </div>
            ";
            }

            ?>

        </div>

    </div>

</body>

</html>
```