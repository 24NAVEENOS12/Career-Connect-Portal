<?php

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../auth/company-login.php");
    exit();
}

$success = false;
$error = "";

if (isset($_POST['post_opportunity'])) {

    $company_id = $_SESSION['user_id'];

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

    $vacancies = (int) $_POST['vacancies'];

    $skills_required = mysqli_real_escape_string(
        $conn,
        trim($_POST['skills_required'])
    );

    $description = mysqli_real_escape_string(
        $conn,
        trim($_POST['description'])
    );

    $deadline = $_POST['deadline'];

    $insert = mysqli_query(
        $conn,
        "INSERT INTO opportunities
        (
            company_id,
            opportunity_title,
            opportunity_type,
            location,
            salary,
            vacancies,
            skills_required,
            description,
            deadline,
            status
        )
        VALUES
        (
            '$company_id',
            '$opportunity_title',
            '$opportunity_type',
            '$location',
            '$salary',
            '$vacancies',
            '$skills_required',
            '$description',
            '$deadline',
            'Active'
        )"
    );

    if ($insert) {

        header("Location: post-opportunity.php?success=1");
        exit();
    } else {

        $error = "Failed to post opportunity.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Post Opportunity
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

        <!-- Page Heading -->

        <div class="dashboard-header">

            <h1 class="dashboard-title">

                Post Opportunity

            </h1>

            <div class="title-line"></div>

        </div>

        <!-- Welcome Card -->

        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3>
                        🏢 Post New Opportunity
                    </h3>

                    <p>
                        Fill in the details below to publish a new opportunity for candidates.
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

        <?php if (isset($_GET['success'])) { ?>

            <div class="success-card">

                <div class="success-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <h2>
                    Opportunity Posted Successfully!
                </h2>

                <p>
                    Your opportunity has been published and is now visible to candidates.
                </p>

                <a href="post-opportunity.php"
                    class="btn-post-another">

                    <i class="fa-solid fa-plus"></i>

                    Post Another Opportunity

                </a>

            </div>

        <?php } ?>



        <!-- Error Message -->

        <?php if ($error != "") { ?>

            <div class="alert alert-danger">

                <?php echo $error; ?>

            </div>

        <?php } ?>

        <?php if (!isset($_GET['success'])) { ?>

            <!-- Form Card -->

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
                                placeholder="e.g. Full Stack Developer Intern"
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

                                <option value="">
                                    Select Type
                                </option>

                                <option value="Internship">
                                    Internship
                                </option>

                                <option value="Part-Time">
                                    Part-Time
                                </option>

                                <option value="Full-Time">
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
                                placeholder="e.g. Chennai"
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
                                placeholder="e.g. ₹25,000 / month"
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
                                min="1"
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
                            placeholder="HTML, CSS, JavaScript, PHP, MySQL"
                            required></textarea>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Opportunity Description

                        </label>

                        <textarea
                            name="description"
                            rows="6"
                            class="form-control"
                            placeholder="Describe the opportunity..."
                            required></textarea>

                    </div>

                    <button
                        type="submit"
                        name="post_opportunity"
                        class="btn btn-primary-custom">

                        <i class="fa-solid fa-paper-plane me-2"></i>

                        Publish Opportunity

                    </button>

                </form>

            </div>

        <?php } ?>

    </div>

</body>

</html>