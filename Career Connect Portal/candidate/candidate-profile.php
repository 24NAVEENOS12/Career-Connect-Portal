<?php

include '../config/database.php';
include '../config/candidate-session.php';

if (!isset($_SESSION['candidate_id'])) {

    header("Location: ../auth/candidate-login.php");
    exit();
}

// $user_id = $_SESSION['candidate_id'];

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $view_mode = "company";
} else {
    $user_id = $_SESSION['candidate_id'];
}

/* ==========================
   USER DETAILS
========================== */

$userQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($userQuery);

/* ==========================
   PROFILE DETAILS
========================== */

$profileQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_profiles
     WHERE user_id='$user_id'"
);

$profile = mysqli_fetch_assoc($profileQuery);

if (!$profile) {
    $profile = [];
}

/* ==========================
   EDUCATION
========================== */

$educationQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_education
     WHERE user_id='$user_id'
     ORDER BY end_year DESC"
);

/* ==========================
   SKILLS
========================== */

$skillsQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_skills
     WHERE user_id='$user_id'"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>


    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        My Profile
    </title>

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

    <?php

    if (isset($view_mode)) {
        include '../includes/company-sidebar.php';
    } else {
        include '../includes/candidate-sidebar.php';
    }

    ?>

    <div class="candidate-content">


        <!-- PAGE HEADER -->

        <div class="profile-page-header">

            <div>

                <h1 class="profile-page-title">
                    My Profile
                </h1>

                <!-- <p class="profile-subtitle">
                    View and manage your personal information and profile details.
                </p> -->

                <div class="title-line"></div>

            </div>

        </div>

        <!-- TOP SECTION -->

        <div class="row g-4">

            <!-- PROFILE CARD -->

            <div class="col-lg-8">

                <div class="profile-card">

                    <?php if (!isset($view_mode)) { ?>

                        <a href="candidate-profile-edit.php"
                            class="btn-edit">

                            Edit Profile

                        </a>

                    <?php } ?>

                    <div class="row">

                        <!-- LEFT -->

                        <div class="col-md-4 profile-left">

                            <div class="profile-image-wrapper">

                                <?php if (!empty($profile['profile_photo'])) { ?>

                                    <img
                                        src="../uploads/profile-photos/<?=
                                                                        $profile['profile_photo']; ?>"
                                        class="profile-image">

                                <?php } else { ?>

                                    <div class="profile-placeholder">

                                        <i class="fa-solid fa-user"></i>

                                    </div>

                                <?php } ?>

                            </div>

                            <h2 class="candidate-name">

                                <?= htmlspecialchars($user['full_name']); ?>

                            </h2>

                            <div class="candidate-info">

                                <p>

                                    <i class="fa-regular fa-envelope"></i>

                                    <?= htmlspecialchars($user['email']); ?>

                                </p>

                                <p>

                                    <i class="fa-solid fa-phone"></i>

                                    <?= htmlspecialchars(
                                        $profile['phone']
                                            ?? 'Not Added'
                                    ); ?>

                                </p>

                                <p>

                                    <i class="fa-solid fa-location-dot"></i>

                                    <?= htmlspecialchars(
                                        $profile['location']
                                            ?? 'Not Added'
                                    ); ?>

                                </p>

                            </div>

                        </div>

                        <!-- RIGHT -->

                        <div class="col-md-8 profile-right">

                            <h4 class="section-title">

                                About Me

                            </h4>

                            <p class="about-text">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $profile['about_me']
                                            ?? 'No information added yet.'
                                    )
                                ); ?>

                            </p>

                            <hr>

                            <div class="info-row">

                                <div>

                                    <i class="fa-regular fa-calendar"></i>

                                    Date of Birth

                                </div>

                                <span>

                                    <?= $profile['dob']
                                        ?? 'Not Added'; ?>

                                </span>

                            </div>

                            <hr>

                            <div class="info-row">

                                <div>

                                    <i class="fa-solid fa-user"></i>

                                    Gender

                                </div>

                                <span>

                                    <?= $profile['gender']
                                        ?? 'Not Added'; ?>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RESUME CARD -->

            <div class="col-lg-4">

                <div class="profile-card resume-card">

                    <h3 class="card-heading">

                        Resume

                    </h3>

                    <div class="resume-content">

                        <?php if (!empty($profile['resume'])) { ?>

                            <iframe
                                src="../uploads/resumes/<?= $profile['resume']; ?>"
                                class="resume-preview">
                            </iframe>

                            <div class="mt-3">

                                <a
                                    href="../uploads/resumes/<?= $profile['resume']; ?>"
                                    target="_blank"
                                    class="btn btn-primary">

                                    View Resume

                                </a>

                            </div>

                        <?php } else { ?>

                            <i class="fa-regular fa-file-lines resume-icon"></i>

                            <h4>
                                No Resume Uploaded
                            </h4>

                            <p>
                                Upload your resume to apply for opportunities.
                            </p>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

        <!-- EDUCATION + SKILLS + CONTACT -->

        <div class="row g-4 mt-1">

            <!-- EDUCATION CARD -->

            <div class="col-lg-4">

                <div class="profile-card">

                    <div class="card-header-custom">

                        <h3>

                            Education

                        </h3>

                        <?php if (!isset($view_mode)) { ?>

                            <a href="candidate-profile-edit.php">

                                <i class="fa-solid fa-plus"></i>

                                Add Education

                            </a>

                        <?php } ?>

                    </div>

                    <?php

                    if (mysqli_num_rows($educationQuery) > 0) {

                        while ($edu = mysqli_fetch_assoc($educationQuery)) {

                    ?>

                            <div class="education-item">

                                <div class="education-icon">

                                    <i class="fa-solid fa-graduation-cap"></i>

                                </div>

                                <div class="education-content">

                                    <h5>

                                        <?= htmlspecialchars(
                                            $edu['institution_name']
                                        ); ?>

                                    </h5>

                                    <p>

                                        <?= htmlspecialchars(
                                            $edu['course_name']
                                        ); ?>

                                    </p>

                                    <small>

                                        <?= $edu['start_year']; ?>

                                        -

                                        <?= $edu['end_year']; ?>

                                        |

                                        <?= htmlspecialchars(
                                            $edu['cgpa_percentage']
                                        ); ?>

                                    </small>

                                    <br>

                                    <span class="education-type">

                                        <?= htmlspecialchars(
                                            $edu['education_type']
                                        ); ?>

                                    </span>

                                </div>

                            </div>

                            <hr>

                        <?php

                        }
                    } else {

                        ?>

                        <p class="text-center">

                            No Education Added

                        </p>

                    <?php

                    }

                    ?>

                </div>

            </div>

            <!-- SKILLS CARD -->

            <div class="col-lg-4">

                <div class="profile-card">

                    <div class="card-header-custom">

                        <h3>

                            Skills

                        </h3>

                        <?php if (!isset($view_mode)) { ?>

                            <a href="candidate-profile-edit.php">

                                <i class="fa-solid fa-plus"></i>

                                Add Skill

                            </a>

                        <?php } ?>

                    </div>

                    <div class="skills-wrapper">

                        <?php

                        if (mysqli_num_rows($skillsQuery) > 0) {

                            while ($skill = mysqli_fetch_assoc($skillsQuery)) {

                        ?>

                                <span class="skill-badge">

                                    <?= htmlspecialchars(
                                        $skill['skill_name']
                                    ); ?>

                                </span>

                            <?php

                            }
                        } else {

                            ?>

                            <p>

                                No Skills Added

                            </p>

                        <?php

                        }

                        ?>

                    </div>

                </div>

            </div>

            <!-- CONTACT INFO CARD -->

            <div class="col-lg-4">

                <div class="profile-card">

                    <div class="card-header-custom">

                        <h3>

                            Other Contact Info

                        </h3>

                        <a href="candidate-profile-edit.php">

                            Edit

                        </a>

                    </div>

                    <!-- LINKEDIN -->

                    <div class="contact-item">

                        <div class="contact-left">

                            <i class="fa-brands fa-linkedin"></i>

                            <span>

                                LinkedIn

                            </span>

                        </div>

                        <?php if (!empty($profile['linkedin_url'])) { ?>

                            <a
                                href="<?= $profile['linkedin_url']; ?>"
                                target="_blank">

                                View

                                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                            </a>

                        <?php } else { ?>

                            <span>

                                Not Added

                            </span>

                        <?php } ?>

                    </div>

                    <hr>

                    <!-- PORTFOLIO -->

                    <div class="contact-item">

                        <div class="contact-left">

                            <i class="fa-solid fa-laptop-code"></i>

                            <span>

                                Portfolio

                            </span>

                        </div>

                        <?php if (!empty($profile['portfolio_url'])) { ?>

                            <a
                                href="<?= $profile['portfolio_url']; ?>"
                                target="_blank">

                                View

                                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                            </a>

                        <?php } else { ?>

                            <span>

                                Not Added

                            </span>

                        <?php } ?>

                    </div>

                    <hr>

                    <!-- GITHUB -->

                    <div class="contact-item">

                        <div class="contact-left">

                            <i class="fa-brands fa-github"></i>

                            <span>

                                GitHub

                            </span>

                        </div>

                        <?php if (!empty($profile['github_url'])) { ?>

                            <a
                                href="<?= $profile['github_url']; ?>"
                                target="_blank">

                                View

                                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                            </a>

                        <?php } else { ?>

                            <span>

                                Not Added

                            </span>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </div> <!-- candidate-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>