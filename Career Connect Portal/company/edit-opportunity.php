<?php

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../auth/company-login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-opportunities.php");
    exit();
}

$opportunity_id = (int)$_GET['id'];
$company_id = $_SESSION['user_id'];

$fetch = mysqli_query(
    $conn,
    "SELECT *
     FROM opportunities
     WHERE id='$opportunity_id'
     AND company_id='$company_id'"
);

if (mysqli_num_rows($fetch) == 0) {
    header("Location: manage-opportunities.php");
    exit();
}

$row = mysqli_fetch_assoc($fetch);

$success = false;
$error = "";

if (isset($_POST['update_opportunity'])) {

    $opportunity_title = mysqli_real_escape_string(
        $conn,
        trim($_POST['opportunity_title'])
    );

    $opportunity_type = mysqli_real_escape_string(
        $conn,
        $_POST['opportunity_type']
    );

    $location = mysqli_real_escape_string(
        $conn,
        trim($_POST['location'])
    );

    $salary = mysqli_real_escape_string(
        $conn,
        trim($_POST['salary'])
    );

    $vacancies = (int)$_POST['vacancies'];

    $skills_required = mysqli_real_escape_string(
        $conn,
        trim($_POST['skills_required'])
    );

    $description = mysqli_real_escape_string(
        $conn,
        trim($_POST['description'])
    );

    $deadline = $_POST['deadline'];

    $update = mysqli_query(
        $conn,
        "UPDATE opportunities
         SET
         opportunity_title='$opportunity_title',
         opportunity_type='$opportunity_type',
         location='$location',
         salary='$salary',
         vacancies='$vacancies',
         skills_required='$skills_required',
         description='$description',
         deadline='$deadline'
         WHERE id='$opportunity_id'
         AND company_id='$company_id'"
    );

    if ($update) {

        $success = true;
    } else {

        $error = "Failed to update opportunity.";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Opportunity</title>

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

        <div class="dashboard-header">

            <h1 class="dashboard-title">
                Edit Opportunity
            </h1>

            <div class="title-line"></div>

        </div>

        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3>
                        ✏️ Edit Opportunity
                    </h3>

                    <p>
                        Update opportunity information and save changes.
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

        <?php if ($success) { ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check me-2"></i>

                Opportunity updated successfully.

            </div>

        <?php } ?>

        <?php if ($error != "") { ?>

            <div class="alert alert-danger">

                <?php echo $error; ?>

            </div>

        <?php } ?>

        <?php if (!$success) { ?>

            <div class="section-card">

                <h5 class="mb-4">

                    <i class="fa-solid fa-briefcase me-2"></i>

                    Opportunity Details

                </h5>

                <form method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Opportunity Title

                            </label>

                            <input
                                type="text"
                                name="opportunity_title"
                                class="form-control"
                                value="<?php echo htmlspecialchars($row['opportunity_title']); ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Opportunity Type

                            </label>

                            <select
                                name="opportunity_type"
                                class="form-select"
                                required>

                                <option value="Internship"
                                    <?php if ($row['opportunity_type'] == "Internship") echo "selected"; ?>>
                                    Internship
                                </option>

                                <option value="Part-Time"
                                    <?php if ($row['opportunity_type'] == "Part-Time") echo "selected"; ?>>
                                    Part-Time
                                </option>

                                <option value="Full-Time"
                                    <?php if ($row['opportunity_type'] == "Full-Time") echo "selected"; ?>>
                                    Full-Time
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Location

                            </label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?php echo htmlspecialchars($row['location']); ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Salary / Stipend

                            </label>

                            <input
                                type="text"
                                name="salary"
                                class="form-control"
                                value="<?php echo htmlspecialchars($row['salary']); ?>"
                                required>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Vacancies

                            </label>

                            <input
                                type="number"
                                name="vacancies"
                                class="form-control"
                                value="<?php echo $row['vacancies']; ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Application Deadline

                            </label>

                            <input
                                type="date"
                                name="deadline"
                                class="form-control"
                                value="<?php echo $row['deadline']; ?>"
                                required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Skills Required

                        </label>

                        <textarea
                            name="skills_required"
                            rows="3"
                            class="form-control"
                            required><?php echo htmlspecialchars($row['skills_required']); ?></textarea>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Opportunity Description

                        </label>

                        <textarea
                            name="description"
                            rows="6"
                            class="form-control"
                            required><?php echo htmlspecialchars($row['description']); ?></textarea>

                    </div>

                    <button
                        type="submit"
                        name="update_opportunity"
                        class="btn btn-primary-custom">

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Update Opportunity

                    </button>

                    <a href="manage-opportunities.php"
                        class="btn btn-secondary ms-2">

                        Back

                    </a>

                </form>

            </div>

        <?php } ?>

    </div>

</body>

</html>