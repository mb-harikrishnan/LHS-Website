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
                    <img src="<?php echo base_url('assets/images/main_image/logonew.png') ?>"
                        class="logo logo-scrolled"
                        alt="School Logo">
                    <span class="school-name">Little Hearts School<small>Learn &middot; Grow &middot; Succeed</small></span>
                </a>
            </div>

            <a href="<?php echo base_url('admissions'); ?>" class="btn-nav-apply">Apply Now <i class="fa fa-arrow-right"></i></a>

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
    :root{
        --navy:#0b2447;
        --navy-deep:#081a34;
        --gold:#f5a623;
        --gold-deep:#e2911a;
        --ink:#1c2530;
        --muted:#5b6572;
        --font-head:'Fraunces', Georgia, serif;
        --font-body:'Plus Jakarta Sans', -apple-system, 'Segoe UI', sans-serif;
    }

    /* =========================
       NEWS TICKER
    ========================= */
    .news-ticker-wrap {
        display: flex;
        align-items: stretch;
        background: linear-gradient(90deg, var(--navy-deep), var(--navy));
        height: 38px;
        overflow: hidden;
        position: relative;
        z-index: 1001;
        box-shadow: 0 2px 10px rgba(8, 26, 52, 0.35);
    }

    .news-ticker-label {
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--gold);
        color: var(--navy-deep);
        font-family: var(--font-body);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 0 16px;
        white-space: nowrap;
        clip-path: polygon(0 0, 100% 0, 88% 100%, 0% 100%);
        padding-right: 26px;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .news-ticker-label i { animation: bell-ring 2.2s infinite; }

    @keyframes bell-ring {
        0%, 100% { transform: rotate(0deg); }
        10% { transform: rotate(-15deg); }
        20% { transform: rotate(15deg); }
        30% { transform: rotate(-10deg); }
        40% { transform: rotate(10deg); }
        50% { transform: rotate(0deg); }
    }

    .news-ticker-track { flex: 1; overflow: hidden; position: relative; display: flex; align-items: center; }

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
        font-family: var(--font-body);
        font-size: 13px;
        font-weight: 500;
        padding: 0 40px;
        position: relative;
    }

    .news-ticker-list li::after { content: "•"; position: absolute; right: 14px; color: var(--gold); }
    .news-ticker-wrap:hover .news-ticker-list { animation-play-state: paused; }

    @keyframes ticker-scroll {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }

    @media (max-width: 767px) {
        .news-ticker-label span { display: none; }
        .news-ticker-label { padding: 0 10px 0 12px; }
        .news-ticker-list li { font-size: 11.5px; padding: 0 24px; }
    }

    /* =========================
       HEADER / NAVBAR
    ========================= */
    .navbar {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid rgba(11, 36, 71, 0.08) !important;
        box-shadow: 0 8px 28px rgba(11, 36, 71, 0.1);
        transition: background .35s ease, box-shadow .35s ease;
        min-height: 68px !important;
        border: none !important;
    }

    .navbar .container { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 18px; }

    .logo-brand { display: flex !important; align-items: center; gap: 12px; height: 68px; padding: 0 !important; }
    .logo-brand img { width: 42px; height: 42px; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(11,36,71,.2)); }
    .school-name {
        font-family: var(--font-head);
        font-size: 18px;
        font-weight: 700;
        color: var(--navy) !important;
        white-space: nowrap;
        line-height: 1.2;
        display: flex;
        flex-direction: column;
    }
    .school-name small { font-family: var(--font-body); font-weight: 600; font-size: 10.5px; letter-spacing: .06em; color: var(--gold-deep); }

    .btn-nav-apply {
        background: var(--gold);
        color: var(--navy-deep) !important;
        font-family: var(--font-body);
        font-weight: 700;
        font-size: 13px;
        padding: 10px 22px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 10px 22px rgba(245,166,35,.35);
        transition: transform .25s ease;
        margin-left: auto;
    }
    .btn-nav-apply:hover { transform: translateY(-2px); color: var(--navy-deep); }

    #navbar-menu { flex: 1; display: flex !important; justify-content: flex-end; }
    .navbar-nav { display: flex; align-items: center; flex-wrap: nowrap; margin: 0; }

    .navbar-nav > li > a {
        font-family: var(--font-body);
        color: var(--ink) !important;
        padding: 24px 12px !important;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        transition: .3s;
    }
    .navbar-nav > li > a:hover,
    .navbar-nav > .active > a { color: var(--navy) !important; background: none !important; }

    .dropdown-menu {
        background: rgba(255,255,255,.97) !important;
        border: 1px solid rgba(11,36,71,.08) !important;
        border-radius: 14px !important;
        margin-top: 6px !important;
        padding: 8px 0 !important;
        box-shadow: 0 16px 36px rgba(11,36,71,.18);
    }
    .dropdown-menu li a { padding: 10px 20px !important; font-size: 13px; color: var(--ink) !important; font-family: var(--font-body); }
    .dropdown-menu li a:hover { background: rgba(245,166,35,.1) !important; color: var(--navy) !important; }

    .navbar-toggle { background: rgba(11,36,71,.06) !important; border: none !important; border-radius: 8px; margin-top: 14px; }
    .navbar-toggle i { color: var(--navy) !important; }

    /* dropdown accent + active state */
    .dropdown-menu li { position: relative; overflow: hidden; }
    .dropdown-menu li a { position: relative; display: block; z-index: 1; transition: color .3s ease, padding-left .3s ease; }
    .dropdown-menu li::before {
        content: ""; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
        background: linear-gradient(180deg, var(--navy), var(--gold)); transform: scaleY(0); transition: transform .3s ease; z-index: 0;
    }
    .dropdown-menu li:hover::before { transform: scaleY(1); }
    .dropdown-menu li a:hover { padding-left: 26px !important; font-weight: 700; }
    .dropdown-menu li.active a { color: #fff !important; background: linear-gradient(90deg, var(--navy), var(--navy-deep)) !important; padding-left: 26px !important; font-weight: 700; }
    .dropdown-menu li.active::before { transform: scaleY(1); background: var(--gold); }

    @media (max-width: 991px) {
        .navbar .container { flex-wrap: wrap; }
        .navbar-header { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 15px; }
        .logo-brand { height: auto; }
        .logo-brand img { width: 34px; height: 34px; }
        .school-name { font-size: 14.5px; }
        .btn-nav-apply { display: none; }
        #navbar-menu { display: block !important; width: 100%; background: rgba(255,255,255,.97); }
        .navbar-nav { display: block; }
        .navbar-nav > li > a { padding: 13px 18px !important; border-bottom: 1px solid rgba(11,36,71,.06); }
        .dropdown-menu { box-shadow: none; border-radius: 0 !important; background: rgba(246,248,251,.95) !important; }
    }
</style>