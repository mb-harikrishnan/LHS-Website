<!--Header-->
<header>
    <nav class="navbar navbar-default navbar-sticky bootsnav">
        <div class="container">

            <!-- Mobile Menu Button -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Logo + School Name -->
                <a class="navbar-brand logo-brand" href="<?php echo base_url('home_page'); ?>">
                    <img src="<?php echo base_url('assets/images/main_image/school_logo.png') ?>"
                        class="logo logo-scrolled"
                        alt="School Logo">

                    <span class="school-name">--</span>
                </a>
            </div>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOut">

                    <li>
                        <a href="<?php echo base_url('home_page'); ?>">Home</a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">School</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('mandatory_disclosure'); ?>">Mandatory Disclosure</a></li>
                            <li><a href="<?php echo base_url('transfer_certificates'); ?>">Transfer Certificates</a></li>
                            <li><a href="<?php echo base_url('about_us'); ?>">About Us</a></li>
                            <li><a href="<?php echo base_url('mission_vision'); ?>">Mission & Vision</a></li>
                            <li><a href="<?php echo base_url('directors_message'); ?>">Director's Message</a></li>
                            <li><a href="<?php echo base_url('principals_message'); ?>">Principal's Message</a></li>
                            <li><a href="<?php echo base_url('news'); ?>">School News</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Academics</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('fun_n_learn'); ?>">Fun 'N' Learn</a></li>
                            <li><a href="<?php echo base_url('curriculum'); ?>">Curriculum</a></li>
                            <li><a href="<?php echo base_url('scheme_of_studies'); ?>">Scheme Of Studies</a></li>
                            <li><a href="<?php echo base_url('rules_and_regulations'); ?>">Our Rules</a></li>
                            <li><a href="<?php echo base_url('discipline'); ?>">Discipline</a></li>
                            <li><a href="<?php echo base_url('fee_regulations'); ?>">Fee Regulations</a></li>
                            <li><a href="<?php echo base_url('admissions'); ?>">Admissions</a></li>
                            <li><a href="<?php echo base_url('school_uniform'); ?>">Uniform</a></li>
                            <li><a href="<?php echo base_url('parental_support'); ?>">A Word to Parents</a></li>
                            <li><a href="<?php echo base_url('downloads'); ?>">Downloads</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Activities</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('house_system'); ?>">House System</a></li>
                            <li><a href="<?php echo base_url('co_curricular_activities'); ?>">Co-Curricular Activities</a></li>
                            <li><a href="<?php echo base_url('sports_and_games'); ?>">Sports & Games</a></li>
                            <li><a href="<?php echo base_url('clubs'); ?>">Clubs</a></li>
                        </ul>
                    </li>

                    <li><a href="<?php echo base_url('gallery'); ?>">Gallery</a></li>
                    <li><a href="<?php echo base_url('vaccancy'); ?>">Vacancies</a></li>
                    <li class="active"><a href="<?php echo base_url('contact'); ?>">Contact Us</a></li>

                </ul>
            </div>

        </div>
    </nav>
</header>
<!--Header-->

<style>
    /* =========================
   COMPACT HEADER FIX
========================= */

    /* ONLY COLOR CHANGE FOR HEADER */

    /* HEADER BACKGROUND */
    .navbar {
        background: linear-gradient(135deg, #f0fdfa, #e6fffb) !important;
        box-shadow: 0 4px 18px rgba(15, 118, 110, 0.08);
    }

    /* SCHOOL NAME */
    .school-name {
        color: #0654c2 !important;
    }

    /* MENU LINKS */
    .navbar-nav>li>a {
        color: #444 !important;
    }

    /* MENU HOVER */
    .navbar-nav>li>a:hover,
    .navbar-nav>.active>a {
        color: #0654c2 !important;
        background: none !important;
    }

    /* DROPDOWN */
    .dropdown-menu {
        background: #f8fffe !important;
    }

    /* DROPDOWN HOVER */
    .dropdown-menu li a:hover {
        background: #e6fffb !important;
        color: #0654c2 !important;
    }

    /* MOBILE MENU BUTTON */
    .navbar-toggle {
        background: #e6fffb !important;
    }

    .navbar-toggle i {
        color: #0654c2 !important;
    }

    /* NAVBAR */
    .navbar {
        min-height: 62px !important;
        background: #f8fffe !important;
        border: none !important;
        box-shadow: 0 4px 18px rgba(15, 118, 110, 0.08);
    }

    /* CONTAINER */
    .navbar .container {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* BRAND */
    .logo-brand {
        display: flex !important;
        align-items: center;
        gap: 6px;
        height: 62px;
        padding: 0 !important;
        margin-right: 10px;
    }

    /* SMALL LOGO */
    .logo-brand img {
        width: 34px;
        height: 34px;
        object-fit: contain;
    }

    /* SMALL SCHOOL NAME */
    .school-name {
        font-size: 15px;
        font-weight: 700;
        color: #0654c2 !important;
        white-space: nowrap;
        line-height: 1;
        margin: 0;
    }

    /* MENU AREA */
    #navbar-menu {
        flex: 1;
        display: flex !important;
        justify-content: flex-end;
    }

    /* MENU */
    .navbar-nav {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        margin: 0;
    }

    /* MENU LINKS */
    .navbar-nav>li>a {
        padding: 22px 8px !important;
        font-size: 12px;
        font-weight: 600;
        color: #444 !important;
        white-space: nowrap;
        transition: 0.3s;
    }

    /* HOVER */
    .navbar-nav>li>a:hover,
    .navbar-nav>.active>a {
        color: #0654c2 !important;
        background: none !important;
    }

    /* DROPDOWN */
    .dropdown-menu {
        border: none !important;
        border-radius: 14px !important;
        margin-top: 5px !important;
        padding: 8px 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    /* DROPDOWN LINKS */
    .dropdown-menu li a {
        padding: 10px 18px !important;
        font-size: 12px;
        color: #555 !important;
    }

    .dropdown-menu li a:hover {
        background: #e6fffb !important;
        color: #0654c2 !important;
    }

    /* MOBILE BUTTON */
    .navbar-toggle {
        margin-top: 12px;
        border: none !important;
        background: #e6fffb !important;
        border-radius: 8px;
    }

    .navbar-toggle i {
        color: #0654c2;
    }

    /* MOBILE */
    @media(max-width:991px) {

        .navbar .container {
            display: block;
        }

        .navbar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 15px;
        }

        .logo-brand {
            height: auto;
        }

        .logo-brand img {
            width: 32px;
            height: 32px;
        }

        .school-name {
            font-size: 14px;
        }

        #navbar-menu {
            display: block !important;
            background: #fff;
            margin-top: 8px;
        }

        .navbar-nav {
            display: block;
        }

        .navbar-nav>li>a {
            padding: 12px 18px !important;
            font-size: 13px;
            border-bottom: 1px solid #f5f5f5;
        }

        .dropdown-menu {
            box-shadow: none;
            border-radius: 0 !important;
            background: #f0fdfa;
        }

        .dropdown-menu li a {
            padding: 10px 28px !important;
        }
    }
</style>