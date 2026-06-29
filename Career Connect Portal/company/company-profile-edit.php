<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/database.php';
include '../config/company-session.php';

if (!isset($_SESSION['company_id'])) {

    header("Location: ../auth/company-login.php");
    exit();
}

$user_id = $_SESSION['company_id'];

/* ==========================================
   FETCH COMPANY DATA
========================================== */

$companyQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM companies
     WHERE user_id='$user_id'"
);

$company = mysqli_fetch_assoc($companyQuery);


/* ==========================================
   UPDATE PROFILE
========================================== */

if (!$company) {
    $company = [];
}

if (isset($_POST['update_profile'])) {

    $company_name =
        mysqli_real_escape_string(
            $conn,
            $_POST['company_name']
        );

    $industry =
        mysqli_real_escape_string(
            $conn,
            $_POST['industry']
        );

    $website =
        mysqli_real_escape_string(
            $conn,
            $_POST['website']
        );

    $headquarters =
        mysqli_real_escape_string(
            $conn,
            $_POST['headquarters']
        );

    $about_company =
        mysqli_real_escape_string(
            $conn,
            $_POST['about_company']
        );

    $founded_year =
        mysqli_real_escape_string(
            $conn,
            $_POST['founded_year']
        );

    $office_locations =
        mysqli_real_escape_string(
            $conn,
            $_POST['office_locations']
        );

    $total_employees =
        mysqli_real_escape_string(
            $conn,
            $_POST['total_employees']
        );

    $projects_completed =
        mysqli_real_escape_string(
            $conn,
            $_POST['projects_completed']
        );

    $why_join_us =
        mysqli_real_escape_string(
            $conn,
            $_POST['why_join_us']
        );

    $company_culture =
        mysqli_real_escape_string(
            $conn,
            $_POST['company_culture']
        );

    $technologies_used =
        mysqli_real_escape_string(
            $conn,
            $_POST['technologies_used']
        );

    /* ==========================================
       COMPANY LOGO
    ========================================== */

    $company_logo =
        $company['company_logo'] ?? '';

    if (!empty($_FILES['company_logo']['name'])) {

        $company_logo =
            time() .
            '_' .
            $_FILES['company_logo']['name'];

        move_uploaded_file(

            $_FILES['company_logo']['tmp_name'],

            "../uploads/company-logos/" .
                $company_logo
        );
    }

    /* ==========================================
       COVER IMAGE
    ========================================== */

    $cover_image =
        $company['cover_image'] ?? '';

    if (!empty($_FILES['cover_image']['name'])) {

        $cover_image =
            time() .
            '_' .
            $_FILES['cover_image']['name'];

        move_uploaded_file(

            $_FILES['cover_image']['tmp_name'],

            "../uploads/company-covers/" .
                $cover_image
        );
    }

    $checkCompany = mysqli_query(
        $conn,
        "SELECT id FROM companies WHERE user_id='$user_id'"
    );

    if (mysqli_num_rows($checkCompany) > 0) {

        $result = mysqli_query(
            $conn,

            "UPDATE companies SET

        company_name='$company_name',
        industry='$industry',
        website='$website',
        headquarters='$headquarters',
        about_company='$about_company',

        founded_year='$founded_year',
        office_locations='$office_locations',
        total_employees='$total_employees',
        projects_completed='$projects_completed',

        why_join_us='$why_join_us',
        company_culture='$company_culture',
        technologies_used='$technologies_used',

        company_logo='$company_logo',
        cover_image='$cover_image'

        WHERE user_id='$user_id'"
        );
    } else {

        $result = mysqli_query(
            $conn,

            "INSERT INTO companies(

        user_id,
        company_name,
        company_logo,
        website,
        industry,
        founded_year,
        headquarters,
        about_company,
        total_employees,
        cover_image,
        office_locations,
        projects_completed,
        why_join_us,
        company_culture,
        technologies_used

        )

        VALUES(

        '$user_id',
        '$company_name',
        '$company_logo',
        '$website',
        '$industry',
        '$founded_year',
        '$headquarters',
        '$about_company',
        '$total_employees',
        '$cover_image',
        '$office_locations',
        '$projects_completed',
        '$why_join_us',
        '$company_culture',
        '$technologies_used'

        )"
        );
    }

    if ($result) {

        echo "<script>
            alert('Profile Updated Successfully');
            window.location='company-profile.php';
          </script>";
        exit();
    } else {

        die(mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='UTF-8'>

    <meta name='viewport'
        content='width=device-width, initial-scale=1.0'>

    <title>

        Edit Company Profile

    </title>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
        rel='stylesheet'>

    <link rel='stylesheet'
        href='../assets/css/style.css'>

    <link rel='stylesheet'
        href='../assets/css/company.css'>

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>

</head>

<body>

    <?php include '../includes/company-sidebar.php'; ?>

    <div class="company-content">

        <div class="profile-page-header">

            <div>

                <h1 class="profile-page-title">

                    Edit Company Profile

                </h1>

                <div class="title-line"></div>

            </div>

        </div>

        <form method="POST"
            enctype="multipart/form-data">

            <!-- COMPANY LOGO & COVER -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon blue">

                        <i class="fa-solid fa-image"></i>

                    </div>

                    <h4>

                        Company Branding

                    </h4>

                </div>

                <div class="row">

                    <!-- Logo -->

                    <div class="col-md-6">

                        <label class="form-label">

                            Company Logo

                        </label>

                        <?php if (!empty($company['company_logo'])) { ?>

                            <div class="mb-3">

                                <img
                                    src="../uploads/company-logos/<?=
                                                                    $company['company_logo']; ?>"

                                    style="
                            width:120px;
                            height:120px;
                            border-radius:15px;
                            object-fit:cover;
                            border:3px solid #2563eb;">

                            </div>

                        <?php } ?>

                        <input
                            type="file"
                            name="company_logo"
                            class="form-control">

                    </div>

                    <!-- Cover -->

                    <div class="col-md-6">

                        <label class="form-label">

                            Cover Image

                        </label>

                        <?php if (!empty($company['cover_image'])) { ?>

                            <div class="mb-3">

                                <img
                                    src="../uploads/company-covers/<?=
                                                                    $company['cover_image']; ?>"

                                    style="
                            width:100%;
                            height:120px;
                            border-radius:15px;
                            object-fit:cover;
                            border:3px solid #2563eb;">

                            </div>

                        <?php } ?>

                        <input
                            type="file"
                            name="cover_image"
                            class="form-control">

                    </div>

                </div>

            </div>

            <!-- BASIC INFORMATION -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon green">

                        <i class="fa-solid fa-building"></i>

                    </div>

                    <h4>

                        Basic Information

                    </h4>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Company Name

                        </label>

                        <input
                            type="text"
                            name="company_name"
                            class="form-control"

                            value="<?= $company['company_name'] ?? ''; ?>"

                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Industry

                        </label>

                        <input
                            type="text"
                            name="industry"
                            class="form-control"

                            value="<?= $company['industry'] ?? ''; ?>">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Website

                        </label>

                        <input
                            type="text"
                            name="website"
                            class="form-control"

                            value="<?= $company['website'] ?? ''; ?>">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Headquarters

                        </label>

                        <input
                            type="text"
                            name="headquarters"
                            class="form-control"

                            value="<?= $company['headquarters'] ?? ''; ?>">

                    </div>

                </div>

            </div>

            <!-- ABOUT COMPANY -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon orange">

                        <i class="fa-solid fa-circle-info"></i>

                    </div>

                    <h4>

                        About Company

                    </h4>

                </div>

                <textarea
                    name="about_company"

                    class="form-control"

                    rows="8"

                    placeholder="Describe your company..."><?= $company['about_company'] ?? ''; ?></textarea>

            </div>
            <!-- COMPANY HIGHLIGHTS -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon purple">

                        <i class="fa-solid fa-chart-column"></i>

                    </div>

                    <h4>

                        Company Highlights

                    </h4>

                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Founded Year

                        </label>

                        <input
                            type="number"
                            name="founded_year"
                            class="form-control"

                            value="<?= $company['founded_year'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Office Locations

                        </label>

                        <input
                            type="text"
                            name="office_locations"
                            class="form-control"

                            value="<?= $company['office_locations'] ?? ''; ?>"

                            placeholder="4">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Employees

                        </label>

                        <input
                            type="number"
                            name="total_employees"
                            class="form-control"

                            value="<?= $company['total_employees'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Projects Completed

                        </label>

                        <input
                            type="number"
                            name="projects_completed"
                            class="form-control"

                            value="<?= $company['projects_completed'] ?? ''; ?>">

                    </div>

                </div>

            </div>

            <!-- WHY JOIN US -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon green">

                        <i class="fa-solid fa-star"></i>

                    </div>

                    <h4>

                        Why Join Us

                    </h4>

                </div>

                <textarea
                    name="why_join_us"
                    class="form-control"
                    rows="5"

                    placeholder="Career Growth, Flexible Work, Competitive Salary, Learning Opportunities"><?= $company['why_join_us'] ?? ''; ?></textarea>

                <small class="text-light">

                    Separate each benefit using commas (,)

                </small>

            </div>

            <!-- COMPANY CULTURE -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon orange">

                        <i class="fa-solid fa-people-group"></i>

                    </div>

                    <h4>

                        Company Culture

                    </h4>

                </div>

                <textarea
                    name="company_culture"
                    class="form-control"
                    rows="6"

                    placeholder="Describe your company culture..."><?= $company['company_culture'] ?? ''; ?></textarea>

            </div>

            <!-- TECHNOLOGIES USED -->

            <div class="profile-card">

                <div class="section-heading">

                    <div class="section-icon blue">

                        <i class="fa-solid fa-laptop-code"></i>

                    </div>

                    <h4>

                        Technologies Used

                    </h4>

                </div>

                <textarea
                    name="technologies_used"
                    class="form-control"
                    rows="4"

                    placeholder="Java, Spring Boot, React, PHP, MySQL"><?= $company['technologies_used'] ?? ''; ?></textarea>

                <small class="text-light">

                    Separate technologies using commas (,)

                </small>

            </div>

            <!-- ACTION BUTTONS -->

            <div class="text-center mb-5">

                <button
                    type="submit"
                    name="update_profile"

                    class="btn btn-primary btn-lg px-5">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Profile

                </button>

                <a href="company-profile.php"
                    class="btn btn-secondary btn-lg px-5 ms-2">

                    Cancel

                </a>

            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>