<?php

include '../config/database.php';
include '../config/candidate-session.php';

if (!isset($_SESSION['candidate_id'])) {

    header("Location: ../auth/candidate-login.php");
    exit();
}

$user_id = $_SESSION['candidate_id'];

/* ==========================================
   USER DETAILS
========================================== */

$userQuery = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($userQuery);

/* ==========================================
   PROFILE DETAILS
========================================== */

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

/* ==========================================
   EDUCATION DETAILS
========================================== */

$educationQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_education
     WHERE user_id='$user_id'"
);

$educationData = [];

while ($row = mysqli_fetch_assoc($educationQuery)) {

    $educationData[$row['education_type']] = $row;
}

/* ==========================================
   SKILLS
========================================== */

$skillsQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM candidate_skills
     WHERE user_id='$user_id'"
);

$skillsArray = [];

while ($skill = mysqli_fetch_assoc($skillsQuery)) {

    $skillsArray[] = $skill['skill_name'];
}

$skillsText = implode(", ", $skillsArray);

/* ==========================================
   UPDATE PROFILE
========================================== */

if (isset($_POST['save_profile'])) {

    $phone =
        mysqli_real_escape_string(
            $conn,
            $_POST['phone']
        );

    $dob =
        mysqli_real_escape_string(
            $conn,
            $_POST['dob']
        );

    $gender =
        mysqli_real_escape_string(
            $conn,
            $_POST['gender']
        );

    $location =
        mysqli_real_escape_string(
            $conn,
            $_POST['location']
        );

    $about_me =
        mysqli_real_escape_string(
            $conn,
            $_POST['about_me']
        );

    $linkedin_url =
        mysqli_real_escape_string(
            $conn,
            $_POST['linkedin_url']
        );

    $github_url =
        mysqli_real_escape_string(
            $conn,
            $_POST['github_url']
        );

    $portfolio_url =
        mysqli_real_escape_string(
            $conn,
            $_POST['portfolio_url']
        );

    /* ==========================================
       PROFILE PHOTO
    ========================================== */

    $profile_photo =
        $profile['profile_photo'] ?? '';

    if (!empty($_FILES['profile_photo']['name'])) {

        $profile_photo =
            time() . "_" .
            $_FILES['profile_photo']['name'];

        move_uploaded_file(

            $_FILES['profile_photo']['tmp_name'],

            "../uploads/profile-photos/" .
                $profile_photo
        );
    }

    /* ==========================================
       RESUME
    ========================================== */

    $resume =
        $profile['resume'] ?? '';

    if (!empty($_FILES['resume']['name'])) {

        $resume =
            time() . "_" .
            $_FILES['resume']['name'];

        move_uploaded_file(

            $_FILES['resume']['tmp_name'],

            "../uploads/resumes/" .
                $resume
        );
    }

    /* ==========================================
       INSERT / UPDATE PROFILE
    ========================================== */

    $checkProfile = mysqli_query(

        $conn,

        "SELECT id
         FROM candidate_profiles
         WHERE user_id='$user_id'"
    );

    if (mysqli_num_rows($checkProfile) > 0) {

        mysqli_query(

            $conn,

            "UPDATE candidate_profiles SET

            phone='$phone',
            dob='$dob',
            gender='$gender',
            location='$location',
            about_me='$about_me',

            profile_photo='$profile_photo',
            resume='$resume',

            linkedin_url='$linkedin_url',
            github_url='$github_url',
            portfolio_url='$portfolio_url'

            WHERE user_id='$user_id'"
        );
    } else {

        mysqli_query(

            $conn,

            "INSERT INTO candidate_profiles(

            user_id,
            phone,
            dob,
            gender,
            location,
            about_me,

            profile_photo,
            resume,

            linkedin_url,
            github_url,
            portfolio_url

            )

            VALUES(

            '$user_id',
            '$phone',
            '$dob',
            '$gender',
            '$location',
            '$about_me',

            '$profile_photo',
            '$resume',

            '$linkedin_url',
            '$github_url',
            '$portfolio_url'
            )"
        );
    }

    /* ==========================================
   EDUCATION VALUES
========================================== */

    $college_name = mysqli_real_escape_string(
        $conn,
        $_POST['college_name']
    );

    $college_course = mysqli_real_escape_string(
        $conn,
        $_POST['college_course']
    );

    $college_start = mysqli_real_escape_string(
        $conn,
        $_POST['college_start']
    );

    $college_end = mysqli_real_escape_string(
        $conn,
        $_POST['college_end']
    );

    $college_cgpa = mysqli_real_escape_string(
        $conn,
        $_POST['college_cgpa']
    );

    $hsc_school = mysqli_real_escape_string(
        $conn,
        $_POST['hsc_school']
    );

    $hsc_year = mysqli_real_escape_string(
        $conn,
        $_POST['hsc_year']
    );

    $hsc_percentage = mysqli_real_escape_string(
        $conn,
        $_POST['hsc_percentage']
    );

    $sslc_school = mysqli_real_escape_string(
        $conn,
        $_POST['sslc_school']
    );

    $sslc_year = mysqli_real_escape_string(
        $conn,
        $_POST['sslc_year']
    );

    $sslc_percentage = mysqli_real_escape_string(
        $conn,
        $_POST['sslc_percentage']
    );

    $skills = $_POST['skills'];

    /* ==========================================
   REFRESH EDUCATION
========================================== */

    mysqli_query(

        $conn,

        "DELETE FROM candidate_education
     WHERE user_id='$user_id'"
    );

    mysqli_query(

        $conn,

        "INSERT INTO candidate_education(

    user_id,
    education_type,
    institution_name,
    course_name,
    start_year,
    end_year,
    cgpa_percentage

    )

    VALUES(

    '$user_id',
    'College',
    '$college_name',
    '$college_course',
    '$college_start',
    '$college_end',
    '$college_cgpa'

    )"
    );

    mysqli_query(

        $conn,

        "INSERT INTO candidate_education(

    user_id,
    education_type,
    institution_name,
    course_name,
    start_year,
    end_year,
    cgpa_percentage

    )

    VALUES(

    '$user_id',
    'HSC',
    '$hsc_school',
    '',
    '',
    '$hsc_year',
    '$hsc_percentage'

    )"
    );

    mysqli_query(

        $conn,

        "INSERT INTO candidate_education(

    user_id,
    education_type,
    institution_name,
    course_name,
    start_year,
    end_year,
    cgpa_percentage

    )

    VALUES(

    '$user_id',
    'SSLC',
    '$sslc_school',
    '',
    '',
    '$sslc_year',
    '$sslc_percentage'

    )"
    );

    /* ==========================================
   REFRESH SKILLS
========================================== */

    mysqli_query(

        $conn,

        "DELETE FROM candidate_skills
     WHERE user_id='$user_id'"
    );

    $skillsArray = explode(",", $skills);

    foreach ($skillsArray as $skill) {

        $skill = trim($skill);

        if (!empty($skill)) {

            mysqli_query(

                $conn,

                "INSERT INTO candidate_skills(

            user_id,
            skill_name

            )

            VALUES(

            '$user_id',
            '$skill'

            )"
            );
        }
    }

    echo "<script>
