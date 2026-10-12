<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/edua-icons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/owl.transitions.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cubeportfolio.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/settings.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootsnav.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loader.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/glass-theme.css">

    <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.png">

    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>





<!--Search-->
<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="Search here...." required />
        <button type="submit" class="btn btn_common blue">Search</button>
    </form>
</div>

<!-- ============ HERO ============ -->
<section class="hero" id="hero">

    <div class="hero-slides">
        <?php $slide_i = 0; foreach ($slider as $value) { $slide_i++; ?>
            <div class="hero-slide <?php echo $slide_i === 1 ? 'is-active' : ''; ?>">

                <?php if ($value->c_upload_type == "image") { ?>
                    <img src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>" alt="" class="hero-media">
                <?php } ?>

                <?php if ($value->c_upload_type == "video") { ?>
                    <video autoplay muted loop playsinline class="hero-media">
                        <source src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>" type="video/mp4">
                    </video>
                <?php } ?>

                <?php if ($value->c_upload_type == "link") { ?>
                    <video autoplay muted loop playsinline class="hero-media">
                        <source src="<?php echo $value->c_file; ?>" type="video/webm">
                    </video>
                <?php } ?>

            </div>
        <?php } ?>
        <div class="hero-overlay"></div>
    </div>

    <div class="lh-container hero-content">
        <span class="eyebrow eyebrow-light"><span class="dash"></span> Welcome to Little Hearts School</span>
        <h1 class="hero-title">
            Inspiring Young Minds.<br>
            Building <span class="accent">Bright Futures.</span>
        </h1>
        <p class="hero-copy">
            At Little Hearts School, we nurture every child with quality education, strong values
            and a caring environment to help them become confident, compassionate and responsible
            global citizens.
        </p>
        <div class="hero-actions">
            <a href="<?php echo base_url('about_us'); ?>" class="btn-gold">Explore Our School <i class="fa fa-arrow-right"></i></a>
            <a href="<?php echo base_url('admissions'); ?>" class="btn-outline">Admission Enquiry <i class="fa fa-file-text-o"></i></a>
        </div>
    </div>

    <div class="hero-badge">
        <div class="hero-badge-row"><i class="fa fa-mortar-board"></i><div><strong>25+</strong><span>Years of Excellence</span></div></div>
        <div class="hero-badge-row"><i class="fa fa-users"></i><div><strong>1000+</strong><span>Students</span></div></div>
        <div class="hero-badge-row"><i class="fa fa-star"></i><div><strong>100%</strong><span>Commitment</span></div></div>
    </div>
</section>

<!-- ============ ABOUT ============ -->
<section class="section about-section" id="about">
    <div class="lh-container about-grid">

        <div class="about-copy">
            <span class="eyebrow"><span class="dash"></span> About Little Hearts</span>
            <h2 class="h2">Where Learning Meets Character</h2>
            <p>
                Little Hearts School, one of the most progressive schools of its kind in Paravur, was
                started in the year 1997 in a rented building. Now it is functioning in its own building
                at Kizhakkepram, surrounded by scenic natural beauty. Affiliated to CBSE, New Delhi since
                January 2006 (Affiliation No. 930601), the school was upgraded to Senior Secondary status
                with Science and Commerce streams in 2008.
            </p>
            <p>
                Our vision of education is the formation of the human being for the fulfillment of
                individual and social responsibilities &mdash; moulding leaders who champion justice, love,
                truth and peace, and who remain ever open to growth.
            </p>
            <a href="<?php echo base_url('about_us'); ?>" class="btn-dark">Discover Our Story <i class="fa fa-arrow-right"></i></a>
        </div>

        <div class="about-media">
            <img src="<?php echo base_url(); ?>assets/images/main_image/little-hearts-school-paravur-ernakulam-schools-4c8g4k4.avif" alt="School campus" class="about-img-main">
            <img src="<?php echo base_url(); ?>assets/images/main_image/school_full_image.png" alt="School building" class="about-img-small">
            <div class="about-badge"><strong>25+</strong><span>Years of<br>Excellence</span></div>
        </div>

    </div>
</section>

