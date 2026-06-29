<?php

include '../config/session.php';

$current_page = basename($_SERVER['PHP_SELF']);

?>

<style>

    .candidate-sidebar{

        position:fixed;

        top:0;
        left:0;

        width:260px;
        height:100vh;

        background:#071d49;

        color:white;

        z-index:1000;

        box-shadow:
        5px 0 20px rgba(0,0,0,0.15);

        font-family:'Poppins',sans-serif;
    }

    /* Logo */

    .candidate-sidebar .sidebar-logo{

        height:100px;

        display:flex;

        align-items:center;

        justify-content:center;

        border-bottom:
        1px solid rgba(255,255,255,0.08);
    }

    .candidate-sidebar .sidebar-logo h4{

        margin:0;

        font-size:25px;

        font-weight:700;

        color:white;
    }

    .candidate-sidebar .sidebar-logo i{

        color:#3b82f6;

        margin-right:10px;
    }

    .candidate-sidebar .logo-wrapper{

        text-align:center;
    }

    .candidate-sidebar .logo-line{

        width:70px;

        height:3px;

        background:#2563eb;

        border-radius:20px;

        margin:10px auto 0;
    }

    /* Menu */

    .candidate-sidebar .sidebar-menu{

        padding:25px 15px;
    }

    .candidate-sidebar .sidebar-menu a{

        display:flex;

        align-items:center;

        gap:12px;

        color:white;

        text-decoration:none;

        padding:14px 18px;

        margin-bottom:10px;

        border-radius:12px;

        transition:.3s;
    }

    .candidate-sidebar .sidebar-menu a:hover{

        background:#2563eb;

        color:white;
    }

    .candidate-sidebar .sidebar-menu a.active{

        background:#2563eb;

        color:white;

        font-weight:600;
    }

    .candidate-sidebar .sidebar-menu i{

        width:22px;

        text-align:center;

        font-size:16px;
    }

    /* Logout */

    .candidate-sidebar .logout-btn{

        position:absolute;

        bottom:20px;

        left:15px;

        right:15px;
    }

    .candidate-sidebar .logout-btn a{

        display:flex;

        align-items:center;

        gap:12px;

        background:#ef4444;

        color:white;

        text-decoration:none;

        padding:14px 18px;

        border-radius:12px;

        transition:.3s;
    }

    .candidate-sidebar .logout-btn a:hover{

        background:#dc2626;
    }

    @media(max-width:992px){

        .candidate-sidebar{

            width:220px;
        }
    }

</style>

<div class="candidate-sidebar">

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

        <a href="candidate-dashboard.php"
           class="<?php echo ($current_page == 'candidate-dashboard.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-table-columns"></i>

            Dashboard

        </a>

        <a href="opportunities.php"
           class="<?php echo ($current_page == 'opportunities.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-briefcase"></i>

            Opportunities

        </a>

        <a href="applied-opportunities.php"
           class="<?php echo ($current_page == 'applied-opportunities.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-file-lines"></i>

            Applied Opportunities

        </a>

        <a href="candidate-profile.php"
           class="<?php echo ($current_page == 'candidate-profile.php') ? 'active' : ''; ?>">

            <i class="fa-solid fa-user"></i>

            My Profile

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