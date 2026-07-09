<!--Header-->

<?php
            $sql = "SELECT c_news FROM school_news WHERE c_status='Y'";
            $query = $this->db->query($sql);
            $result = $query->result();

           
            ?>
<header>
    <!-- Scrolling News Ticker -->
    <div class="news-ticker-wrap">
        <div class="news-ticker-label">
            <i class="fa fa-bullhorn"></i> <span>Latest News</span>
        </div>
        <div class="news-ticker-track">
            <ul class="news-ticker-list">
                <!-- <li>🎉 Admissions for the new academic session are now open — apply early to secure your seat!</li>
                <li>🏆 Little Hearts students win 1st prize at the Inter-School Science Exhibition.</li>
                <li>📚 Annual Sports Day will be held next month — details coming soon.</li>
                <li>📢 Parent-Teacher Meeting scheduled this Saturday. Kindly check the notice board.</li>
                <li>🎉 Admissions for the new academic session are now open — apply early to secure your seat!</li>
                <li>🏆 Little Hearts students win 1st prize at the Inter-School Science Exhibition.</li>
                <li>📚 Annual Sports Day will be held next month — details coming soon.</li>
                <li>📢 Parent-Teacher Meeting scheduled this Saturday. Kindly check the notice board.</li> -->
            <?php foreach($result as $row){  ?>
                <li><?php echo $row->c_news; ?></li>
            <?php  }?>

            </ul>
        </div>
    </div>

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

                    <span class="school-name">LITTLE HEARTS</span>
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
   NEWS TICKER
========================= */
    .news-ticker-wrap {
        display: flex;
        align-items: stretch;
        background: linear-gradient(90deg, #0654c2, #0a7ea4);
        height: 38px;
        overflow: hidden;
        position: relative;
        z-index: 1001;
        box-shadow: 0 2px 10px rgba(6, 84, 194, 0.25);
    }

    .news-ticker-label {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #023e8a;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 0 16px;
        white-space: nowrap;
        clip-path: polygon(0 0, 100% 0, 88% 100%, 0% 100%);
        padding-right: 26px;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .news-ticker-label i {
        color: #ffd166;
        animation: bell-ring 2.2s infinite;
    }

    @keyframes bell-ring {

        0%,
        100% {
            transform: rotate(0deg);
        }

        10% {
            transform: rotate(-15deg);
        }

        20% {
            transform: rotate(15deg);
        }

        30% {
            transform: rotate(-10deg);
        }

        40% {
            transform: rotate(10deg);
        }

        50% {
            transform: rotate(0deg);
        }
    }

    .news-ticker-track {
        flex: 1;
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
    }

    .news-ticker-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        white-space: nowrap;
        animation: ticker-scroll 28s linear infinite;
        will-change: transform;
    }

    .news-ticker-list li {
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 0 40px;
        position: relative;
    }

    .news-ticker-list li::after {
        content: "•";
        position: absolute;
        right: 14px;
        color: #ffd166;
    }

    .news-ticker-wrap:hover .news-ticker-list {
        animation-play-state: paused;
    }

    @keyframes ticker-scroll {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    @media (max-width: 767px) {
        .news-ticker-label span {
            display: none;
        }

        .news-ticker-label {
            padding: 0 10px 0 12px;
        }

        .news-ticker-list li {
            font-size: 11.5px;
            padding: 0 24px;
        }
    }

    /* =========================
   COMPACT HEADER FIX + GLASS EFFECT
========================= */

    /* HEADER BACKGROUND - GLASSY */
    .navbar {
        background: rgba(255, 255, 255, 0.55) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 8px 32px rgba(6, 84, 194, 0.12);
        transition: background 0.35s ease, box-shadow 0.35s ease;
    }

    .navbar:hover {
        background: rgba(255, 255, 255, 0.7) !important;
    }

    /* SCHOOL NAME */
    .school-name {
        color: #0654c2 !important;
    }

    /* MENU LINKS */
    .navbar-nav>li>a {
        color: #333 !important;
    }

    /* MENU HOVER */
    .navbar-nav>li>a:hover,
    .navbar-nav>.active>a {
        color: #0654c2 !important;
        background: none !important;
    }

    /* DROPDOWN - GLASSY */
    .dropdown-menu {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    /* DROPDOWN HOVER */
    .dropdown-menu li a:hover {
        background: rgba(230, 255, 251, 0.8) !important;
        color: #0654c2 !important;
    }

    /* MOBILE MENU BUTTON */
    .navbar-toggle {
        background: rgba(230, 255, 251, 0.6) !important;
        backdrop-filter: blur(6px);
    }

    .navbar-toggle i {
        color: #0654c2 !important;
    }

    /* NAVBAR */
    .navbar {
        min-height: 62px !important;
        border: none !important;
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
        filter: drop-shadow(0 2px 6px rgba(6, 84, 194, 0.25));
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
        white-space: nowrap;
        transition: 0.3s;
    }

    /* DROPDOWN */
    .dropdown-menu {
        border-radius: 14px !important;
        margin-top: 5px !important;
        padding: 8px 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    /* DROPDOWN LINKS */
    .dropdown-menu li a {
        padding: 10px 18px !important;
        font-size: 12px;
        color: #444 !important;
    }

    /* MOBILE BUTTON */
    .navbar-toggle {
        margin-top: 12px;
        border: none !important;
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            margin-top: 8px;
        }

        .navbar-nav {
            display: block;
        }

        .navbar-nav>li>a {
            padding: 12px 18px !important;
            font-size: 13px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu {
            box-shadow: none;
            border-radius: 0 !important;
            background: rgba(240, 253, 250, 0.9) !important;
        }

        .dropdown-menu li a {
            padding: 10px 28px !important;
        }
    }




    /* //// */


    /* ===== BEAUTIFUL DROPDOWN ITEM SELECTION ===== */
    .dropdown-menu li {
        position: relative;
        overflow: hidden;
    }

    .dropdown-menu li a {
        position: relative;
        display: block;
        z-index: 1;
        transition: color 0.3s ease, padding-left 0.3s ease;
    }

    /* Sliding gradient accent bar on the left */
    .dropdown-menu li::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #0654c2, #0a7ea4);
        transform: scaleY(0);
        transition: transform 0.3s ease;
        z-index: 0;
    }

    /* Soft glowing fill that sweeps in from the left */
    .dropdown-menu li::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(6, 84, 194, 0.12), rgba(10, 126, 164, 0.05));
        transform: translateX(-100%);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        z-index: 0;
    }

    .dropdown-menu li:hover::before {
        transform: scaleY(1);
    }

    .dropdown-menu li:hover::after {
        transform: translateX(0);
    }

    .dropdown-menu li a:hover {
        color: #0654c2 !important;
        padding-left: 26px !important;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* Arrow indicator that slides in on hover */
    .dropdown-menu li a::before {
        content: "\203A";
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translate(-8px, -50%);
        opacity: 0;
        color: #0a7ea4;
        font-weight: 900;
        font-size: 15px;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .dropdown-menu li a:hover::before {
        transform: translate(0, -50%);
        opacity: 1;
    }

    /* Active / currently selected dropdown item (e.g. current page) */
    .dropdown-menu li.active a {
        color: #fff !important;
        background: linear-gradient(90deg, #0654c2, #0a7ea4) !important;
        padding-left: 26px !important;
        font-weight: 700;
    }

    .dropdown-menu li.active::before {
        transform: scaleY(1);
        background: #ffd166;
    }

    .dropdown-menu li.active a::before {
        content: "\2713";
        color: #fff;
        transform: translate(0, -50%);
        opacity: 1;
        font-size: 12px;
    }
</style>