<!-- ============ WHAT MAKES US SPECIAL ============ -->
<section class="section features-section">
    <div class="lh-container">

        <span class="eyebrow"><span class="dash"></span> Our Key Features</span>
        <h2 class="h2">What Makes Us Special</h2>

        <div class="features-grid">

            <div class="feature-hero">
                <img src="<?php echo base_url(); ?>assets/images/main_image/house.jpg" alt="Academic Excellence" class="feature-hero-img">
                <div class="feature-hero-overlay"></div>
                <div class="feature-hero-icon"><i class="fa fa-book"></i></div>
                <h3>Smart Classrooms</h3>
                <p>Interactive smart classes led by qualified educators, with personalised coaching so no child is left behind.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-blue"><i class="fa fa-lightbulb-o"></i></div>
                <h4>Kid&rsquo;s Fest</h4>
                <p>A platform for children to showcase creativity, skills and innovation.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-orange"><i class="fa fa-tree"></i></div>
                <h4>Children&rsquo;s Park</h4>
                <p>A serene outdoor space and playroom that builds physical and cognitive skills.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-purple"><i class="fa fa-bullhorn"></i></div>
                <h4>Morning Assemblies</h4>
                <p>Themed assemblies build communication, teamwork and self-confidence.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-rose"><i class="fa fa-shield"></i></div>
                <h4>Strict Security</h4>
                <p>Children are released only to authorised individuals with proper ID.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-green"><i class="fa fa-graduation-cap"></i></div>
                <h4>Faculty Excellence</h4>
                <p>Mentors and lifelong learners, trained through regular pedagogy workshops.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-blue"><i class="fa fa-book"></i></div>
                <h4>Library</h4>
                <p>A district-leading resource with age-appropriate reading for every grade.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-orange"><i class="fa fa-music"></i></div>
                <h4>Extracurriculars</h4>
                <p>Specialised coaching in dance, music and yoga for well-rounded growth.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon i-purple"><i class="fa fa-trophy"></i></div>
                <h4>Graduation Day</h4>
                <p>UKG graduates receive scrolls of honour, marking their step into formal schooling.</p>
            </div>

        </div>
    </div>
</section>

<!-- ============ WHY PARENTS CHOOSE (dark stats band) ============ -->
<section class="stats-band">
    <img src="<?php echo base_url(); ?>assets/images/main_image/sports_school.jpeg" alt="" class="stats-bg">
    <div class="stats-overlay"></div>
    <div class="lh-container stats-inner">
        <div class="stats-heading">
            <span class="eyebrow eyebrow-light"><span class="dash"></span> Why Parents Choose Us</span>
            <h2 class="h2 h2-light">Why Parents Choose<br>Little Hearts School</h2>
        </div>
        <div class="stats-grid">
            <div class="stat"><i class="fa fa-mortar-board"></i><strong data-count="25">0</strong>+<span>Years of Excellence</span></div>
            <div class="stat"><i class="fa fa-users"></i><strong data-count="1000">0</strong>+<span>Students</span></div>
            <div class="stat"><i class="fa fa-user"></i><strong data-count="186">0</strong>+<span>Qualified Staff</span></div>
            <div class="stat"><i class="fa fa-heart"></i><strong data-count="100">0</strong>%<span>Commitment to Learning</span></div>
        </div>
    </div>
</section>

<!-- ============ ACTIVITIES GALLERY ============ -->
<section class="section activities-section">
    <div class="lh-container">
        <span class="eyebrow"><span class="dash"></span> Our Activities</span>
        <h2 class="h2">Beyond Classrooms, Into Life</h2>

        <div class="gallery-grid">

            <a href="<?php echo base_url('sports_and_games'); ?>" class="gallery-tile tile-tall">
                <img src="<?php echo base_url(); ?>assets/images/main_image/sports_school.jpeg" alt="Sports and Games">
                <span class="tile-label">Annual Sports Day<small>Celebrating energy, teamwork &amp; achievement.</small></span>
            </a>

            <a href="<?php echo base_url('annual_day'); ?>" class="gallery-tile">
                <img src="<?php echo base_url(); ?>assets/images/main_image/anual_day.jpeg" alt="Annual Day">
                <span class="tile-label">Cultural Events<small>Showcasing talent and creativity.</small></span>
            </a>

            <a href="<?php echo base_url('co_curricular_activities'); ?>" class="gallery-tile">
                <img src="<?php echo base_url(); ?>assets/images/main_image/dance.png" alt="Co-Curricular Activities">
                <span class="tile-label">Arts &amp; Creativity<small>Explore, express, grow.</small></span>
            </a>

            <a href="<?php echo base_url('clubs'); ?>" class="gallery-tile">
                <img src="<?php echo base_url(); ?>assets/images/main_image/ClubsActivities.jpg" alt="Clubs">
                <span class="tile-label">Clubs &amp; Co-curricular<small>Discover new interests.</small></span>
            </a>

            <a href="<?php echo base_url('study_tour'); ?>" class="gallery-tile">
                <img src="<?php echo base_url(); ?>assets/images/main_image/tourimage.jpg" alt="Study Tour">
                <span class="tile-label">Field Trips<small>Learning beyond the classroom.</small></span>
            </a>

        </div>
    </div>
