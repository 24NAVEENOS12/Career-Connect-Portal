<?php

include '../config/session.php';

$current_page = basename($_SERVER['PHP_SELF']);

?>

<style>
    .company-sidebar {

        position: fixed;

        top: 0;
        left: 0;

        width: 260px;
        height: 100vh;

        background: #071d49;

        color: white;

        z-index: 1000;

        box-shadow:
            5px 0 20px rgba(0, 0, 0, 0.15);
    }

    .company-sidebar {
        font-family: 'Poppins', sans-serif;
    }

    /* Logo Section */

    .sidebar-logo {

        height: 100px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-logo h4 {

        margin: 0;

        font-size: 25px;

        font-weight: 700;

        color: white;
    }

    .sidebar-logo i {

        color: #3b82f6;

        margin-right: 10px;
    }

    .logo-wrapper {
        text-align: center;
    }

    .logo-line {

        width: 70px;

        height: 3px;

        background: #2563eb;

        border-radius: 20px;

        margin: 10px auto 0;
    }

    /* Menu */

    .sidebar-menu {

        padding: 25px 15px;
    }

    .sidebar-menu a {

        display: flex;

        align-items: center;

        gap: 12px;

        color: white;

        text-decoration: none;

        padding: 14px 18px;

        margin-bottom: 10px;

        border-radius: 12px;

        transition: 0.3s;
    }

    .sidebar-menu a:hover {

        background: #2563eb;

        color: white;
    }

    /* Active Menu */

    .sidebar-menu a.active {

        background: #2563eb;

        color: white;

        font-weight: 600;
    }

    .sidebar-menu i {

        width: 22px;

        text-align: center;

        font-size: 16px;
    }

    /* Logout */

    .logout-btn {

        position: absolute;

        bottom: 20px;

        left: 15px;

        right: 15px;
    }

    .logout-btn a {

        display: flex;

        align-items: center;

        gap: 12px;

        background: #ef4444;

        color: white;

        text-decoration: none;

        padding: 14px 18px;

        border-radius: 12px;

        transition: 0.3s;
    }

    .logout-btn a:hover {

        background: #dc2626;
    }

    /* Mobile */

    @media(max-width:992px) {

        .company-sidebar {

            width: 220px;
        }
    }
</style>

<div class="company-sidebar">

    <!-- Logo -->

    <div class="sidebar-logo">

        <div class="logo-wrapper">

            <h4>
                <i class="fa-solid fa-briefcase"></i>
                Career Connect
            </h4>

            <div class="logo-line"></div>

        </div>

    </div>

    <!-- Menu -->

    <div class="sidebar-menu">

        <a href="dashboard.php"
            class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-chart-line"></i>

            Dashboard

        </a>

        <a href="post-opportunity.php"
            class="<?php echo ($current_page == 'post-opportunity.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-plus"></i>

            Post Opportunity

        </a>

        <a href="manage-opportunities.php"
            class="<?php echo ($current_page == 'manage-opportunities.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-briefcase"></i>

            Manage Opportunities

        </a>

        <a href="applicants.php"
            class="<?php echo ($current_page == 'applicants.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-users"></i>

            Applicants

        </a>

        <a href="company-profile.php"
            class="<?php echo ($current_page == 'company-profile.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-building"></i>

            Company Profile

        </a>

    </div>

    <!-- Logout -->

    <div class="logout-btn">

        <a href="../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</div>