alert('Profile Updated Successfully');
window.location='candidate-profile.php';
</script>";
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Profile</title>

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

        <div class="profile-page-header">

            <h1 class="profile-page-title">
                Edit Profile
            </h1>

            <p class="profile-subtitle">
                Update your personal information, education and skills.
            </p>

            <div class="title-line"></div>

        </div>

        <form method="POST"
            enctype="multipart/form-data">

            <!-- ==========================
         BASIC PROFILE
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    Basic Information
                </h3>

                <div class="row">

                    <!-- PROFILE PHOTO -->

                    <div class="col-md-3 text-center">

                        <?php if (!empty($profile['profile_photo'])) { ?>

                            <img
                                src="../uploads/profile-photos/<?= $profile['profile_photo']; ?>"
                                class="profile-image">

                        <?php } else { ?>

                            <div class="profile-placeholder">

                                <i class="fa-solid fa-user"></i>

                            </div>

                        <?php } ?>

                        <div class="mt-3">

                            <label class="form-label">
                                Profile Photo
                            </label>

                            <input
                                type="file"
                                name="profile_photo"
                                class="form-control">

                        </div>

                    </div>

                    <!-- PROFILE DETAILS -->

                    <div class="col-md-9">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= $user['full_name']; ?>"
                                    readonly>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    value="<?= $user['email']; ?>"
                                    readonly>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"

                                    value="<?= $profile['phone'] ?? ''; ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="dob"
                                    class="form-control"

                                    value="<?= $profile['dob'] ?? ''; ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    class="form-select">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male">
                                        Male
                                    </option>

                                    <option value="Female">
                                        Female
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Location
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    class="form-control"

                                    value="<?= $profile['location'] ?? ''; ?>">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ==========================
         ABOUT ME
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    About Me
                </h3>

                <textarea

                    name="about_me"

                    rows="6"

                    class="form-control"><?=

                                            $profile['about_me'] ?? '';

                                            ?></textarea>

            </div>

            <!-- ==========================
         RESUME
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    Resume
                </h3>

                <input

                    type="file"

                    name="resume"

                    class="form-control">

                <?php if (!empty($profile['resume'])) { ?>

                    <div class="mt-3">

                        Current Resume:

                        <a

                            href="../uploads/resumes/<?= $profile['resume']; ?>"

                            target="_blank">

                            View Resume

                        </a>

                    </div>

                <?php } ?>

            </div>

            <!-- ==========================
         CONTACT LINKS
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    Contact Links
                </h3>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            LinkedIn URL
                        </label>

                        <input

                            type="text"

                            name="linkedin_url"

                            class="form-control"

                            value="<?= $profile['linkedin_url'] ?? ''; ?>">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            GitHub URL
                        </label>

                        <input

                            type="text"

                            name="github_url"

                            class="form-control"

                            value="<?= $profile['github_url'] ?? ''; ?>">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Portfolio URL
                        </label>

                        <input

                            type="text"

                            name="portfolio_url"

                            class="form-control"

                            value="<?= $profile['portfolio_url'] ?? ''; ?>">

                    </div>

                </div>

            </div>


            <!-- ==========================
         EDUCATION
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    Education
                </h3>

                <div class="row">

                    <!-- COLLEGE -->

                    <div class="col-12">

                        <h5 class="mb-3">
                            College / Degree
                        </h5>

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="text"
                            name="college_name"
                            class="form-control"

                            placeholder="Institution Name"

                            value="<?= $educationData['College']['institution_name'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="text"
                            name="college_course"
                            class="form-control"

                            placeholder="Course Name"

                            value="<?= $educationData['College']['course_name'] ?? ''; ?>">

                    </div>

                    <div class="col-md-2 mb-3">

                        <input
                            type="number"
                            name="college_start"

                            class="form-control"

                            placeholder="Start Year"

                            value="<?= $educationData['College']['start_year'] ?? ''; ?>">

                    </div>

                    <div class="col-md-2 mb-3">

                        <input
                            type="number"
                            name="college_end"

                            class="form-control"

                            placeholder="End Year"

                            value="<?= $educationData['College']['end_year'] ?? ''; ?>">

                    </div>

                    <div class="col-md-2 mb-3">

                        <input
                            type="text"
                            name="college_cgpa"

                            class="form-control"

                            placeholder="CGPA"

                            value="<?= $educationData['College']['cgpa_percentage'] ?? ''; ?>">

                    </div>

                    <!-- HSC -->

                    <div class="col-12 mt-4">

                        <h5 class="mb-3">
                            HSC / 12th
                        </h5>

                    </div>

                    <div class="col-md-4 mb-3">

                        <input
                            type="text"
                            name="hsc_school"

                            class="form-control"

                            placeholder="School Name"

                            value="<?= $educationData['HSC']['institution_name'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="number"
                            name="hsc_year"

                            class="form-control"

                            placeholder="Year"

                            value="<?= $educationData['HSC']['end_year'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="text"
                            name="hsc_percentage"

                            class="form-control"

                            placeholder="Percentage"

                            value="<?= $educationData['HSC']['cgpa_percentage'] ?? ''; ?>">

                    </div>

                    <!-- SSLC -->

                    <div class="col-12 mt-4">

                        <h5 class="mb-3">
                            SSLC / 10th
                        </h5>

                    </div>

                    <div class="col-md-4 mb-3">

                        <input
                            type="text"
                            name="sslc_school"

                            class="form-control"

                            placeholder="School Name"

                            value="<?= $educationData['SSLC']['institution_name'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="number"
                            name="sslc_year"

                            class="form-control"

                            placeholder="Year"

                            value="<?= $educationData['SSLC']['end_year'] ?? ''; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="text"
                            name="sslc_percentage"

                            class="form-control"

                            placeholder="Percentage"

                            value="<?= $educationData['SSLC']['cgpa_percentage'] ?? ''; ?>">

                    </div>

                </div>

            </div>

            <!-- ==========================
         SKILLS
    =========================== -->

            <div class="profile-card mb-4">

                <h3 class="card-heading">
                    Skills
                </h3>

                <label class="form-label">
                    Enter skills separated by commas
                </label>

                <textarea

                    name="skills"

                    rows="4"

                    class="form-control"><?=

                                            $skillsText;

                                            ?></textarea>

            </div>

            <!-- SAVE BUTTON -->

            <div class="text-center mb-5">

                <button

                    type="submit"

                    name="save_profile"

                    class="btn btn-primary btn-lg px-5">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Profile

                </button>

                <a

                    href="candidate-profile.php"

                    class="btn btn-secondary btn-lg px-5 ms-2">

                    Cancel

                </a>

            </div>

        </form>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>