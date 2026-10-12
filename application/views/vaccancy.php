<?php $page_title = 'Careers'; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <title>Little Hearts | Careers</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/edua-icons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/animate.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.carousel.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.transitions.css') ?> ">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/cubeportfolio.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/settings.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootsnav.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/loader.css') ?>">
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           LITTLE HEARTS — CAREERS PAGE (attractive & user friendly)
           ============================================================ */
        :root {
            --v-navy: #0b2447;
            --v-navy2: #123a6b;
            --v-gold: #f5a623;
            --v-gold2: #e2911a;
            --v-ink: #22314a;
            --v-muted: #5b6b84;
            --v-paper: #f6f9ff;
            --v-line: #e3ecf7;
            --v-r: 22px;
            --v-sh: 0 16px 36px rgba(11, 36, 71, .10);
        }

        body { font-family: 'Nunito Sans', sans-serif; color: var(--v-ink); background: var(--v-paper); }

        /* ---------- HERO ---------- */
        .vac-hero {
            position: relative;
            background:
                radial-gradient(circle at 88% 18%, rgba(245, 166, 35, .16) 0, transparent 36%),
                radial-gradient(circle at 8% 85%, rgba(47, 111, 237, .18) 0, transparent 40%),
                linear-gradient(120deg, #081a34 0%, var(--v-navy) 55%, #143a70 100%);
            padding: 86px 0 120px;
            color: #fff;
            overflow: hidden;
        }
        .vac-hero .doodle {
            position: absolute; border-radius: 50%;
            border: 2px dashed rgba(245, 166, 35, .35);
            animation: vacFloat 8s ease-in-out infinite;
        }
        .vac-hero .doodle.d1 { width: 150px; height: 150px; top: 60px; right: 12%; }
        .vac-hero .doodle.d2 { width: 90px; height: 90px; bottom: 60px; right: 30%; animation-delay: -3s; }
        .vac-hero .doodle.d3 { width: 60px; height: 60px; top: 120px; left: 8%; border-color: rgba(255,255,255,.18); }
        @keyframes vacFloat { 0%,100% { transform: translateY(0) } 50% { transform: translateY(-18px) } }

        .vac-hero-inner { position: relative; z-index: 2; text-align: center; max-width: 780px; margin: 0 auto; }
        .vac-hero-badge {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(245, 166, 35, .15);
            border: 1px solid rgba(245, 166, 35, .45);
            color: var(--v-gold);
            font-weight: 800; font-size: 12.5px; letter-spacing: 1.4px; text-transform: uppercase;
            padding: 9px 22px; border-radius: 50px; margin-bottom: 22px;
        }
        .vac-hero h1 {
            font-family: 'Fredoka', cursive;
            font-size: 46px; font-weight: 700; line-height: 1.2; margin: 0 0 16px;
        }
        .vac-hero h1 .accent { color: var(--v-gold); }
        .vac-hero p { color: rgba(255,255,255,.8); font-size: 16.5px; line-height: 1.85; max-width: 640px; margin: 0 auto 30px; }
        .vac-hero-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

        .btn-vac-gold, .btn-vac-ghost {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 30px; border-radius: 50px;
            font-weight: 800; font-size: 14.5px; text-decoration: none !important;
            transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s ease;
        }
        .btn-vac-gold { background: var(--v-gold); color: var(--v-navy) !important; box-shadow: 0 12px 26px rgba(245,166,35,.4); }
        .btn-vac-gold:hover { transform: translateY(-3px); box-shadow: 0 18px 34px rgba(245,166,35,.5); color: var(--v-navy) !important; }
        .btn-vac-ghost { border: 2px solid rgba(255,255,255,.5); color: #fff !important; }
        .btn-vac-ghost:hover { background: rgba(255,255,255,.12); color: #fff !important; transform: translateY(-3px); }

        /* ---------- PERK CARDS overlapping hero ---------- */
        .vac-perks { margin-top: -70px; position: relative; z-index: 5; }
        .perk-card {
            background: #fff; border-radius: var(--v-r);
            box-shadow: var(--v-sh);
            padding: 28px 22px;
            text-align: center;
            height: 100%;
            border: 2px solid var(--v-line);
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease;
        }
        .perk-card:hover { transform: translateY(-8px) rotate(-.5deg); box-shadow: 0 26px 50px rgba(11,36,71,.18); }
        .perk-icon {
            width: 62px; height: 62px; margin: 0 auto 16px;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff;
        }
        .perk-icon.p-blue { background: linear-gradient(135deg,#2f6fed,#1d4ed8); box-shadow: 0 10px 20px rgba(47,111,237,.35); }
        .perk-icon.p-gold { background: linear-gradient(135deg,var(--v-gold),var(--v-gold2)); box-shadow: 0 10px 20px rgba(245,166,35,.35); }
        .perk-icon.p-green { background: linear-gradient(135deg,#2fb380,#178357); box-shadow: 0 10px 20px rgba(47,179,128,.35); }
        .perk-icon.p-purple { background: linear-gradient(135deg,#8e6fe8,#6d4fd8); box-shadow: 0 10px 20px rgba(142,111,232,.35); }
        .perk-card h4 { font-family: 'Fredoka', cursive; font-size: 17px; color: var(--v-navy); margin-bottom: 8px; }
        .perk-card p { font-size: 13.5px; color: var(--v-muted); margin: 0; line-height: 1.75; }

        /* ---------- SECTION HEADS ---------- */
        .vac-section { padding: 80px 0; }
        .vac-eyebrow {
            display: inline-flex; align-items: center; gap: 9px;
            color: var(--v-gold2); font-weight: 800; font-size: 13px;
            letter-spacing: 1.2px; text-transform: uppercase;
        }
        .vac-eyebrow .dash { width: 24px; height: 2px; background: currentColor; }
        .vac-eyebrow::before { content: "✏"; font-size: 13px; }
        .vac-title {
            font-family: 'Fredoka', cursive; font-size: 34px; font-weight: 700;
            color: var(--v-navy); margin: 8px 0 0;
            position: relative; display: inline-block;
        }
        .vac-title::after {
            content: ""; position: absolute; left: 50%; transform: translateX(-50%) rotate(-.6deg);
            bottom: -4px; width: 110%; height: 12px; border-radius: 6px; z-index: -1;
            background: linear-gradient(90deg, rgba(47,111,237,.22), rgba(245,166,35,.3));
        }

        /* ---------- JOB CARDS ---------- */
        .job-card {
            background: #fff;
            border: 2px solid var(--v-line);
            border-radius: var(--v-r);
            box-shadow: var(--v-sh);
            padding: 34px 36px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease;
        }
        .job-card::before {
            content: "WE'RE HIRING";
            position: absolute; top: 22px; right: -34px;
            transform: rotate(38deg);
            background: linear-gradient(135deg, var(--v-gold), var(--v-gold2));
            color: var(--v-navy);
            font-size: 10.5px; font-weight: 900; letter-spacing: 1.5px;
            padding: 6px 44px;
            box-shadow: 0 6px 14px rgba(245,166,35,.35);
        }
        .job-card:hover { transform: translateY(-8px); box-shadow: 0 26px 50px rgba(11,36,71,.18); border-color: rgba(47,111,237,.45); }

        .job-head { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .job-briefcase {
            width: 64px; height: 64px; flex: none;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--v-navy), #2f6fed);
            color: var(--v-gold);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            box-shadow: 0 12px 24px rgba(11,36,71,.3);
        }
        .job-head h3 {
            font-family: 'Fredoka', cursive;
            font-size: 25px; font-weight: 700; color: var(--v-navy);
            margin: 0 0 6px; line-height: 1.3; word-break: break-word;
        }
        .job-meta { display: flex; gap: 16px; flex-wrap: wrap; }
        .job-meta span {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 700; color: var(--v-muted);
        }
        .job-meta i { color: var(--v-gold2); }

        .job-desc {
            font-size: 15px; line-height: 1.95; color: var(--v-muted);
            margin-bottom: 26px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;
        }

        .btn-apply {
            display: inline-flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--v-navy), #2f6fed);
            color: #fff !important;
            padding: 14px 34px; border-radius: 50px;
            font-weight: 800; font-size: 14.5px; text-decoration: none !important;
            box-shadow: 0 8px 0 rgba(11,36,71,.35);
            transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s ease;
        }
        .btn-apply:hover { transform: translateY(-3px); box-shadow: 0 11px 0 rgba(11,36,71,.35); color: #fff !important; }

        /* ---------- EMPTY STATE ---------- */
        .vac-empty {
            background: #fff; border: 2px dashed var(--v-line);
            border-radius: 26px; text-align: center;
            padding: 70px 30px; max-width: 620px; margin: 30px auto 0;
            position: relative;
        }
        .vac-empty .empty-icon {
            width: 96px; height: 96px; margin: 0 auto 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(245,166,35,.15), rgba(245,166,35,.3));
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: var(--v-gold2);
        }
        .vac-empty h2 { font-family: 'Fredoka', cursive; font-size: 30px; color: var(--v-navy); margin-bottom: 8px; }
        .vac-empty .tagline { color: var(--v-muted); font-size: 15.5px; margin-bottom: 24px; }
        .vac-empty h4 { font-family: 'Fredoka', cursive; font-size: 20px; color: var(--v-navy); margin-bottom: 10px; }
        .vac-empty p { color: var(--v-muted); font-size: 14.5px; line-height: 1.85; max-width: 440px; margin: 0 auto 26px; }

        /* ---------- JOIN CTA BAND ---------- */
        .join-band {
            position: relative;
            background:
                radial-gradient(circle at 85% 30%, rgba(245,166,35,.18) 0, transparent 40%),
                linear-gradient(120deg, #081a34, var(--v-navy));
            border-radius: 26px;
            padding: 50px 54px;
            color: #fff;
            overflow: hidden;
            display: flex; align-items: center; justify-content: space-between; gap: 30px; flex-wrap: wrap;
        }
        .join-band::before {
            content: ""; position: absolute; right: -70px; top: -70px;
            width: 220px; height: 220px; border-radius: 50%;
            border: 2px dashed rgba(245,166,35,.3);
        }
        .join-band h3 { font-family: 'Fredoka', cursive; font-size: 28px; margin-bottom: 10px; position: relative; z-index: 1; }
        .join-band p { color: rgba(255,255,255,.78); margin: 0; font-size: 15px; line-height: 1.85; position: relative; z-index: 1; }
        .join-band strong { color: var(--v-gold); }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 991px) {
            .vac-hero h1 { font-size: 36px; }
            .join-band { text-align: center; justify-content: center; }
        }
        @media (max-width: 767px) {
            .vac-hero { padding: 60px 0 100px; }
            .vac-hero h1 { font-size: 29px; }
            .job-card { padding: 26px 20px; }
            .job-card::before { display: none; }
            .job-head h3 { font-size: 21px; }
            .btn-apply { width: 100%; justify-content: center; }
            .vac-section { padding: 56px 0; }
            .join-band { padding: 36px 24px; }
        }
    </style>
</head>

<body>

<!-- ============ HERO ============ -->
<section class="vac-hero">
    <span class="doodle d1"></span>
    <span class="doodle d2"></span>
    <span class="doodle d3"></span>

    <div class="container">
        <div class="vac-hero-inner">
            <span class="vac-hero-badge"><i class="fa fa-graduation-cap"></i> Careers at Little Hearts</span>
            <h1>Shape Young Minds.<br>Build <span class="accent">Bright Futures.</span></h1>
            <p>
                Join our passionate educational community. At Little Hearts School, teachers grow,
                learners flourish, and every day brings the joy of shaping tomorrow.
            </p>
            <div class="vac-hero-actions">
                <a href="#openings" class="btn-vac-gold">View Openings <i class="fa fa-arrow-down"></i></a>
                <a href="<?php echo base_url('contact'); ?>" class="btn-vac-ghost">Contact School <i class="fa fa-phone"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- ============ WHY WORK WITH US ============ -->
<section class="vac-perks">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
                <div class="perk-card wow fadeInUp">
                    <div class="perk-icon p-blue"><i class="fa fa-users"></i></div>
                    <h4>Supportive Team</h4>
                    <p>Collaborative colleagues who share ideas and celebrate wins together.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
                <div class="perk-card wow fadeInUp">
                    <div class="perk-icon p-gold"><i class="fa fa-graduation-cap"></i></div>
                    <h4>Growth &amp; Training</h4>
                    <p>Regular workshops and NEP-aligned professional development.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
                <div class="perk-card wow fadeInUp">
                    <div class="perk-icon p-green"><i class="fa fa-leaf"></i></div>
                    <h4>Green Campus</h4>
                    <p>Work in a scenic, calm campus surrounded by nature.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom:20px;">
                <div class="perk-card wow fadeInUp">
                    <div class="perk-icon p-purple"><i class="fa fa-heart"></i></div>
                    <h4>Meaningful Work</h4>
                    <p>Every lesson you teach shapes a child's future.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ OPENINGS ============ -->
<section class="vac-section" id="openings">
    <div class="container">

        <div style="text-align:center; margin-bottom:44px;">
            <span class="vac-eyebrow"><span class="dash"></span> Current Openings</span>
            <br>
            <h2 class="vac-title">We're Looking For People Like You</h2>
        </div>

        <?php if (empty($all_vacancy)) { ?>

            <!-- EMPTY STATE -->
            <div class="vac-empty wow fadeInUp">
                <div class="empty-icon"><i class="fa fa-briefcase"></i></div>
                <h2>No Openings Right Now</h2>
                <p class="tagline">All positions are currently filled — but we're always happy to meet great educators.</p>
                <h4>Check Back Soon</h4>
                <p>
                    New vacancies are posted here as soon as they open. Meanwhile, feel free to
                    explore our school or get in touch with the office.
                </p>
                <a href="<?php echo base_url('contact'); ?>" class="btn-apply" style="box-shadow:0 8px 0 rgba(47,111,237,.35);">
                    <i class="fa fa-envelope"></i> Get in Touch
                </a>
            </div>

        <?php } else { ?>

            <div class="vacancy_wrapper">

                <?php foreach ($all_vacancy as $value) { ?>

                    <div class="job-card wow fadeInUp">

                        <div class="job-head">
                            <div class="job-briefcase"><i class="fa fa-briefcase"></i></div>
                            <div>
                                <h3><?php echo $value->c_title; ?></h3>
                                <div class="job-meta">
                                    <span><i class="fa fa-map-marker"></i> Kizhakkepram, N. Paravur</span>
                                    <span><i class="fa fa-clock-o"></i> Full Time</span>
                                    <span><i class="fa fa-graduation-cap"></i> As per CBSE norms</span>
                                </div>
                            </div>
                        </div>

                        <div class="job-desc">
                            <?php echo nl2br($value->c_description); ?>
                        </div>

                        <a href="<?php echo base_url('apply_job/' . $value->n_slno); ?>" class="btn-apply">
                            Apply Now <i class="fa fa-arrow-right"></i>
                        </a>

                    </div>

                <?php } ?>

            </div>

            <!-- JOIN CTA -->
            <div class="join-band wow fadeInUp">
                <div>
                    <h3>Don't see your role?</h3>
                    <p>Send your resume to <strong>littleheartsschool97@gmail.com</strong> — we keep great people in mind.</p>
                </div>
                <a href="mailto:littleheartsschool97@gmail.com" class="btn-vac-gold"><i class="fa fa-envelope"></i> Email Your Resume</a>
            </div>

        <?php } ?>

    </div>
</section>

<!-- Scripts -->
<script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
<script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>
<script src="<?php echo JS_PATH ?>jquery.dataTables.min.js"></script>
<script src="<?php echo JS_PATH ?>dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?php echo base_url("assets/js/wow.min.js") ?>"></script>
<script>if (typeof WOW === "function") { new WOW({ offset: 60, mobile: true, live: true }).init(); }</script>
</body>
</html>
