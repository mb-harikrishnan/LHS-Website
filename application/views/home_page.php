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

<body class="home-page">
    <a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
    <!--Loader-->
    <div class="loader">
        <div class="bouncybox">
            <div class="bouncy"></div>
        </div>
    </div>




    <!--Search-->
    <div id="search">
        <button type="button" class="close">×</button>
        <form>
            <input type="search" value="" placeholder="Search here...." required />
            <button type="submit" class="btn btn_common blue">Search</button>
        </form>
    </div>

    <!--Slider-->
    <section class="rev_slider_wrapper text-center">
        <!-- START REVOLUTION SLIDER 5.0 auto mode -->
        <div id="rev_slider" class="rev_slider" data-version="5.0">
        <ul>

                <?php foreach ($slider as $value) { ?>
                    <li data-transition="fade">
                        <!-- MAIN IMAGE -->



                        <?php if ($value->c_upload_type == "image") { ?>

                            <!-- IMAGE -->

                            <img src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>"
                                alt=""
                                data-bgposition="center center"
                                data-bgfit="cover"
                                data-bgparallax="10"
                                class="rev-slidebg">

                        <?php } ?>

                        <?php if ($value->c_upload_type == "video") { ?>

                            <video autoplay muted loop playsinline controls
                                width="100%"
                                height="700"
                                style="object-fit:cover;">

                                <source src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>" type="video/mp4">

                                Your browser does not support the video tag.

                            </video>

                        <?php } ?>

                        <?php if ($value->c_upload_type == "link") { ?>

                            <?php
                            $link = trim($value->c_file);

                            $video_id = '';

                            parse_str(parse_url($link, PHP_URL_QUERY), $vars);

                            if (isset($vars['v'])) {
                                $video_id = $vars['v'];
                            }

                            // support youtu.be links also
                            if (empty($video_id)) {
                                $path = parse_url($link, PHP_URL_PATH);
                                $video_id = trim($path, '/');
                            }
                            ?>

                            <?php if (!empty($video_id)) { ?>

                                <iframe width="100%"
                                    height="700"
                                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1&mute=1&playsinline=1&loop=1&playlist=<?php echo $video_id; ?>&controls=1&rel=0"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>

                            <?php } ?>

                        <?php } ?>




                        <!-- LAYER NR. 1 -->
                    <li data-transition="fade">
                        <!-- MAIN IMAGE -->
                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['180','170','160','100']" data-voffset="['0','0','0','0']"
                            data-responsive_offset="on"
                            data-visibility="['on','on','on','on']"
                            data-transform_idle="o:1;"
                            data-transform_in="z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;"
                            data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                            data-start="800">
                            <h1 class="hero-title">
                                <?php echo $value->c_title; ?>
                                Empowering Young Minds for a Bright Future
                            </h1>
                        </div>
                        <div class="tp-caption tp-resizeme"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['250','220','210','170']" data-voffset="['0','0','0','0']"
                            data-responsive_offset="on"
                            data-visibility="['on','on','off','off']"
                            data-transform_idle="o:1;"
                            data-transform_in="opacity:0;s:1000;e:Power2.easeInOut;"
                            data-transform_out="opacity:0;s:1000;s:1000;"
                            data-start="1500">
                            <p style="color:#ff6600;">

                                <?php echo $value->c_description; ?>
                            </p>
                            Providing quality education, creativity, and values to help students achieve academic excellence and personal growth.
                            </p>
                        </div>
                        <div class="tp-caption  tp-resizeme"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['250','200','260','120']" data-voffset="['0','0','0','0']"
                            data-responsive_offset="on"
                            data-visibility="['on','on','on','on']"
                            data-transform_idle="o:1;"
                            data-transform_in="y:[-200%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                            data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                            data-start="2000">
                            <a href="<?php echo base_url('about_us'); ?>" class="border_radius btn_common white_border">our services</a>
                            <!-- <a href="#." class="border_radius btn_common blue">Get a quote</a> -->
                        </div>
                    </li>


                <?php } ?>
