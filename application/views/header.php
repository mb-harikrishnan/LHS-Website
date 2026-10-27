<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <!-- Page title: each controller can pass $page_title, otherwise a friendly default -->
    <title><?php echo isset($page_title) && $page_title ? $page_title . ' | Little Hearts School' : 'Little Hearts School — Learn · Grow · Succeed'; ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/edua-icons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/owl.carousel.css">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/main_image/logonew.png'); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/owl.transitions.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cubeportfolio.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/settings.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootsnav.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loader.css">

    <!-- Friendly school typefaces: Fredoka (headings) + Nunito Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet">

    <!-- NEW THEME: global design system (loaded before page internal CSS so pages always win) -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/newtheme.css'); ?>">
    

     <!-- Navbar polish + CREATIVE DROPDOWN -->
    <style>
        /* dropdown items always read left-to-right */
        .dropdown-menu > li > a { text-align: left !important; }

        /* remove tap/click outline on buttons (keyboard users keep a gold ring) */
        .lh-apply-btn, .lh-login-btn, .navbar-toggle { -webkit-tap-highlight-color: transparent; }

        .lh-apply-btn:focus:not(:focus-visible),
        .lh-login-btn:focus:not(:focus-visible),
        .navbar-toggle:focus:not(:focus-visible),
        .navbar-nav > li > a:focus:not(:focus-visible),
        .dropdown-menu > li > a:focus:not(:focus-visible) { outline: none; }

        .lh-apply-btn:focus-visible,
        .lh-login-btn:focus-visible,
        .navbar-toggle:focus-visible { outline: 3px solid rgba(245, 166, 35, .55); outline-offset: 2px; }

        .navbar .dropdown { position: relative; }

        /* no transform on nav links themselves (stops the menu shifting under the cursor) */
        .navbar-nav > li > a { transform: none !important; transition: color .2s ease, background-color .2s ease; }
        /* .navbar-nav > li > a:hover,
        .navbar-nav > li > a:focus { transform: none !important; } */

        /* =====================================================
           CREATIVE DROPDOWN — shared look (desktop + mobile)
           Each row: icon tile on the left, label, arrow on hover
        ===================================================== */

        /* animated caret next to School / Academics / Activities */
        .navbar-nav > li > a .fa-caret-down {
            display: inline-block;
            margin-left: 4px;
            color: #f5a623;
            transition: transform .3s ease;
        }
        .navbar-nav > li.open > a .fa-caret-down { transform: rotate(180deg); }

        /* flat rows: wipe theme borders / shadows / margins */
        .navbar .dropdown-menu > li {
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            background: none !important;
            box-shadow: none !important;
        }

        .navbar .dropdown-menu > li > a {
            position: relative;
            display: block;
            padding: 8px 34px 8px 52px !important;
            border: 0 !important;
            border-radius: 12px;
            box-shadow: none !important;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
            color: #1c2530 !important;
            background: transparent !important;
            white-space: normal;
            transition: background .25s ease, color .25s ease, padding-left .25s ease;
        }

        /* icon tile */
        .navbar .dropdown-menu > li > a .dd-ico {
            position: absolute;
            left: 10px;
            top: 50%;
            width: 30px;
            height: 30px;
            margin-top: -15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(245, 166, 35, .15);
            color: #e2911a;
            font-size: 14px;
            transition: background .25s ease, color .25s ease, transform .3s cubic-bezier(.22, 1, .36, 1);
        }

        /* arrow that slides in on hover */
        .navbar .dropdown-menu > li > a::after {
            content: "\f178";
            font-family: FontAwesome;
            position: absolute;
            right: 12px;
            top: 50%;
            font-size: 12px;
            color: #e2911a;
            opacity: 0;
            transform: translate(-8px, -50%);
            transition: opacity .25s ease, transform .25s ease;
        }

        .navbar .dropdown-menu > li > a:hover,
        .navbar .dropdown-menu > li > a:focus {
            background: linear-gradient(90deg, rgba(245, 166, 35, .18), rgba(245, 166, 35, .03)) !important;
            color: #0b2447 !important;
        }
        .navbar .dropdown-menu > li > a:hover .dd-ico,
        .navbar .dropdown-menu > li > a:focus .dd-ico {
            background: linear-gradient(135deg, #f5a623, #e2911a);
            color: #fff;
            transform: rotate(-8deg) scale(1.08);
        }
        .navbar .dropdown-menu > li > a:hover::after,
        .navbar .dropdown-menu > li > a:focus::after {
            opacity: 1;
            /* transform: translate(0, -50%); */
        }

        /* current page inside a dropdown */
        .navbar .dropdown-menu > li.active > a {
            background: linear-gradient(90deg, #0b2447, #12305c) !important;
            color: #fff !important;
        }
        .navbar .dropdown-menu > li.active > a .dd-ico { background: #f5a623; color: #fff; }

        /* =====================================================
           DESKTOP-ONLY (>=992px)
        ===================================================== */
        @media (min-width: 992px) {

            .navbar .dropdown-menu {
                display: block !important;       /* always laid out; shown with opacity/visibility */
                margin-top: 0 !important;
                top: 100% !important;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                will-change: opacity, transform;

                min-width: 270px;
                padding: 10px !important;
                background: #fff !important;
                border: 1px solid rgba(11, 36, 71, .08) !important;
                border-radius: 16px !important;
                box-shadow: inset 0 3px 0 #f5a623, 0 26px 54px rgba(11, 36, 71, .20) !important;

                transform: translateY(10px) scale(.97);
                transform-origin: top right;
                transition: opacity .22s ease, transform .26s cubic-bezier(.22, 1, .36, 1), visibility .22s ease;
            }

            /* gold pointer triangle (menus are right-aligned under their link) */
            .navbar .dropdown-menu::before {
                content: "";
                position: absolute;
                top: -11px;
                right: 34px;
                left: auto;
                width: 22px;
                height: 12px;
                background: #f5a623;
                border: 0;
                border-radius: 0;
                transform: none;
                clip-path: polygon(50% 0, 0 100%, 100% 100%);
                pointer-events: none;
            }

            /* graduation-cap watermark in the corner */
            .navbar .dropdown-menu::after {
                content: "\f19d";
                font-family: FontAwesome;
                position: absolute;
                right: 14px;
                bottom: 8px;
                font-size: 46px;
                line-height: 1;
                color: rgba(11, 36, 71, .045);
                pointer-events: none;
            }

            /* invisible hover bridge between link and menu */
            .navbar .dropdown::after {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                top: 100%;
                height: 14px;
            }

            .navbar .dropdown:hover .dropdown-menu,
            .navbar .dropdown-menu:hover {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                
            }

            /* staggered entrance */
            .navbar .dropdown-menu > li {
                position: relative;
                z-index: 1;
                opacity: 0;
                transform: translateY(8px);
                transition: opacity .28s ease, transform .28s ease;
            }
            .navbar .dropdown:hover .dropdown-menu > li,
            .navbar .dropdown-menu:hover > li { opacity: 1; transform: none; }

            .navbar .dropdown-menu > li:nth-child(1)  { transition-delay: .03s; }
            .navbar .dropdown-menu > li:nth-child(2)  { transition-delay: .06s; }
            .navbar .dropdown-menu > li:nth-child(3)  { transition-delay: .09s; }
            .navbar .dropdown-menu > li:nth-child(4)  { transition-delay: .12s; }
            .navbar .dropdown-menu > li:nth-child(5)  { transition-delay: .15s; }
            .navbar .dropdown-menu > li:nth-child(6)  { transition-delay: .18s; }
            .navbar .dropdown-menu > li:nth-child(7)  { transition-delay: .21s; }
            .navbar .dropdown-menu > li:nth-child(8)  { transition-delay: .24s; }
            .navbar .dropdown-menu > li:nth-child(9)  { transition-delay: .27s; }
            .navbar .dropdown-menu > li:nth-child(10) { transition-delay: .30s; }

            /* long menus (8+ items, e.g. Academics) flow into two columns */
            .navbar .dropdown-menu:has(> li:nth-child(8)) {
                min-width: 540px;
                column-count: 2;
                column-gap: 6px;
            }
            .navbar .dropdown-menu:has(> li:nth-child(8)) > li { break-inside: avoid; }

            /* gold underline under plain top-level links */
            .navbar-nav > li:not(.dropdown):not(.lh-nav-login):not(.lh-nav-cta) > a { position: relative; }
            .navbar-nav > li:not(.dropdown):not(.lh-nav-login):not(.lh-nav-cta) > a::after {
                content: "";
                position: absolute;
                left: 50%;
                bottom: 0;
                width: 0;
                height: 3px;
                border-radius: 3px 3px 0 0;
                background: linear-gradient(90deg, #f5a623, #e2911a);
             
                transition: width .3s ease;
            }
            .navbar-nav > li:not(.dropdown):not(.lh-nav-login):not(.lh-nav-cta) > a:hover::after { width: 58%; }

            .navbar-nav > li.dropdown:hover > a .fa-caret-down { transform: rotate(180deg); }
        }

        /* =====================================================
           MOBILE (<992px): smooth accordion.
           Closed = max-height 0. Open = .open class on the <li>,
           which is set ONLY by the JS block at the bottom of this
           file (one open at a time, tap again to close).
        ===================================================== */
        @media (max-width: 991px) {

            /* ---- shared panel look (closed AND open) ---- */
            .navbar .navbar-nav > li.dropdown > .dropdown-menu {
                display: block !important;
                position: static !important;
                float: none !important;
                width: auto !important;
                min-width: 0 !important;
                height: auto !important;
                overflow: hidden !important;
                transform: none !important;
                animation: none !important;          /* kills animate.css fadeIn/fadeOut leftovers */
                -webkit-animation: none !important;
                border: none !important;
                border-left: 3px solid #f5a623 !important;
                border-radius: 0 14px 14px 0 !important;
                box-shadow: none !important;
                background: linear-gradient(90deg, rgba(245, 166, 35, .09), rgba(11, 36, 71, .02)) !important;
                transition: max-height .4s ease, margin .3s ease, padding .3s ease !important;
            }

            /* ---- CLOSED ---- */
            .navbar .navbar-nav > li.dropdown:not(.open) > .dropdown-menu {
                max-height: 0 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
                margin: 0 0 0 16px !important;
                padding: 0 6px !important;
                border-left-width: 0 !important;
            }

            /* ---- OPEN: everything forced visible so nothing can hide the items ---- */
            .navbar .navbar-nav > li.dropdown.open > .dropdown-menu {
                max-height: 1200px !important;
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
                margin: 2px 0 10px 16px !important;
                padding: 6px !important;
            }

            .navbar .navbar-nav > li.dropdown.open > .dropdown-menu > li {
                display: block !important;
                float: none !important;
                width: 100% !important;
                height: auto !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                transition: none !important;
                animation: none !important;
            }

            .navbar .navbar-nav > li.dropdown.open > .dropdown-menu > li > a {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                color: #1c2530 !important;
                padding: 10px 30px 10px 52px !important;
            }

            .navbar .navbar-nav > li.dropdown.open > .dropdown-menu > li.active > a { color: #fff !important; }

            .navbar-nav > li.open > a {
                color: #0b2447 !important;
                background: rgba(245, 166, 35, .08) !important;
            }
        }

        /* ---- Login button (sits beside Apply Now) ---- */
        .lh-nav-login { display: flex; align-items: center; }
        .lh-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px !important;
            margin-left: 10px;
            border: 1.5px solid #0b2447;
            border-radius: 4px;
            background: transparent;
            color: #0b2447 !important;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color .2s ease, color .2s ease;
        }
        .lh-login-btn:hover,
        .lh-login-btn:focus {
            background: #0b2447;
            color: #fff !important;
        }
        @media (max-width: 991px) {
            .lh-nav-login { padding: 10px 18px; }
            .lh-login-btn { width: 100%; justify-content: center; margin-left: 0; }
        }


        /* ===== NEWS TICKER: clickable + adjustable speed ===== */
.news-ticker-label {
    color: inherit;
    text-decoration: none;
}
.news-ticker-track {
    overflow: hidden;
    position: relative;
    flex: 1;
}
.news-ticker-list {
    display: inline-block;
    margin: 0;
    padding: 0 0 0 100%;              /* starts just outside the right edge */
    list-style: none;
    white-space: nowrap;
    animation: lhTicker var(--ticker-duration, 30s) linear infinite !important;
    will-change: transform;
}
.news-ticker-list li {
    display: inline-block;
    padding-right: 60px;              /* gap between news items */
}
.news-ticker-list li a {
    color: inherit;
    text-decoration: none;
    transition: color .2s ease;
}
.news-ticker-list li a:hover { color: #f5a623; text-decoration: underline; }

/* pause while the visitor hovers / touches so they can click */
.news-ticker-track:hover .news-ticker-list,
.news-ticker-track:active .news-ticker-list {
    animation-play-state: paused !important;
}

@keyframes lhTicker {
    from { transform: translateX(0); }
    to   { transform: translateX(-100%); }
}
    </style>

    
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.png">

    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">

    <a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>

    <!--Search-->
    <div id="search">
        <button type="button" class="close">×</button>
        <form>
            <input type="search" value="" placeholder="Search here...." required />
            <button type="submit" class="btn btn_common blue">Search</button>
        </form>
    </div>

    <!-- ============================================================
         LITTLE HEARTS SCHOOL — HEADER
         ============================================================ -->

    <?php

        $sql = "SELECT c_news FROM school_news WHERE c_status='Y'";
        $query = $this->db->query($sql);
        $result = $query->result();

        $ticker_speed = 60;   // <-- running speed: 30 = slow, 60 = normal, 100 = fast
    ?>

    <header class="lh-header">

        <!-- Top Info Bar -->
        <div class="lh-topbar">
            <div class="container1 lh-topbar-grid">
                <div class="lh-topbar-left">
                    <span><i class="fa fa-phone"></i> <a href="tel:+919744693905">+91-9744693905&nbsp;|&nbsp;0484-2446939</a></span>
                    <span><i class="fa fa-envelope"></i> <a href="mailto:littleheartsschool97@gmail.com">littleheartsschool97@gmail.com</a></span>
                </div>

                <div class="lh-affiliation">
                    <i class="fa fa-graduation-cap"></i>
                    <span>Affiliated to CBSE, New Delhi &mdash; No. 930601 &nbsp;&bull;&nbsp; Kizhakkepram, N. Paravur &ndash; 683 513</span>
                </div>

                <div class="lh-topbar-right">
                    <a href="https://www.facebook.com/profile.php?id=100071342449643" target="_blank" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@littleheartsschool7138" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a>
                </div>
            </div>
        </div>

        <!-- Scrolling News Ticker -->
        <!-- <div class="news-ticker-wrap">
            <div class="news-ticker-label">
                <i class="fa fa-bullhorn"></i> <span>Latest News</span>
            </div>
            <div class="news-ticker-track">
                <ul class="news-ticker-list">
                    <?php foreach ($result as $row) { ?>
                        <li><?php echo $row->c_news; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div> -->

        <!-- Scrolling News Ticker -->
<?php if (!empty($result)) { ?>
<div class="news-ticker-wrap" data-speed="<?php echo (int)$ticker_speed; ?>">
    <a class="news-ticker-label" href="<?php echo base_url('news'); ?>">
        <i class="fa fa-bullhorn"></i> <span>Latest News</span>
    </a>
    <div class="news-ticker-track">
        <ul class="news-ticker-list">
            <?php foreach ($result as $row) { ?>
                <li>
                    <a href="<?php echo base_url('news'); ?>">
                        <?php echo $row->c_news; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>

        <!-- Main Navigation -->
        <nav class="navbar navbar-default navbar-sticky bootsnav">
            <div class="container">

                <!-- Mobile Menu Button -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Logo -->
                    <a class="navbar-brand logo-brand" href="<?php echo base_url('home_page'); ?>">
                        <img src="<?php echo base_url('assets/images/main_image/logonew12.png'); ?>" alt="School Logo">
                    </a>
                </div>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOut">

                        <li><a href="<?php echo base_url('home_page'); ?>">Home</a></li>

                        <!-- SCHOOL -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">School <i class="fa fa-caret-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('about_us'); ?>"><i class="fa fa-info-circle dd-ico"></i>About Us</a></li>
                                <li><a href="<?php echo base_url('mandatory_disclosure'); ?>"><i class="fa fa-shield dd-ico"></i>Mandatory Disclosure</a></li>
                                <li><a href="<?php echo base_url('transfer_certificates'); ?>"><i class="fa fa-file-text-o dd-ico"></i>Transfer Certificates</a></li>
                                <li><a href="<?php echo base_url('mission_vision'); ?>"><i class="fa fa-bullseye dd-ico"></i>Mission &amp; Vision</a></li>
                                <li><a href="<?php echo base_url('directors_message'); ?>"><i class="fa fa-user-circle-o dd-ico"></i>Director's Message</a></li>
                                <li><a href="<?php echo base_url('principals_message'); ?>"><i class="fa fa-commenting-o dd-ico"></i>Principal's Message</a></li>
                                <li><a href="<?php echo base_url('news'); ?>"><i class="fa fa-newspaper-o dd-ico"></i>School News</a></li>
                            </ul>
                        </li>

                        <!-- ACADEMICS -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Academics <i class="fa fa-caret-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('fun_n_learn'); ?>"><i class="fa fa-smile-o dd-ico"></i>Fun 'N' Learn</a></li>
                                <li><a href="<?php echo base_url('curriculum'); ?>"><i class="fa fa-book dd-ico"></i>Curriculum</a></li>
                                <li><a href="<?php echo base_url('scheme_of_studies'); ?>"><i class="fa fa-calendar dd-ico"></i>Scheme Of Studies</a></li>
                                <li><a href="<?php echo base_url('rules_and_regulations'); ?>"><i class="fa fa-gavel dd-ico"></i>Our Rules</a></li>
                                <li><a href="<?php echo base_url('discipline'); ?>"><i class="fa fa-balance-scale dd-ico"></i>Discipline</a></li>
                                <li><a href="<?php echo base_url('fee_regulations'); ?>"><i class="fa fa-inr dd-ico"></i>Fee Regulations</a></li>
                                <li><a href="<?php echo base_url('admissions'); ?>"><i class="fa fa-pencil-square-o dd-ico"></i>Admissions</a></li>
                                <li><a href="<?php echo base_url('school_uniform'); ?>"><i class="fa fa-shopping-bag dd-ico"></i>Uniform</a></li>
                                <li><a href="<?php echo base_url('parental_support'); ?>"><i class="fa fa-heart-o dd-ico"></i>A Word to Parents</a></li>
                                <li><a href="<?php echo base_url('downloads'); ?>"><i class="fa fa-download dd-ico"></i>Downloads</a></li>
                            </ul>
                        </li>

                        <!-- ACTIVITIES -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Activities <i class="fa fa-caret-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('house_system'); ?>"><i class="fa fa-flag dd-ico"></i>House System</a></li>
                                <li><a href="<?php echo base_url('co_curricular_activities'); ?>"><i class="fa fa-paint-brush dd-ico"></i>Co-Curricular Activities</a></li>
                                <li><a href="<?php echo base_url('sports_and_games'); ?>"><i class="fa fa-futbol-o dd-ico"></i>Sports &amp; Games</a></li>
                                <li><a href="<?php echo base_url('clubs'); ?>"><i class="fa fa-users dd-ico"></i>Clubs</a></li>
                            </ul>
                        </li>

                        <li><a href="<?php echo base_url('gallery'); ?>">Gallery</a></li>
                        <li><a href="<?php echo base_url('vaccancy'); ?>">Vacancies</a></li>
                        <li><a href="<?php echo base_url('contact'); ?>">Contact Us</a></li>

                        <li class="lh-nav-login">
                            <button
                                type="button"
                                class="lh-login-btn"
                                onclick="window.location.href='<?php echo base_url('school_admin_project'); ?>';"
                                style="background: #3F51B5 !important; color: #fff !important; border: none; cursor: pointer; border-radius: 20px;">
                                <i class="fa fa-user"></i> Login
                            </button>
                        </li>

                        <li class="lh-nav-cta">
                            <button
                                type="button"
                                class="lh-apply-btn"
                                onclick="window.location.href='<?php echo base_url('admissions_enqry'); ?>';"
                                style="padding: 11px !important; background: #df3e3e !important; color: #fff !important; border: none; cursor: pointer;">
                                <i class="fa fa-graduation-cap"></i> Apply Now
                            </button>
                        </li>

                    </ul>
                </div>

            </div>
        </nav>
    </header>


<style>
    .logo-brand img {
        width: 131px;
        height: max-content;
        object-fit: contain;
        filter: inherit;
        transition: .3s ease;
    }

    /* =====================================================
       TOP INFO BAR — contact + CBSE affiliation + social
    ===================================================== */
    .lh-topbar {
        background: linear-gradient(90deg, #081a34, #0b2447);
        color: #cfd8e6;
        font-family: 'Nunito Sans', -apple-system, 'Segoe UI', sans-serif;
        font-size: 12.5px;
    }

    .lh-topbar-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 8px 15px;
        flex-wrap: wrap;
    }

    .lh-topbar-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
    .lh-topbar-left span { white-space: nowrap; }
    .lh-topbar-left i { color: #f5a623; margin-right: 6px; }
    .lh-topbar-left a { color: #cfd8e6; text-decoration: none; }
    .lh-topbar-left a:hover { color: #f5a623; }

    .lh-topbar-right { display: flex; align-items: center; gap: 14px; }
    .lh-topbar-right a { color: #cfd8e6; text-decoration: none; font-size: 13px; }
    .lh-topbar-right a:hover { color: #f5a623; }

    .lh-affiliation {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 auto;
        padding: 4px 16px;
        border-left: 1px solid rgba(255,255,255,.14);
        border-right: 1px solid rgba(255,255,255,.14);
        text-align: center;
    }
    .lh-affiliation i { color: #f5a623; font-size: 13px; }
    .lh-affiliation span {
        font-weight: 700;
        letter-spacing: .03em;
        font-size: 11.5px;
        text-transform: uppercase;
        color: #eef2f8;
    }

    @media (max-width: 1100px) {
        .lh-affiliation { display: none; }
    }
    @media (max-width: 767px) {
        .lh-topbar-grid { justify-content: center; text-align: center; }
        .lh-topbar-left { justify-content: center; }
    }
</style>

<!-- ===== MOBILE DROPDOWN FIX: one open at a time, tap again to close ===== -->
<script>
(function () {
    var MOBILE_MAX = 991;

    // remove anything Bootsnav / animate.css / jQuery may have left on a menu
    function resetMenu(menu) {
        if (!menu) return;
        var props = ['display', 'opacity', 'visibility', 'transform', 'height', 'max-height', 'animation'];
        for (var p = 0; p < props.length; p++) menu.style.removeProperty(props[p]);
        menu.classList.remove('animated', 'fadeIn', 'fadeOut', 'fadeInDown', 'fadeOutUp', 'fadeInUp');
    }

    function closeAll(except) {
        var items = document.querySelectorAll('.navbar-nav > li.dropdown');
        for (var i = 0; i < items.length; i++) {
            if (items[i] === except) continue;
            items[i].classList.remove('open', 'on');
            resetMenu(items[i].querySelector('.dropdown-menu'));
            var link = items[i].querySelector('.dropdown-toggle');
            if (link) link.setAttribute('aria-expanded', 'false');
        }
    }

    // Capture phase on document: runs BEFORE Bootstrap/Bootsnav handlers,
    // and stopImmediatePropagation stops them from double-toggling.
    document.addEventListener('click', function (e) {
        if (window.innerWidth > MOBILE_MAX) return;            // desktop keeps hover behaviour

        var toggle = e.target.closest('.navbar-nav > li.dropdown > a.dropdown-toggle');

        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var li = toggle.parentElement;
            var willOpen = !li.classList.contains('open');

            closeAll(li);                                      // close every other dropdown

            li.classList.remove('on');
            resetMenu(li.querySelector('.dropdown-menu'));

            li.classList.toggle('open', willOpen);             // open this one / close if already open
            toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            return;
        }

        // tapping anywhere outside the nav closes any open dropdown
        if (!e.target.closest('.navbar-nav')) closeAll(null);
    }, true);

    // reset state when switching between mobile and desktop widths
    window.addEventListener('resize', function () {
        if (window.innerWidth > MOBILE_MAX) closeAll(null);
    });
})();
</script>

    <!--Header ends-->


    <script>
(function () {
    var wrap = document.querySelector('.news-ticker-wrap');
    if (!wrap) return;
    var list  = wrap.querySelector('.news-ticker-list');
    var speed = parseInt(wrap.getAttribute('data-speed'), 10) || 60;   // px per second

    function setSpeed() {
        var distance = list.scrollWidth;                 // full travel distance
        list.style.setProperty('--ticker-duration', (distance / speed) + 's');
    }
    setSpeed();
    window.addEventListener('load', setSpeed);
    window.addEventListener('resize', setSpeed);
})();
</script>