</section>

<!-- ============ LEARNING JOURNEY ============ -->
<section class="section journey-section">
    <div class="lh-container journey-grid">

        <div class="journey-media">
            <?php foreach ($homepage_video as $link_value) {
                if ($link_value->c_type == 'infrastructure') {
                    if ($link_value->links != "") {
                        $link = trim($link_value->links);
                        $video_id = '';
                        parse_str(parse_url($link, PHP_URL_QUERY), $vars);
                        if (isset($vars['v'])) { $video_id = $vars['v']; }
                        if (empty($video_id)) { $path = parse_url($link, PHP_URL_PATH); $video_id = trim($path, '/'); }
                        ?>
                        <?php if (!empty($video_id)) { ?>
                            <div class="journey-video">
                                <iframe
                                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>?mute=1&playsinline=1&rel=0"
                                    title="School story video"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="journey-video">
                            <video width="100%" height="100%" controls playsinline>
                                <source src="<?php echo base_url('../assets/uploads/videos/' . $link_value->c_videos); ?>" type="video/*">
                            </video>
                        </div>
                    <?php }
                }
            } ?>
        </div>

        <div class="journey-copy">
            <span class="eyebrow"><span class="dash"></span> Our Learning Journey</span>
            <h2 class="h2">Learning Beyond the Classroom</h2>
            <p class="journey-intro">Our school provides a supportive environment where students learn, explore talents, and grow into confident individuals.</p>

            <ol class="journey-steps">
                <li><span class="step-num">01</span><div><h4>Discover</h4><p>Students explore new ideas.</p></div></li>
                <li><span class="step-num">02</span><div><h4>Learn</h4><p>Build knowledge and confidence.</p></div></li>
                <li><span class="step-num">03</span><div><h4>Create</h4><p>Turn ideas into meaningful work.</p></div></li>
                <li><span class="step-num">04</span><div><h4>Grow</h4><p>Develop skills, values and character.</p></div></li>
            </ol>
        </div>

    </div>
</section>

<!-- ============ MESSAGE FROM LEADERSHIP ============ -->
<section class="section leaders-section">
    <div class="lh-container">

        <div class="leader-block">
            <img src="<?php echo base_url(); ?>assets/images/main_image/director.jpg" alt="Mrs. Nancy Sebastian" class="leader-photo">
            <div class="leader-copy">
                <span class="eyebrow"><span class="dash"></span> Message from the Chairman</span>
                <p class="leader-quote">&ldquo;Since 1997, Little Hearts School has blossomed from a modest rented space into a beacon of excellence &mdash; a cradle where every young mind is polished to brilliance.&rdquo;</p>
                <h4>Mrs. Nancy Sebastian</h4>
                <span class="leader-role">Chairman &amp; Managing Director</span>
            </div>
        </div>

        <div class="leader-block reverse">
            <img src="<?php echo base_url(); ?>assets/images/main_image/principal.jpg" alt="Mrs. Pushpalatha P" class="leader-photo">
            <div class="leader-copy">
                <span class="eyebrow"><span class="dash"></span> Message from the Principal</span>
                <p class="leader-quote">&ldquo;We nurture visionary thinkers and compassionate leaders, blending academic rigor, creative expression and character &mdash; in step with NEP 2020.&rdquo;</p>
                <h4>Mrs. Pushpalatha P</h4>
                <span class="leader-role">Principal</span>
            </div>
        </div>

    </div>
</section>

<!-- ============ NEWS & EVENTS ============ -->
<section class="section news-section">
    <div class="lh-container">
        <div class="section-head-row">
            <div>
                <span class="eyebrow"><span class="dash"></span> News &amp; Events</span>
                <h2 class="h2">Latest News &amp; Events</h2>
            </div>
            <a href="<?php echo base_url('news'); ?>" class="link-arrow">View All News <i class="fa fa-arrow-right"></i></a>
        </div>

        <div class="news-grid">
            <?php foreach ($latest_news as $news) { ?>
                <div class="news-card">
                    <div class="news-date">
                        <strong><?php echo date('d', strtotime($news->d_date)); ?></strong>
                        <span><?php echo date('M', strtotime($news->d_date)); ?></span>
                    </div>
                    <h4><?php echo $news->c_title; ?></h4>
                    <p><?php echo nl2br($news->c_news); ?></p>
                    <a href="<?php echo base_url('news'); ?>" class="link-arrow">Read More <i class="fa fa-arrow-right"></i></a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ IMPORTANT DETAILS ============ -->
<section class="section details-section">
    <div class="lh-container">
        <span class="eyebrow"><span class="dash"></span> School Information</span>
        <h2 class="h2">Important Details</h2>

        <div class="details-grid">

            <a href="<?php echo base_url('mandatory_disclosure'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/mandatory.jpg" alt="Mandatory Disclosure">
                <div><i class="fa fa-info-circle"></i><h4>Mandatory Disclosure</h4><span>Policies, infrastructure &amp; staff information.</span></div>
            </a>

            <a href="<?php echo base_url('admissions'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/admission.jpg" alt="Admissions">
                <div><i class="fa fa-mortar-board"></i><h4>Admissions</h4><span>Process, eligibility &amp; enquiry.</span></div>
            </a>

            <a href="<?php echo base_url('transfer_certificates'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/transfer_new.png" alt="Transfer Certificates">
                <div><i class="fa fa-certificate"></i><h4>Certificates</h4><span>Transfer certificates &amp; records.</span></div>
            </a>

            <a href="<?php echo base_url('fee_regulations'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/School-Fees.jpg" alt="Fee Regulations">
                <div><i class="fa fa-credit-card"></i><h4>Fee Payment Policy</h4><span>Due dates &amp; late fee rules.</span></div>
            </a>

            <a href="<?php echo base_url('school_uniform'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/uniform.avif" alt="School Uniform">
                <div><i class="fa fa-shopping-bag"></i><h4>School Uniform</h4><span>Guidelines for daily wear.</span></div>
            </a>

            <a href="<?php echo base_url('parental_support'); ?>" class="detail-card">
                <img src="<?php echo base_url(); ?>assets/images/main_image/parents.jpg" alt="A Word to Parents">
                <div><i class="fa fa-heart"></i><h4>A Word to Parents</h4><span>Partnering for a child&rsquo;s success.</span></div>
            </a>

        </div>
    </div>
</section>

<!-- ============ CTA BAND ============ -->
<section class="cta-band">
    <img src="<?php echo base_url(); ?>assets/images/main_image/sports_school.jpeg" alt="" class="cta-bg">
    <div class="cta-overlay"></div>
    <div class="lh-container cta-inner">
        <h2 class="h2 h2-light">Give Your Child a Brighter Tomorrow</h2>
        <p>Discover an environment where children learn, explore, create and grow with confidence.</p>
        <div class="hero-actions">
            <a href="<?php echo base_url('admissions'); ?>" class="btn-gold">Apply for Admission <i class="fa fa-arrow-right"></i></a>
            <a href="<?php echo base_url('about_us'); ?>" class="btn-outline">Contact School <i class="fa fa-phone"></i></a>
        </div>
    </div>
</section>

<style>
    /* =========================================================
       LITTLE HEARTS SCHOOL — HOME PAGE (navy + gold theme)
       Pairs with header.php / footer.php
    ========================================================= */
    :root{
        --navy:#0b2447;
        --navy-deep:#081a34;
        --gold:#f5a623;
        --gold-deep:#e2911a;
        --ink:#1c2530;
        --muted:#5b6572;
        --paper:#f6f8fb;
        --line:#e6eaf0;
        --radius:20px;
        --shadow:0 18px 40px rgba(11,36,71,.12);
        --font-head:'Fraunces', Georgia, serif;
        --font-body:'Plus Jakarta Sans', -apple-system, 'Segoe UI', sans-serif;
    }
    body{font-family:var(--font-body);color:var(--ink);}
    .lh-container{max-width:1220px;margin:0 auto;padding:0 24px;}
    h1,h2,h3,h4,h5{font-family:var(--font-head);margin:0;}
    .h2{font-size:34px;font-weight:700;color:var(--navy);margin:8px 0 22px;line-height:1.25;}
    .h2-light{color:#fff;}
    .eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;letter-spacing:.04em;color:var(--gold-deep);}
    .eyebrow-light{color:var(--gold);}
    .eyebrow .dash{width:22px;height:2px;background:currentColor;display:inline-block;}
    .section{padding:78px 0;}

    /* buttons */
    .btn-gold,.btn-outline,.btn-dark{
        display:inline-flex;align-items:center;gap:10px;
        padding:14px 26px;border-radius:50px;font-weight:700;font-size:14.5px;
        text-decoration:none;transition:transform .25s ease, box-shadow .25s ease;
    }
    .btn-gold{background:var(--gold);color:var(--navy-deep);box-shadow:0 12px 26px rgba(245,166,35,.35);}
    .btn-gold:hover{transform:translateY(-2px);box-shadow:0 16px 32px rgba(245,166,35,.45);}
    .btn-outline{border:1.5px solid rgba(255,255,255,.6);color:#fff;}
    .btn-outline:hover{background:rgba(255,255,255,.12);}
    .btn-dark{background:var(--navy);color:#fff;box-shadow:0 12px 26px rgba(11,36,71,.28);}
    .btn-dark:hover{transform:translateY(-2px);}
    .link-arrow{color:var(--navy);font-weight:700;font-size:14px;text-decoration:none;display:inline-flex;gap:8px;align-items:center;}
    .link-arrow:hover{color:var(--gold-deep);}

    /* hero */
    .hero{position:relative;min-height:640px;display:flex;align-items:center;overflow:hidden;color:#fff;}
    .hero-slides{position:absolute;inset:0;}
    .hero-slide{position:absolute;inset:0;opacity:0;transition:opacity 1.2s ease;}
    .hero-slide.is-active{opacity:1;}
    .hero-media{width:100%;height:100%;object-fit:cover;}
    .hero-overlay{position:absolute;inset:0;background:linear-gradient(100deg,rgba(8,26,52,.92) 10%,rgba(8,26,52,.55) 55%,rgba(8,26,52,.25) 100%);}
    .hero-content{position:relative;z-index:2;max-width:680px;padding-top:60px;padding-bottom:60px;}
    .hero-title{font-size:48px;font-weight:700;line-height:1.2;margin:16px 0 18px;}
    .hero-title .accent{color:var(--gold);}
    .hero-copy{font-size:16px;color:#dbe3f0;max-width:520px;margin-bottom:30px;}
    .hero-actions{display:flex;gap:16px;flex-wrap:wrap;}
    .hero-badge{position:absolute;right:60px;bottom:46px;z-index:2;background:rgba(8,26,52,.72);backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:14px;min-width:190px;}
    .hero-badge-row{display:flex;align-items:center;gap:12px;}
    .hero-badge-row i{font-size:20px;color:var(--gold);width:26px;text-align:center;}
    .hero-badge-row strong{display:block;font-family:var(--font-head);font-size:19px;}
    .hero-badge-row span{display:block;font-size:11.5px;color:#c9d3e3;}

    /* about */
    .about-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:60px;align-items:center;}
    .about-copy p{color:var(--muted);font-size:15.5px;margin:16px 0;}
    .about-media{position:relative;}
    .about-img-main{width:100%;border-radius:var(--radius);box-shadow:var(--shadow);}
    .about-img-small{position:absolute;left:-40px;bottom:-40px;width:52%;border:6px solid #fff;border-radius:16px;box-shadow:var(--shadow);}
    .about-badge{position:absolute;top:-24px;right:-10px;width:92px;height:92px;border-radius:50%;background:var(--gold);
        color:var(--navy-deep);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;box-shadow:0 12px 26px rgba(245,166,35,.4);}
    .about-badge strong{font-family:var(--font-head);font-size:19px;}
    .about-badge span{font-size:9.5px;line-height:1.15;font-weight:700;}

    /* features */
    .features-section{background:var(--paper);}
    .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:10px;}
    .feature-hero{grid-row:span 2;position:relative;border-radius:var(--radius);overflow:hidden;min-height:420px;
        display:flex;flex-direction:column;justify-content:flex-end;padding:30px;color:#fff;}
    .feature-hero-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
    .feature-hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,36,71,.15),rgba(8,20,40,.92));}
    .feature-hero-icon{position:relative;z-index:1;width:52px;height:52px;border-radius:14px;background:var(--gold);color:var(--navy-deep);
        display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:14px;}
    .feature-hero h3,.feature-hero p{position:relative;z-index:1;}
    .feature-hero h3{font-size:21px;margin-bottom:8px;}
    .feature-hero p{font-size:14px;color:#dbe3f0;margin:0;}
    .feature-card{background:#fff;border-radius:var(--radius);padding:26px;box-shadow:var(--shadow);transition:transform .3s ease;}
    .feature-card:hover{transform:translateY(-6px);}
    .feature-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;margin-bottom:14px;}
    .i-blue{background:#2f6fed;} .i-orange{background:#f2994a;} .i-purple{background:#8e6fe8;}
    .i-rose{background:#e8577a;} .i-green{background:#2fb380;}
    .feature-card h4{font-size:16.5px;margin-bottom:8px;color:var(--navy);}
    .feature-card p{font-size:13.5px;color:var(--muted);margin:0;}

    /* stats band */
    .stats-band{position:relative;padding:72px 0;color:#fff;overflow:hidden;}
    .stats-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
    .stats-overlay{position:absolute;inset:0;background:linear-gradient(120deg,rgba(8,20,40,.94),rgba(11,36,71,.82));}
    .stats-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1.4fr;gap:36px;align-items:center;}
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
    .stat{text-align:center;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:22px 12px;}
    .stat i{font-size:20px;color:var(--gold);margin-bottom:10px;display:block;}
    .stat strong{font-family:var(--font-head);font-size:28px;}
    .stat span{display:block;font-size:12px;color:#c9d3e3;margin-top:4px;}

    /* activities gallery */
    .gallery-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr;grid-template-rows:1fr 1fr;gap:18px;margin-top:10px;}
    .tile-tall{grid-row:span 2;}
    .gallery-tile{position:relative;display:block;border-radius:var(--radius);overflow:hidden;min-height:210px;text-decoration:none;}
    .gallery-tile img{width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:transform .5s ease;}
    .gallery-tile:hover img{transform:scale(1.08);}
    .tile-label{position:absolute;left:0;right:0;bottom:0;padding:18px;color:#fff;
        background:linear-gradient(0deg,rgba(8,20,40,.88),rgba(8,20,40,0));font-weight:700;font-size:14.5px;z-index:1;}
    .tile-label small{display:block;font-weight:500;font-size:11.5px;color:#dbe3f0;margin-top:3px;}

    /* journey */
    .journey-section{background:var(--paper);}
    .journey-grid{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}
    .journey-video{border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);aspect-ratio:4/3;background:#0b2447;}
    .journey-video iframe,.journey-video video{width:100%;height:100%;border:0;}
    .journey-intro{color:var(--muted);font-size:15px;margin:14px 0 22px;}
    .journey-steps{list-style:none;margin:0;padding:0;}
    .journey-steps li{display:flex;gap:18px;padding:14px 0;border-top:1px solid var(--line);}
    .journey-steps li:first-child{border-top:0;}
    .step-num{font-family:var(--font-head);font-size:22px;font-weight:700;color:var(--gold-deep);width:36px;flex:none;}
    .journey-steps h4{font-size:16px;color:var(--navy);margin-bottom:4px;}
    .journey-steps p{margin:0;font-size:13.5px;color:var(--muted);}

    /* leaders */
    .leader-block{display:grid;grid-template-columns:280px 1fr;gap:44px;align-items:center;margin-bottom:56px;}
    .leader-block:last-child{margin-bottom:0;}
    .leader-block.reverse{grid-template-columns:1fr 280px;}
    .leader-block.reverse .leader-photo{order:2;}
    .leader-photo{width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:var(--radius);box-shadow:var(--shadow);}
    .leader-quote{font-family:var(--font-head);font-size:22px;line-height:1.5;color:var(--navy);margin:14px 0 18px;}
    .leader-copy h4{font-size:16px;color:var(--navy);margin-bottom:2px;}
    .leader-role{font-size:13px;color:var(--muted);}

    /* news */
    .section-head-row{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:6px;flex-wrap:wrap;gap:12px;}
    .news-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:16px;}
    .news-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:26px;box-shadow:var(--shadow);}
    .news-date{display:inline-flex;flex-direction:column;align-items:center;background:var(--navy);color:#fff;border-radius:10px;
        padding:8px 14px;margin-bottom:14px;}
    .news-date strong{font-family:var(--font-head);font-size:18px;line-height:1;}
    .news-date span{font-size:10.5px;letter-spacing:.06em;}
    .news-card h4{font-size:16.5px;color:var(--navy);margin-bottom:8px;}
    .news-card p{font-size:13.5px;color:var(--muted);margin:0 0 14px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;}

    /* important details */
    .details-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:10px;}
    .detail-card{display:flex;align-items:center;gap:16px;background:#fff;border:1px solid var(--line);border-radius:16px;
        padding:14px;text-decoration:none;box-shadow:var(--shadow);transition:transform .25s ease;}
    .detail-card:hover{transform:translateY(-4px);}
    .detail-card img{width:64px;height:64px;object-fit:cover;border-radius:12px;flex:none;}
    .detail-card i{color:var(--gold-deep);margin-bottom:4px;display:block;}
    .detail-card h4{font-size:14.5px;color:var(--navy);margin-bottom:2px;}
    .detail-card span{font-size:12px;color:var(--muted);}

    /* CTA band */
    .cta-band{position:relative;padding:90px 0;color:#fff;text-align:center;overflow:hidden;}
    .cta-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
    .cta-overlay{position:absolute;inset:0;background:linear-gradient(120deg,rgba(8,20,40,.92),rgba(11,36,71,.8));}
    .cta-inner{position:relative;z-index:1;max-width:640px;margin:0 auto;}
    .cta-inner p{color:#dbe3f0;margin:14px 0 26px;}
    .cta-inner .hero-actions{justify-content:center;}

    /* responsive */
    @media (max-width:1024px){
        .about-grid,.journey-grid,.stats-inner{grid-template-columns:1fr;}
        .features-grid{grid-template-columns:repeat(2,1fr);}
        .feature-hero{grid-row:span 1;min-height:280px;}
        .gallery-grid{grid-template-columns:repeat(2,1fr);grid-template-rows:auto;}
        .tile-tall{grid-row:span 1;}
        .news-grid,.details-grid{grid-template-columns:repeat(2,1fr);}
    }
    @media (max-width:768px){
        .hero-title{font-size:32px;}
        .hero-badge{position:static;margin:26px 0 0;flex-direction:row;flex-wrap:wrap;}
        .hero-content{padding-top:36px;padding-bottom:36px;}
        .stats-grid{grid-template-columns:repeat(2,1fr);}
        .features-grid,.news-grid,.details-grid,.gallery-grid{grid-template-columns:1fr;}
        .feature-hero{grid-row:auto;}
        .leader-block,.leader-block.reverse{grid-template-columns:1fr;}
        .leader-block.reverse .leader-photo{order:0;}
        .leader-photo{max-width:220px;margin:0 auto;}
    }
</style>

<script>
    // hero slide rotation
    (function () {
        var slides = document.querySelectorAll('.hero-slide');
        if (slides.length > 1) {
            var i = 0;
            setInterval(function () {
                slides[i].classList.remove('is-active');
                i = (i + 1) % slides.length;
                slides[i].classList.add('is-active');
            }, 6000);
        }
    })();

    // animated counters
    (function () {
        var counters = document.querySelectorAll('[data-count]');
        var done = false;
        function run() {
            if (done) return;
            var band = document.querySelector('.stats-band');
            if (!band) return;
            var rect = band.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.8) {
                done = true;
                counters.forEach(function (el) {
                    var target = parseInt(el.getAttribute('data-count'), 10);
                    var start = 0;
                    var step = Math.max(1, Math.round(target / 60));
                    var t = setInterval(function () {
                        start += step;
                        if (start >= target) { start = target; clearInterval(t); }
                        el.textContent = start;
                    }, 20);
                });
            }
        }
        window.addEventListener('scroll', run);
        run();
    })();
</script>