<?php foreach ($slider as $value) { ?>

<li data-transition="fade">

    <?php if ($value->c_upload_type == "image") { ?>

        <img src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>"
            alt=""
            data-bgposition="center center"
            data-bgfit="cover"
            data-bgparallax="10"
            class="rev-slidebg">

    <?php } ?>



    <?php if ($value->c_upload_type == "video") { ?>

        <video autoplay muted loop playsinline
            class="rev-slidebg"
            style="width:100%; height:100vh; object-fit:cover;">

            <source src="<?php echo base_url('../assets/images/gallery/' . $value->c_file); ?>" type="video/mp4">

        </video>

    <?php } ?>


<?php if ($value->c_upload_type == "link") { ?>

    <video id="bannerVideo"
        autoplay
        muted
        loop
        playsinline
        class="rev-slidebg"
        style="width:100%; height:100vh; object-fit:cover;">

        <source src="<?php echo $value->c_file; ?>" type="video/webm">

    </video>

    <!-- MUTE / UNMUTE BUTTON -->
    <button id="muteBtn"
        onclick="toggleMute()"
        style="
            position:absolute;
            top:20px;
            right:20px;
            z-index:9999;
            padding:10px 15px;
            background:#000;
            color:#fff;
            border:none;
            cursor:pointer;
        ">

        Unmute

    </button>

    <script>

        function toggleMute() {

            var video = document.getElementById("bannerVideo");
            var button = document.getElementById("muteBtn");

            if (video.muted == true) {

                video.muted = false;
                button.innerHTML = "Mute";

            } else {

                video.muted = true;
                button.innerHTML = "Unmute";

            }

            video.play();

        }

    </script>

<?php } ?>



    <!-- TITLE -->
    <div class="tp-caption tp-resizeme"
        data-x="center"
        data-y="180"
        data-start="1500">

        <h4 class="hero-title">
            <?php echo $value->c_title; ?>
        </h4>

    </div>



    <!-- DESCRIPTION -->
    <div class="tp-caption tp-resizeme"
        data-x="center"
        data-y="260"
        data-start="1500">

        <p style="color:#ff6600;">
            <?php echo $value->c_description; ?>
        </p>

    </div>



    <!-- BUTTON -->
    <div class="tp-caption tp-resizeme"
        data-x="center"
        data-y="350"
        data-start="2000">

        <a href="<?php echo base_url('about_us'); ?>"
            class="border_radius btn_common white_border">

            our services

        </a>

    </div>

</li>

<?php } ?>

</ul>
        </div><!-- END REVOLUTION SLIDER -->
    </section>


    <!--ABout US-->
    <section id="about" class="feature-section">


        <div class="container school-about-section">

            <div class="row align-items-center">

                <!-- CONTENT -->
                <div class="col-lg-7 col-md-12 wow fadeInLeft" data-wow-delay="300ms">

                    <h2 class="heading">
                        Welcome to Little Hearts School
                        <span class="divider-left"></span>
                    </h2>

                    <p>
                        Little Hearts School, one of the most progressive schools of its kind in Paravur, was started in the year 1997 in a rented building. Now it is functioning in its own building at Kizhakkepram. It is located in an area of Scenic beauty in a natural setting surrounded by greenery. The school was affiliated to CBSE, New Delhi in January 2006 with AffliationNo. 930601. In the year 2008 the school was upgraded to the Senior Secondary status with Science and Commerce streams and subjects relating to Information Technology.
                    </p>

                    <p>
                        The school is bubbling with energetic and enthusiastic students who are guided and moulded by a highly qualified, experienced and dedicated band of teachers. The syllabus prescribed by the Central Board of Secondary Education, New Delhi is followed. The medium of instruction is English. Hindi is taught as a compulsory subject upto class VIII and Malayalam is the third language. The students can opt Hindi/Malayalam in class IX which should be continued in class X.
                    </p>

                    <p>
                        Adequate emphasis is being given to Co-Curricular Activities so that the children get opportunities to develop their creative talents. Apart from the formal teaching, other co-curricular activities like Music, Drawing, Dance, Chess, Roller-Skating, Yoga Karate, Clay Modeling and Paper Craft are also included in the Curriculum. The School has a very popular Band Troop as well.
                    </p>

                    <p>
                        The students have been grouped into four houses namely, Ragam, Thalam, Sruthi and Layam. Different Intra and Inter House competitions such as Quiz, Debate, Essay Writing, Recitation, Declamation, Drawing and painting, Story Telling, Fancy Dress, Solo Song etc. are conducted with a well planned programme for the same.
                    </p>

                    <p>
                        Our vision of education consists in "the formation of the human being for the fulfillment of his individual and social responsibilities". We aim at moulding leaders "Who will champion the cause of justice, love, truth and peace and who are ever open to further growth. Education for complete living has been and will be the watchword of all our Educational projects and programmes.
                    </p>


                </div>


                <!-- IMAGES -->
                <div class="col-lg-5 col-md-12 wow fadeInRight" data-wow-delay="300ms">

                    <img src="<?php echo base_url(); ?>assets/images/main_image/little-hearts-school-paravur-ernakulam-schools-4c8g4k4.avif"
                        class="school-image"
                        alt="School Image">

                    <img src="<?php echo base_url(); ?>assets/images/main_image/school_full_image.png"
                        class="school-image"
                        alt="School Full Image">

                </div>

            </div>

        </div>

        <div class="container">

            <div class="row feature-row">


                <!-- BOX 1 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="300ms">

                        <div class="feature-number">01</div>

                        <div class="feature-icon">
                            <i class="icon-icons9"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Key Features
                        </h4>

                        <p>
                            Smart Classrooms & Personalized Coaching Interactive smart classes led by qualified educators ensure engaging, technology-driven learning. Tailored support for students with varying learning paces ensures no child is left behind.
                        </p>

                    </div>
                </div>


                <!-- BOX 2 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="400ms">

                        <div class="feature-number">02</div>

                        <div class="feature-icon">
                            <i class="icon-genius"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Kid’s Fest: Unleashing Talent
                        </h4>

                        <p>
                            A platform for children to showcase creativity, skills, and innovation, fostering confidence and self-expression.
                        </p>

                    </div>
                </div>


                <!-- BOX 3 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="500ms">

                        <div class="feature-number">03</div>

                        <div class="feature-icon">
                            <i class="icon-puzzle"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Children’s Park & Activity Room
                        </h4>

                        <p>
                            A serene outdoor space where children connect with nature daily. A well-equipped playroom with non-toxic toys promotes physical exercise and cognitive development.
                        </p>

                    </div>
                </div>


                <!-- BOX 4 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="600ms">

                        <div class="feature-number">04</div>

                        <div class="feature-icon">
                            <i class="icon-users"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Morning Assemblies
                        </h4>

                        <p>
                            Themed assemblies enhance communication skills, teamwork, and self-confidence through activities like storytelling and role-play.
                        </p>

                    </div>
                </div>


                <!-- BOX 5 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="700ms">

                        <div class="feature-number">05</div>

                        <div class="feature-icon">
                            <i class="icon-layers"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Faculty Excellence
                        </h4>

                        <p>
                            Our educators are mentors and lifelong learners. They participate in regular workshops to stay updated on pedagogical advancements, ensuring innovative and effective teaching methodologies.
                        </p>

                    </div>
                </div>


                <!-- BOX 6 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">06</div>

                        <div class="feature-icon">
                            <i class="icon-open-book"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Library
                        </h4>

                        <p>
                            A district-leading resource with diverse books, periodicals, and age-appropriate reading cards for kindergarteners.
                        </p>

                    </div>
                </div>
                <!-- BOX 7 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">07</div>

                        <div class="feature-icon">
                            <i class="icon-music"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Extracurricular Programs
                        </h4>

                        <p>
                            Specialized coaching in dance, music, and yoga to nurture well- rounded personalities.
                        </p>

                    </div>
                </div>
                <!-- BOX 8 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">08</div>

                        <div class="feature-icon">
                            <i class="icon-shield"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Strict Security Protocols
                        </h4>

                        <p>
                            Children are released only to authorized individuals with proper identification.
                        </p>

                    </div>
                </div>
                <!-- BOX 9 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">09</div>

                        <div class="feature-icon">
                            <i class="icon-lightbulb"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Life Skills Training
                        </h4>

                        <p>
                            Values like empathy and responsibility are taught through stories and hands-on activities. Supervised Outdoor Play: Safe and monitored outdoor sessions under educator guidance.
                        </p>

                    </div>
                </div>
                <!-- BOX 10 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">10</div>

                        <div class="feature-icon">
                            <i class="icon-calendar"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Engaging Events
                        </h4>

                        <p>
                            Color Days: Monthly creative celebrations enhance learning through themed art, music, and collaborative projects.
                        </p>

                    </div>
                </div>
                <!-- BOX 11 -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="feature-box wow fadeInUp" data-wow-delay="800ms">

                        <div class="feature-number">11</div>

                        <div class="feature-icon">
                            <i class="icon-trophy"></i>
                        </div>

                        <h4 class="text-capitalize">
                            Graduation Ceremony
                        </h4>

                        <p>
                            A proud moment for parents as UKG graduates receive scrolls of honor, marking their transition to formal schooling.
                            At Fun 'N’ Learn, we blend creativity, safety, and academic rigor to empower young learners. With unwavering parental support, we strive
                            for continuous excellence, ensuring every child’s journey is filled with joy, growth, and achievement.
                        </p>

                    </div>
                </div>


            </div>
        </div>


    </section>
    <!--ABout US-->


    <!-- Courses -->
    <section id="courses" class="padding parallax">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="heading heading_space wow fadeInDown">Activities<span class="divider-left"></span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="slider_wrapper">
                        <div id="course_slider" class="owl-carousel">
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/house.jpg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('house_system'); ?>">House System</a></h3>
                                <p class="bottom15">Our House System encourages teamwork, leadership, discipline, and healthy competition among students through various academic, cultural, and sports activities. It helps children build confidence, unity, and a strong sense of responsibility.</p>
                                <a href="<?php echo base_url('house_system'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/dance.png" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('co_curricular_activities'); ?>">Co-Curricular Activities</a></h3>
                                <p class="bottom15">Co-Curricular Activities help students develop creativity, confidence, teamwork, and overall personality through arts, sports, music, and cultural programs.</p>
                                <a href="<?php echo base_url('co_curricular_activities'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/sports_school.jpeg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('sports_and_games'); ?>">Sports and Games</a></h3>
                                <p class="bottom15">Each individual in Little Hearts School is held in high esteem as they take part in various sports activities which enable them to develop their sportsman spirit.</p>
                                <a href="<?php echo base_url('sports_and_games'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/ClubsActivities.jpg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('clubs'); ?>">Clubs</a></h3>
                                <p class="bottom15">Clubs encourage students to explore their talents, develop leadership skills, and build teamwork through fun and creative activities. They help children gain confidence, social skills, and practical knowledge beyond academics.</p>
                                <a href="<?php echo base_url('clubs'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/schoolband.jpg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('band_page'); ?>">School Band</a></h3>
                                <p class="bottom15">Our School Band comprises of 24 students with side drums, euphoniums, drums, trumpets and saxophones.</p>
                                <a href="<?php echo base_url('band_page'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/tourimage.jpg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('study_tour'); ?>">Study Tour</a></h3>
                                <p class="bottom15">This is conducted for all classes to expose the students to the knowledge of nature. It will help them with the real life experience which will leave an indelible imprint in the minds of the children..</p>
                                <a href="<?php echo base_url('study_tour'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                            <div class="item">
                                <div class="image bottom20">
                                    <img src="<?php echo base_url(); ?>assets/images/main_image/anual_day.jpeg" alt="Courses" class="img-responsive border_radius">
                                </div>
                                <h3 class="bottom15"><a href="<?php echo base_url('annual_day'); ?>">Annual Day</a></h3>
                                <p class="bottom15">Little Hearts School Annual Day Celebration is held in high esteem every year.
                                    This is the most well known and celebrated function of Little Hearts School as majority of the students participate in the programmes held on that day.</p>
                                <a href="<?php echo base_url('annual_day'); ?>" class="btn_common blue border_radius">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Courses -->




    <!--Fun Facts-->
    <section id="facts" class="learning-section">
        <div class="container">

            <div class="section-title text-center">
                <h2>Learning Journey</h2>
                <p>
                    Our school provides a supportive environment where students learn,
                    explore talents, and grow into confident individuals.
                </p>
            </div>

            <div class="learning-wrapper">

                <?php foreach ($homepage_video as $link_value) { ?>

                    <?php if ($link_value->c_type == 'infrastructure') { ?>

                        <?php if ($link_value->links != "") { ?>

                            <?php
                            $link = trim($link_value->links);

                            $video_id = '';

                            parse_str(parse_url($link, PHP_URL_QUERY), $vars);

                            if (isset($vars['v'])) {
                                $video_id = $vars['v'];
                            }

                            // support youtu.be links also
                            if (empty($video_id)) {
                                $path = parse_url($link, PHP_URL_PATH);
                                $video_id = trim($path, '/');
                            }
                            ?>

                            <?php if (!empty($video_id)) { ?>
                                <div class="learning-video">

                                    <iframe
                                        src="https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1&mute=1&playsinline=1&loop=1&playlist=<?php echo $video_id; ?>&controls=1&rel=0"
                                        title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>

                                    <div class="section-title text-center">
                                        <h2>Learning Journey</h2>
                                        <p>
                                            Our school provides a supportive environment where students learn,
                                            explore talents, and grow into confident individuals.
                                        </p>
                                    </div>

                                </div>

                            <?php } ?>

                        <?php } else {  ?>

                            <div class="learning-video">

                                <?php
                                $video = base_url('../assets/uploads/videos/' . $link_value->c_videos);
                                ?>

                                <video width="100%" height="450" autoplay muted loop controls playsinline>

                                    <source src="<?php echo $video; ?>" type="video/*">

                                    Your browser does not support the video tag.

                                </video>

                            </div>


                        <?php } ?>


                <?php }
                } ?>

                <!-- Right Side Stats -->
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="icon-box">
                            <i class="icon-trophy"></i>
                        </div>
                        <h3 data-to="10000">10,000+</h3>
                        <p>Passed-Out Students</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon-box">
                            <i class="icon-checkmark3"></i>
                        </div>
                        <h3 data-to="8539">8539</h3>
                        <p>Campus Area (Sq Mtr)</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon-box">
                            <i class="fa fa-user"></i>
                        </div>
                        <h3 data-to="186">186+</h3>
                        <p>Experienced Teachers</p>
                    </div>

                    <div class="stat-card">
                        <div class="icon-box">
                            <i class="icon-happy"></i>
                        </div>
                        <h3 data-to="100">100%</h3>
                        <p>Pass Percentage</p>
                    </div>



                </div>
            </div>
    </section>

    <!--Customers Review-->
    <section id="reviews" class="padding bg_light">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center wow fadeInDown">
                    <h2 class="heading heading_space">What People say <span class="divider-center"></span></h2>
                    <div id="review_slider" class="owl-carousel text-center">
                        <div class="item">
                            <h4>Mrs.Nancy Sebastian</h4>
                            <p>Chairman and Managing Director</p>
                            <img src="<?php echo base_url(); ?>assets/images/main_image/director.jpg" class="client_pic border_radius" alt="costomer">
                            <p> Dear Esteemed Students and Beloved Guardians,
                                Since its humble inception in 1997, Little Hearts School blossomed from a modest rented space into a beacon of excellence, now adorned with state-of-the-art infrastructure. Our journey, rooted in the unwavering vision to “Strive for the Best,” has flourished into a symphony of dedication, where every brick and heartbeat echoes our pursuit of unparalleled greatness.
                                To the stars of our constellation—the tireless teaching and non-teaching staff, the steadfast parents, and our cherished well-wishers—we owe the crescendo of achievements that adorn our legacy. Your unwavering support has transformed Little Hearts into a sanctuary where young minds, the radiant jewels of our society and nation, are polished to brilliance. With diligence and passion, each student is empowered to ascend the pinnacle of their potential. Together, let us weave Little Hearts into “A Cradle of Excellence,” where intellect and spirit soar in unison.
                                With boundless optimism and collective resolve, we invite you to join hands in scripting a future where Little Hearts stands as a paragon of educational distinction—where excellence is not merely taught but lived.</p>
                        </div>
                        <div class="item">
                            <h4>Mrs. Pushpalatha P</h4>
                            <p>Principal</p>
                            <img src="<?php echo base_url(); ?>assets/images/main_image/principal.jpg" class="client_pic border_radius" alt="costomer">
                            <p>Dear Esteemed Students, Guardians, and Cherished Members of the Little Hearts Family,
                                At Little Hearts School, we embark on a transformative journey to cultivate visionary thinkers and compassionate leaders, harmonizing the timeless values of excellence with the progressive ethos of the National Education Policy (NEP) 2020. Our sanctuary of learning aspires to ignite in every child a spark of innovation, guiding them to discover luminous role models across the kaleidoscope of human endeavor—be it academia, artistry, athletics, or altruism.
                                We celebrate the unique brilliance of each learner, fostering a holistic ecosystem where academic rigor, creative expression, spiritual depth, and physical vitality converge. Aligning with NEP 2020’s emphasis on multidisciplinary fluency, we empower students to transcend boundaries, blending logic with imagination, and knowledge with empathy. Our pedagogy thrives on experiential learning, where curiosity is not merely encouraged but revered as the compass to self-discovery.
                                Inspired by NEP 2020’s call for critical consciousness, we kindle in our students a restless intellect— a thirst to question, explore, and reimagine the world.
                                We extend a heartfelt welcome to new families joining our synergistic community. Together, let us forge a partnership where parental wisdom and institutional expertise dance in tandem, actualizing our shared dream of equitable, future-ready education. As the adage goes, “Excellence is not an act but a habit” —let us cultivate this habit with unwavering resolve, for quality is the cornerstone of our legacy. With boundless optimism and a shared commitment to nurturing 21st-century trailblazers.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>




    <!--News-->
    <section class="padding" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center wow fadeInDown">
                    <h2 class="heading">News Updates<span class="divider-center"></span></h2>
                    <p class="heading_space margin10">Regular updates and announcements for students and parents.</p>
                </div>




                <div class="col-md-12">



                    <div class="pricing">
                        <?php foreach ($latest_news as $news) { ?>
                            <div class="pricing_item wow fadeInUp" data-wow-delay="300ms">

                                <h3><?php echo $news->c_title; ?></h3>

                                <p class="pricing_sentence blink_text">
                                    <?php echo nl2br($news->c_news); ?>
                                </p>
                                <ul class="pricing_list">
                                    <li class="pricing_feature">
                                        <?php echo date('j F Y', strtotime($news->d_date)); ?>
                                    </li>
                                    <!-- <li class="pricing_feature">Available for all students</li>
                                    <li class="pricing_feature">Visit school office for support</li> -->
                                </ul>

                                <a class="btn_common text-center" href="<?php echo base_url('news'); ?>">Read More</a>

                            </div>
                        <?php } ?>
                    </div>


                </div>

            </div>
        </div>
        </div>
    </section>
    <!--Pricings-->


    <!-- Paralax -->
    <!-- <section id="parallax" class="parallax">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center wow bounceIn">
       <h2>We Believe that Education for Everyone Since</h2>
       <h1 class="margin10">1942</h1>
       <a href="#." class="border_radius btn_common white_border margin10">Gaet a Quote</a>
      </div>
    </div>
  </div>
</section> -->
    <!--Paralax -->


    <!-- News-->
    <section id="news" class="padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 wow fadeInDown">
                    <h2 class="heading heading_space">Important Details<span class="divider-left"></span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="slider_wrapper">
                        <div id="news_slider" class="owl-carousel">
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/mandatory.jpg" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('mandatory_disclosure'); ?>">Mandatory Disclosure</a></h4>
                                        <p>Mandatory Disclosure provides complete and transparent information about the school’s policies, infrastructure, academics, staff, and facilities for parents and students.</p>
                                        <a href="<?php echo base_url('mandatory_disclosure'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/transfer_new.png" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('transfer_certificates'); ?>"> Transfer Certificates</a></h4>
                                        <p>Transfer Certificates are issued to students for official school transfer purposes, ensuring a smooth continuation of their education in another institution.</p>
                                        <a href="<?php echo base_url('transfer_certificates'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/admission.jpg" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('admissions'); ?>">Admissions</a></h4>
                                        <p>We offer the most complete house Services in the country...</p>
                                        <a href="<?php echo base_url('admissions'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/uniform.avif" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('school_uniform'); ?>">School Uniform</a></h4>
                                        <p>The School Uniform is obligatory on all working days and also at school official functions. Parents should take special care to send their child too school, neatly dressed, in the prescribed uniform which reflects the discipline of the school.</p>
                                        <a href="<?php echo base_url('school_uniform'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/School-Fees.jpg" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('fee_regulations'); ?>">School Fee Payment Policy</a></h4>
                                        <p>Fees and transportation charges must be paid by the 10th of every month. A late fee of Rs.50 will apply for payments made between 11th-30th of the month.</p>
                                        <a href="<?php echo base_url('fee_regulations'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content_wrap">
                                    <div class="image">
                                        <img src="<?php echo base_url(); ?>assets/images/main_image/parents.jpg" alt="Edua" class="img-responsive border_radius">
                                    </div>
                                    <div class="news_box border_radius">
                                        <h4><a href="<?php echo base_url('parental_support'); ?>">A word to parents</a></h4>
                                        <p>A Word to Parents highlights the importance of parental support and cooperation in shaping a child’s academic success, values, and overall development.</p>
                                        <a href="<?php echo base_url('parental_support'); ?>" class="readmore">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <style>
        .pricing_sentence {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            line-height: 1.6;
        }

        .icon_box {
            min-height: 250px;
        }

        /* =========================================================
           LITTLE HEARTS SCHOOL — GLASSY THEME (consolidated rebuild)
           Single source of truth: no duplicate/overriding blocks.
        ========================================================= */

        :root {
            --primary: #0654c2;
            --primary-light: #0A84FF;
            --ink: #1b2430;
            --muted: #64707c;
            --glass-bg: rgba(255, 255, 255, 0.60);
            --glass-bg-strong: rgba(255, 255, 255, 0.80);
            --glass-border: rgba(255, 255, 255, 0.45);
            --glass-shadow: 0 10px 34px rgba(6, 84, 194, 0.10);
            --glass-shadow-hover: 0 20px 46px rgba(6, 84, 194, 0.18);
            --radius-lg: 26px;
            --radius-md: 18px;
            --section-pad: 70px;
            --card-gap: 26px;
        }

        * { box-sizing: border-box; }

        body {
            color: var(--ink);
        }

        /* ---------- shared section rhythm (kills mismatched paddings) ---------- */
        #about, #courses, #facts, #reviews, #pricing, #news {
            padding: var(--section-pad) 0;
            background: linear-gradient(135deg, rgba(240, 248, 255, 0.6), rgba(255, 255, 255, 0.98));
        }

        .heading, .section-title h2 {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .heading_space { margin-bottom: 34px; }

        .divider-left, .divider-center {
            width: 70px;
            height: 4px;
            display: block;
            border-radius: 20px;
            background: linear-gradient(to right, var(--primary), var(--primary-light)) !important;
            margin-top: 10px;
        }

        .divider-center { margin: 12px auto 0; }

        /* ---------- universal glass card ---------- */
        .feature-box,
        .pricing_item,
        .content_wrap,
        .stat-card,
        #course_slider .item,
        #review_slider .item {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            border-radius: var(--radius-lg);
            transition: transform .35s ease, box-shadow .35s ease;
        }

        .feature-box:hover,
        .pricing_item:hover,
        .content_wrap:hover,
        .stat-card:hover,
        #course_slider .item:hover,
        #review_slider .item:hover {
            transform: translateY(-8px);
            box-shadow: var(--glass-shadow-hover);
        }

        /* =========================================================
           1) KEY FEATURES — equal-size cards, flex layout
        ========================================================= */
        .feature-row { row-gap: var(--card-gap); }

        .feature-box {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 320px;
            padding: 34px 28px;
            overflow: hidden;
        }

        .feature-box::before {
            content: "";
            position: absolute;
            top: -60px; right: -60px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(6, 84, 194, .16), rgba(10, 132, 255, .06));
        }

        .feature-number {
            position: absolute;
            top: 16px; right: 20px;
            font-size: 56px;
            font-weight: 800;
            color: rgba(6, 84, 194, .08);
            line-height: 1;
        }

        .feature-icon {
            width: 64px; height: 64px;
            flex: none;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 24px;
            margin-bottom: 18px;
            box-shadow: 0 10px 22px rgba(6, 84, 194, .25);
            transition: transform .35s ease;
        }

        .feature-box:hover .feature-icon { transform: scale(1.08) rotate(-4deg); }

        .feature-box h4 {
            font-size: 19px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            line-height: 1.35;
        }

        .feature-box p {
            font-size: 14px;
            line-height: 1.65;
            color: var(--muted);
            margin: 0;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
        }

        @media (max-width: 768px) {
            .feature-box { height: auto; min-height: 260px; padding: 26px 22px; }
        }

        /* =========================================================
           2) NEWS UPDATES (pricing_item) — identical size,
              button pinned to the same spot on every card
        ========================================================= */
        .pricing {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--card-gap);
            align-items: stretch;
        }

        .pricing_item {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 340px;
            padding: 32px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .pricing_item::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
        }

        .pricing_item h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pricing_sentence {
            background: rgba(6, 84, 194, .06);
            border: 1px dashed rgba(6, 84, 194, .25);
            color: var(--primary);
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.6;
            margin: 0 0 16px;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .pricing_list { list-style: none; padding: 0; margin: 0 0 18px; }

        .pricing_feature {
            font-size: 14px;
            color: var(--muted);
            padding: 6px 0;
        }

        .pricing_item .btn_common {
            margin-top: auto;
            align-self: center;
        }

        @media (max-width: 768px) {
            .pricing_item { height: auto; padding: 28px 20px; }
        }

        /* =========================================================
           3) IMPORTANT DETAILS (content_wrap) — reference sizing,
              tidied to match the same system
        ========================================================= */
        .content_wrap {
            display: flex;
            flex-direction: column;
            height: 460px;
            overflow: hidden;
        }

        .content_wrap .image { position: relative; overflow: hidden; flex: none; }

        .content_wrap .image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .content_wrap:hover .image img { transform: scale(1.06); }

        .content_wrap .image::after {
            content: "";
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(6, 84, 194, .18), rgba(255, 255, 255, .04));
        }

        .news_box {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 24px 22px;
            background: transparent;
        }

        .news_box h4 { margin-bottom: 10px; line-height: 1.35; }

        .news_box h4 a {
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            text-decoration: none;
            transition: color .3s;
        }

        .news_box h4 a:hover { color: var(--primary); }

        .news_box p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 16px;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .news_box .readmore {
            margin-top: auto;
            align-self: flex-start;
            display: inline-block;
            padding: 11px 26px;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(6, 84, 194, .22);
            transition: transform .3s, box-shadow .3s;
        }

        .news_box .readmore:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(6, 84, 194, .3);
        }

        @media (max-width: 768px) {
            .content_wrap { height: auto; }
            .content_wrap .image img { height: 170px; }
        }

        /* =========================================================
           4) OTHER CARDS — activities, reviews, stats
        ========================================================= */
        #course_slider .item {
            display: flex;
            flex-direction: column;
            height: 440px;
            padding: 18px;
            margin: 4px;
        }

        #course_slider .image { border-radius: 16px; overflow: hidden; flex: none; }

        #course_slider .image img { width: 100%; height: 190px; object-fit: cover; }

        #course_slider h3 { font-size: 19px; margin: 16px 0 8px; }
        #course_slider h3 a { color: var(--ink); text-decoration: none; }
        #course_slider h3 a:hover { color: var(--primary); }

        #course_slider p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        #course_slider .btn_common { margin-top: auto; align-self: flex-start; }

        .stat-card {
            padding: 26px 18px;
            text-align: center;
        }

        .icon-box {
            width: 64px; height: 64px;
            margin: 0 auto 14px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 26px;
            box-shadow: 0 10px 22px rgba(6, 84, 194, .22);
        }

        .stat-card h3 { font-size: 30px; font-weight: 700; margin-bottom: 6px; }
        .stat-card p { color: var(--muted); font-size: 14px; margin: 0; }

        #review_slider .item { padding: 32px 36px; text-align: center; position: relative; }

        #review_slider .item::before {
            content: "\f10d";
            font-family: "FontAwesome";
            position: absolute; top: 18px; left: 22px;
            color: rgba(6, 84, 194, .2);
            font-size: 24px;
        }

        .client_pic {
            width: 100px; height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 14px;
            border: 4px solid rgba(6, 84, 194, .12);
        }

        #review_slider h4 { font-size: 22px; font-weight: 700; margin-bottom: 4px; color: var(--ink); }
        #review_slider .item p:nth-child(2) { color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 16px; }
        #review_slider .item p:last-child { font-size: 14px; line-height: 1.8; color: var(--muted); max-width: 900px; margin: auto; }

        /* =========================================================
           5) BUTTONS + OWL NAV — one definition each
        ========================================================= */
        .btn_common {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff !important;
            padding: 12px 30px;
            border-radius: 50px;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            box-shadow: 0 10px 22px rgba(6, 84, 194, .22);
            transition: transform .3s, box-shadow .3s, background .3s;
        }

        .btn_common:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #054aab, var(--primary));
            box-shadow: 0 14px 28px rgba(6, 84, 194, .3);
        }

        .owl-nav { text-align: center; margin-top: 20px; }

        .owl-nav button {
            width: 44px; height: 44px;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, .85) !important;
            color: var(--primary) !important;
            font-size: 20px !important;
            margin: 0 6px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .08);
            border: 1px solid rgba(6, 84, 194, .1);
            transition: .3s;
        }

        .owl-nav button:hover { background: var(--primary) !important; color: #fff !important; }

        .owl-dots { text-align: center; margin-top: 22px; }

        .owl-dot span {
            width: 10px; height: 10px;
            background: rgba(6, 84, 194, .25) !important;
            border-radius: 50%;
            display: block; margin: 4px;
            transition: .3s;
        }

        .owl-dot.active span {
            width: 28px;
            border-radius: 20px;
            background: var(--primary) !important;
        }

        /* =========================================================
           6) ABOUT US
        ========================================================= */
        .school-about-section { padding: 0 0 50px; }
        .school-about-section h2 { font-size: 32px; font-weight: 700; margin-bottom: 20px; color: var(--primary); }
        .school-about-section p { font-size: 15.5px; line-height: 1.7; color: var(--muted); text-align: justify; margin-bottom: 18px; }

        .school-image {
            width: 100%;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            box-shadow: 0 8px 22px rgba(6, 84, 194, .12);
        }

        /* =========================================================
           7) LEARNING JOURNEY (video + stats)
        ========================================================= */
        .learning-wrapper {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 24px;
            align-items: stretch;
        }

        .learning-video {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--glass-shadow);
            min-height: 380px;
        }

        .learning-video iframe { width: 100%; height: 100%; min-height: 380px; border: 0; }

        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }

        .section-title p { max-width: 700px; margin: auto; color: var(--muted); line-height: 1.75; font-size: 15.5px; }

        /* =========================================================
           8) NEWS BLINK TEXT / HERO TITLE — kept, de-duplicated
        ========================================================= */
        .blink_text { color: var(--primary); animation: blinkEffect 1.6s infinite; }

        @keyframes blinkEffect {
            0%, 100% { opacity: 1; }
            50% { opacity: .65; }
        }

        .hero-title {
            display: inline-block;
            padding: 16px 22px;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(255,255,255,.55), rgba(255,255,255,.25));
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,.35);
            box-shadow: 0 10px 32px rgba(6, 84, 194, .15);
            color: var(--primary);
            font-size: 42px;
            font-weight: 800;
            line-height: 1.3;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 26px; padding: 14px 18px; border-radius: 16px; }
        }

        /* =========================================================
           9) SLIDER HEIGHT + SPACING CLEANUP (no gaps between blocks)
        ========================================================= */
        .rev_slider_wrapper, #rev_slider, .rev_slider, .rev_slider ul, .rev_slider ul li, .tp-bgimg, .rev-slidebg {
            height: 100% !important;
            min-height: 350px !important;
            max-height: 100% !important;
        }

        .rev-slidebg, .tp-bgimg {
            width: 100% !important;
            object-fit: cover !important;
            object-position: center center !important;
        }

        .rev_slider_wrapper { margin: 0 !important; padding: 0 !important; overflow: hidden !important; }
        .rev_slider { margin-bottom: 0 !important; }
        .rev_slider_wrapper + section, .rev_slider_wrapper + div { margin-top: 0 !important; padding-top: 0 !important; }
        #about { margin-top: 0 !important; }

        @media (max-width: 768px) {
            :root { --section-pad: 46px; }

            .rev_slider_wrapper, #rev_slider, .rev_slider, .rev_slider ul, .rev_slider ul li, .tp-bgimg, .rev-slidebg {
                height: 350px !important;
                min-height: 350px !important;
            }

            .heading, .section-title h2 { font-size: 28px; }
            .learning-wrapper { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
