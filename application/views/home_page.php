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

    <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.png">

    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
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

        <video autoplay muted loop playsinline
            class="rev-slidebg"
            style="width:100%; height:100vh; object-fit:cover;">

            <source src="<?php echo $value->c_file; ?>" type="video/webm">

        </video>

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

        /* Same size for all images */
        #course_slider .item .image {
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        #course_slider .item .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Title */
        #course_slider .item h3 {
            font-size: 18px;
            min-height: 50px;
            margin-bottom: 10px;
        }

        /* Limit paragraph height */
        #course_slider .item p {
            font-size: 14px;
            line-height: 1.6;
            height: 90px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            margin-bottom: 15px;
        }


        /* message image  */

        .client_pic {
            width: 120px;
            /* change size as you want */
            height: 120px;
            /* keeps it uniform */
            object-fit: cover;
            border-radius: 50%;
            /* optional: makes it round */
        }

        /* news */
        .blink_text {
            color: green;
            font-weight: bold;
            animation: blinkEffect 1s infinite;
            text-shadow: 0 0 5px lightgreen;
        }

        @keyframes blinkEffect {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }



        /**container foe welcome about */
        .school-about-section {
            padding: 60px 0;
        }

        .school-about-section h2 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .school-about-section p {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
            text-align: justify;
            margin-bottom: 22px;
        }

        .school-image {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .about-post {
            background: #fff;
            padding: 35px 25px;
            border-radius: 30px 8px 30px 8px;
            text-align: center;
            margin-top: 25px;
            position: relative;
            overflow: hidden;
            transition: 0.4s ease;
            border: 1px solid #edf0f7;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        /* top shape */
        .about-post:before {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #ff5e7d, #ffb547);
            opacity: 0.08;
            border-radius: 50%;
        }

        /* hover effect */
        .about-post:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.10);
        }

        /* image style */
        .about-post img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 18px;
            transition: 0.4s ease;
        }

        /* image animation */
        .about-post:hover img {
            transform: scale(1.1) rotate(5deg);
        }

        /* heading */
        .about-post h4 {
            font-size: 24px;
            font-weight: 700;
            color: #222;
            margin-top: 10px;
            transition: 0.3s;
        }

        /* heading hover color */
        .about-post:hover h4 {
            color: #ff5e7d;
        }

        @media(max-width:768px) {

            .school-about-section {
                padding: 40px 15px;
            }

            .school-about-section h2 {
                font-size: 28px;
            }

            .school-about-section p {
                font-size: 15px;
                line-height: 28px;
            }
        }

        /**container foe welcome about */
        /**container foe key feature about */
        /* SECTION */
        /* ================================
   FEATURE SECTION
================================ */

        .feature-section {
            padding: 90px 0;
            background:
                linear-gradient(135deg,
                    #f4fffd,
                    #ffffff);
            overflow: hidden;
        }

        /* FEATURE BOX */
        .feature-box {
            position: relative;

            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);

            border: 1px solid rgba(255, 255, 255, 0.35);

            border-radius: 32px;

            padding: 40px 30px;

            margin-bottom: 35px;

            min-height: 380px;

            overflow: hidden;

            transition: 0.4s ease;

            box-shadow:
                0 10px 30px rgba(15, 118, 110, 0.10);
        }

        /* TOP GLASS SHAPE */
        .feature-box::before {
            content: "";
            position: absolute;

            top: -60px;
            right: -60px;

            width: 180px;
            height: 180px;

            background:
                linear-gradient(135deg,
                    rgba(15, 118, 110, 0.18),
                    rgba(20, 184, 166, 0.08));

            border-radius: 50%;
        }

        /* BIG NUMBER */
        .feature-number {
            position: absolute;
            top: 18px;
            right: 22px;

            font-size: 70px;
            font-weight: 800;

            color: rgba(15, 118, 110, 0.08);

            line-height: 1;
        }

        /* HOVER */
        .feature-box:hover {
            transform: translateY(-10px);

            box-shadow:
                0 20px 40px rgba(15, 118, 110, 0.18);
        }

        /* ICON */
        .feature-icon {
            width: 82px;
            height: 82px;

            border-radius: 24px;

            background:
                linear-gradient(135deg,
                    #0654c2,
                    #0A84FF);

            display: flex;
            align-items: center;
            justify-content: center;

            color: #fff;
            font-size: 30px;

            margin-bottom: 25px;

            transform: rotate(-6deg);

            transition: 0.4s ease;

            box-shadow:
                0 10px 25px rgba(15, 118, 110, 0.22);
        }

        .feature-box:hover .feature-icon {
            transform: rotate(0deg) scale(1.08);
        }

        /* TITLE */
        .feature-box h4 {
            font-size: 24px;
            font-weight: 700;

            color: #0654c2;

            margin-bottom: 18px;

            line-height: 1.5;
        }

        /* CONTENT */
        .feature-box p {
            font-size: 15px;
            line-height: 1.8;

            color: #5b6770;

            margin: 0;
        }

        /* ROW GAP */
        .feature-row {
            row-gap: 25px;
        }

        /* MOBILE */
        @media(max-width:768px) {

            .feature-box {
                padding: 35px 24px;
                min-height: auto;
            }

            .feature-box h4 {
                font-size: 20px;
            }

            .feature-number {
                font-size: 52px;
            }
        }

        /**container foe key feature about */



        /* activity side  */



        /* Activities Section */
        #courses {
            background: linear-gradient(135deg, #fff8f2 0%, #fffdfb 100%);
            position: relative;
        }

        #courses .heading {
            font-size: 42px;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
        }

        #courses .divider-left {
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #ff8a00, #ffb347);
            display: block;
            margin-top: 10px;
            border-radius: 10px;
        }

        /* Card Design */
        #course_slider .item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            overflow: hidden;
            padding: 20px;
            margin: 15px;
            box-shadow: 0 10px 30px rgba(255, 136, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 170, 80, 0.15);
            min-height: 520px;
            position: relative;
        }

        #course_slider .item:hover {
            transform: translateY(-10px);
            box-shadow: 0 18px 40px rgba(255, 136, 0, 0.18);
        }

        /* Image */
        #course_slider .image {
            overflow: hidden;
            border-radius: 18px;
            position: relative;
        }

        #course_slider .image img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        #course_slider .item:hover img {
            transform: scale(1.08);
        }

        /* Removed dark overlay and added soft orange glow */
        #course_slider .image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(255, 153, 51, 0.15),
                    rgba(255, 255, 255, 0.02));
            border-radius: 18px;
        }

        /* Title */
        #course_slider h3 {
            font-size: 24px;
            font-weight: 700;
            margin-top: 20px;
        }

        #course_slider h3 a {
            color: #222;
            text-decoration: none;
            transition: 0.3s;
        }

        #course_slider h3 a:hover {
            color: #ff8a00;
        }

        /* Description */
        #course_slider p {
            color: #666;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        /* Button */


        .btn_common.blue:hover {
            background: linear-gradient(135deg, #ff7b00, #ff9800);
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(255, 138, 0, 0.35);
        }

        /* Owl Navigation */
        .owl-nav {
            margin-top: 25px;
            text-align: center;
        }

        .owl-nav button {
            width: 45px;
            height: 45px;
            border-radius: 50% !important;
            background: #fff !important;
            color: #00ff51 !important;
            font-size: 22px !important;
            margin: 0 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .owl-nav button:hover {
            background: #37ff00 !important;
            color: #fff !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #courses .heading {
                font-size: 30px;
            }

            #course_slider .item {
                min-height: 420px;
                padding: 16px;
            }

            #course_slider .image img {
                height: 220px;
            }
        }


        /* activity side  */


        /* video side   */


        /* SECTION */
        .learning-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #fff8f2, #ffffff);
            overflow: hidden;
        }

        /* TITLE */
        .section-title h2 {
            font-size: 42px;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
        }

        .section-title p {
            max-width: 700px;
            margin: auto;
            color: #666;
            line-height: 1.8;
            font-size: 16px;
        }

        /* MAIN LAYOUT */
        .learning-wrapper {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 30px;
            align-items: stretch;
        }

        /* VIDEO */
        .learning-video {
            position: relative;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(255, 136, 0, 0.12);
            min-height: 420px;
        }

        .learning-video iframe {
            width: 100%;
            height: 100%;
            min-height: 420px;
            border: 0;
        }

        /* STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        /* CARD */
        .stat-card {
            background: #fff;
            padding: 30px 20px;
            border-radius: 22px;
            text-align: center;
            transition: 0.4s ease;
            border: 1px solid rgba(255, 153, 51, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 35px rgba(255, 136, 0, 0.15);
        }

        /* ICON */
        .icon-box {
            width: 75px;
            height: 75px;
            margin: auto auto 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff8a00, #ffb347);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 30px;
            box-shadow: 0 10px 25px rgba(255, 136, 0, 0.25);
        }

        /* NUMBER */
        .stat-card h3 {
            font-size: 34px;
            font-weight: 700;
            color: #222;
            margin-bottom: 10px;
        }

        /* TEXT */
        .stat-card p {
            color: #666;
            margin: 0;
            line-height: 1.6;
            font-size: 15px;
        }

        /* MOBILE */
        @media(max-width:991px) {

            .learning-wrapper {
                grid-template-columns: 1fr;
            }

            .learning-video {
                min-height: 320px;
            }

            .learning-video iframe {
                min-height: 320px;
            }
        }

        @media(max-width:576px) {

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 32px;
            }

            .learning-section {
                padding: 60px 0;
            }
        }


        /* video side   */


        /* people say   */
        /* ===== REVIEWS SECTION ===== */

        #reviews {
            padding: 70px 0;
            background: #fff7f0;
        }

        /* Heading */
        #reviews .heading {
            font-size: 42px;
            font-weight: 700;
            color: #222;
            margin-bottom: 12px;
        }

        #reviews .divider-center {
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #ff8a00, #ffb347);
            margin: 15px auto;
            border-radius: 20px;
        }

        /* Slider Card */
        #review_slider .item {
            background: #fff;
            border-radius: 22px;
            padding: 35px 40px;
            margin: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255, 136, 0, 0.08);
            border: 1px solid rgba(255, 153, 51, 0.12);
            transition: 0.4s;
            position: relative;
        }

        #review_slider .item:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(255, 136, 0, 0.15);
        }

        /* ROUND IMAGE */
        .client_pic {
            width: 110px !important;
            height: 110px;
            object-fit: cover;
            border-radius: 50% !important;
            margin: 0 auto 18px;
            border: 5px solid #ffe7d1;
            box-shadow: 0 8px 25px rgba(255, 136, 0, 0.18);
        }

        /* NAME */
        #review_slider h4 {
            font-size: 28px;
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
        }

        /* POSITION */
        #review_slider .item p:nth-child(2) {
            color: #ff8a00;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* MESSAGE TEXT */
        #review_slider .item p:last-child {
            font-size: 15px;
            line-height: 1.9;
            color: #666;
            max-width: 900px;
            margin: auto;
            text-align: center;
        }

        /* QUOTE ICON */
        #review_slider .item::before {
            content: "\f10d";
            font-family: "FontAwesome";
            position: absolute;
            top: 20px;
            left: 25px;
            color: #ffd2a6;
            font-size: 28px;
        }

        /* OWL DOTS */
        .owl-dots {
            text-align: center;
            margin-top: 25px;
        }

        .owl-dot span {
            width: 12px;
            height: 12px;
            background: #ffd2a6 !important;
            border-radius: 50%;
            display: block;
            margin: 5px;
            transition: 0.3s;
        }

        .owl-dot.active span {
            width: 32px;
            border-radius: 20px;
            background: #ff8a00 !important;
        }

        /* ARROWS */
        .owl-nav {
            text-align: center;
            margin-top: 20px;
        }

        .owl-nav button {
            width: 45px;
            height: 45px;
            border-radius: 50% !important;
            background: #fff !important;
            color: #ff8a00 !important;
            margin: 0 8px;
            font-size: 22px !important;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .owl-nav button:hover {
            background: #ff8a00 !important;
            color: #fff !important;
        }

        /* MOBILE */
        @media(max-width:768px) {

            #reviews {
                padding: 55px 0;
            }

            #reviews .heading {
                font-size: 32px;
            }

            #review_slider .item {
                padding: 28px 20px;
            }

            .client_pic {
                width: 90px !important;
                height: 90px;
            }

            #review_slider h4 {
                font-size: 22px;
            }

            #review_slider .item p:last-child {
                font-size: 14px;
                line-height: 1.8;
            }
        }



        /**news */

        /* ===== NEWS SECTION ===== */

        #pricing {
            padding: 80px 0;
            background: linear-gradient(135deg, #fff8f2, #ffffff);
        }

        /* Heading */
        #pricing .heading {
            font-size: 42px;
            font-weight: 700;
            color: #222;
            margin-bottom: 12px;
        }

        #pricing .divider-center {
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #ff8a00, #ffb347);
            margin: 15px auto;
            border-radius: 20px;
        }

        #pricing .heading_space {
            color: #666;
            font-size: 16px;
            margin-bottom: 50px;
        }

        /* News Card */
        .pricing_item {
            background: #fff;
            border-radius: 25px;
            padding: 45px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 153, 51, 0.12);
            box-shadow: 0 12px 35px rgba(255, 136, 0, 0.08);
            transition: 0.4s ease;
            max-width: 850px;
            margin: auto;
        }

        .pricing_item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(255, 136, 0, 0.16);
        }

        /* Top Orange Border */
        .pricing_item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, #ff8a00, #ffb347);
        }

        /* News Title */
        .pricing_item h3 {
            font-size: 34px;
            font-weight: 700;
            color: #222;
            margin-bottom: 20px;
        }

        /* Highlight Result Text */
        .pricing_sentence {
            background: #fff3e6;
            color: #ff7b00;
            padding: 16px 22px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.8;
            margin-bottom: 30px;
            border: 1px dashed #ffb347;
        }

        /* Features */
        .pricing_list {
            list-style: none;
            padding: 0;
            margin: 0 0 35px;
        }

        .pricing_feature {
            padding: 14px 0;
            font-size: 15px;
            color: #666;
            border-bottom: 1px solid #f2f2f2;
        }

        .pricing_feature:last-child {
            border-bottom: none;
        }

        /* Button */
        .btn_common {
            background: linear-gradient(135deg, #ff8a00, #ffb347);
            color: #fff !important;
            padding: 13px 34px;
            border-radius: 50px;
            display: inline-block;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.4s ease;
            box-shadow: 0 10px 25px rgba(255, 136, 0, 0.22);
        }

        .btn_common:hover {
            background: linear-gradient(135deg, #ff7b00, #ff9800);
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(255, 136, 0, 0.30);
        }

        /* Blink Animation */
        .blink_text {
            animation: blinkEffect 1.5s infinite;
        }

        @keyframes blinkEffect {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        /* Mobile */
        @media(max-width:768px) {

            #pricing {
                padding: 60px 0;
            }

            .pricing_item {
                padding: 30px 20px;
            }

            #pricing .heading {
                font-size: 32px;
            }

            .pricing_item h3 {
                font-size: 24px;
            }

            .pricing_sentence {
                font-size: 14px;
                padding: 14px 16px;
            }

            .pricing_feature {
                font-size: 14px;
            }
        }


        /**news */


        /**importend details */

        /* ===== IMPORTANT DETAILS SECTION ===== */

        #news {
            padding: 85px 0;
            background: linear-gradient(135deg, #fff8f2, #ffffff);
            overflow: hidden;
        }

        /* HEADING */
        #news .heading {
            font-size: 42px;
            font-weight: 700;
            color: #222;
            margin-bottom: 18px;
        }

        #news .divider-left {
            width: 85px;
            height: 4px;
            background: linear-gradient(to right, #ff8a00, #ffb347);
            display: block;
            margin-top: 14px;
            border-radius: 20px;
        }

        /* CARD */
        #news_slider .item {
            padding: 15px;
        }

        .content_wrap {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(255, 136, 0, 0.08);
            transition: 0.4s ease;
            border: 1px solid rgba(255, 153, 51, 0.10);
            height: 500px;
        }

        .content_wrap:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 45px rgba(255, 136, 0, 0.16);
        }

        /* IMAGE */
        .content_wrap .image {
            position: relative;
            overflow: hidden;
        }

        .content_wrap .image img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: 0.5s ease;
        }

        .content_wrap:hover .image img {
            transform: scale(1.08);
        }

        /* SOFT ORANGE OVERLAY */
        .content_wrap .image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(255, 138, 0, 0.18),
                    rgba(255, 255, 255, 0.02));
        }

        /* CONTENT */
        .news_box {
            padding: 28px 24px;
            background: #fff;
        }

        /* TITLE */
        .news_box h4 {
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .news_box h4 a {
            font-size: 24px;
            font-weight: 700;
            color: #222;
            text-decoration: none;
            transition: 0.3s;
        }

        .news_box h4 a:hover {
            color: #ff8a00;
        }

        /* TEXT */
        .news_box p {
            color: #666;
            font-size: 15px;
            line-height: 1.9;
            margin-bottom: 22px;
        }

        /* BUTTON */
        .news_box .readmore {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 50px;
            background: linear-gradient(135deg, #ff8a00, #ffb347);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.4s ease;
            box-shadow: 0 8px 20px rgba(255, 136, 0, 0.22);
        }

        .news_box .readmore:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #ff7b00, #ff9800);
            box-shadow: 0 12px 28px rgba(255, 136, 0, 0.30);
        }

        /* OWL DOTS */
        .owl-dots {
            text-align: center;
            margin-top: 35px;
        }

        .owl-dot span {
            width: 12px;
            height: 12px;
            background: #ffd2a6 !important;
            border-radius: 50%;
            display: block;
            margin: 5px;
            transition: 0.3s;
        }

        .owl-dot.active span {
            width: 34px;
            border-radius: 20px;
            background: #ff8a00 !important;
        }

        /* OWL NAV */
        .owl-nav {
            text-align: center;
            margin-top: 25px;
        }

        .owl-nav button {
            width: 48px;
            height: 48px;
            border-radius: 50% !important;
            background: #fff !important;
            color: #ff8a00 !important;
            font-size: 22px !important;
            margin: 0 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .owl-nav button:hover {
            background: #ff8a00 !important;
            color: #fff !important;
        }

        /* MOBILE */
        @media(max-width:768px) {

            #news {
                padding: 60px 0;
            }

            #news .heading {
                font-size: 32px;
            }

            .content_wrap .image img {
                height: 90px;
            }

            .news_box {
                padding: 18px 18px;
            }

            .news_box h4 a {
                font-size: 20px;
            }

            .news_box p {
                font-size: 14px;
                line-height: 1.0;
            }
        }

        /**importend details */


        /* ================================
   GLOBAL GLASSY THEME COLORS
================================ */
        :root {
            --primary: #0654c2;
            --primary-light: #0A84FF;
            --white-glass: rgba(255, 255, 255, 0.55);
            --border-glass: rgba(255, 255, 255, 0.35);
            --shadow: 0 8px 32px rgba(15, 118, 110, 0.12);
        }

        /* ================================
   SECTION BACKGROUND
================================ */
        #courses,
        #reviews,
        #pricing,
        #news,
        .feature-section,
        .learning-section {
            background:
                linear-gradient(135deg,
                    rgba(240, 255, 252, 0.95),
                    rgba(255, 255, 255, 0.98));
        }

        /* ================================
   GLASS EFFECT COMMON CARD
================================ */
        .feature-box,
        .about-post,
        #course_slider .item,
        #review_slider .item,
        .pricing_item,
        .content_wrap,
        .stat-card {
            background: var(--white-glass);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            border: 1px solid var(--border-glass);

            box-shadow: var(--shadow);

            transition: 0.4s ease;
        }

        /* Hover */
        .feature-box:hover,
        .about-post:hover,
        #course_slider .item:hover,
        #review_slider .item:hover,
        .pricing_item:hover,
        .content_wrap:hover,
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow:
                0 18px 40px rgba(15, 118, 110, 0.18);
        }

        /* ================================
   HEADINGS
================================ */
        .heading,
        .section-title h2,
        .school-about-section h2,
        .feature-box h4,
        .about-post h4,
        #course_slider h3 a,
        .news_box h4 a,
        .pricing_item h3,
        #review_slider h4 {
            color: #0654c2 !important;
        }

        /* ================================
   DIVIDERS
================================ */
        .divider-left,
        .divider-center {
            background:
                linear-gradient(to right,
                    #0654c2,
                    #0A84FF) !important;
        }

        /* ================================
   ICONS
================================ */
        .feature-icon,
        .icon-box {
            background:
                linear-gradient(135deg,
                    #0654c2,
                    #0A84FF);

            box-shadow:
                0 10px 25px rgba(15, 118, 110, 0.25);
        }

        /* ================================
   BUTTONS
================================ */
        .btn_common,
        .btn_common.blue,
        .news_box .readmore {
            background:
                linear-gradient(135deg,
                    #0654c2,
                    #0A84FF) !important;

            color: #fff !important;

            border: none;

            box-shadow:
                0 10px 25px rgba(15, 118, 110, 0.22);
        }

        .btn_common:hover,
        .btn_common.blue:hover,
        .news_box .readmore:hover {
            background:
                linear-gradient(135deg,
                    #115e59,
                    #0654c2) !important;

            box-shadow:
                0 15px 30px rgba(15, 118, 110, 0.30);
        }

        /* ================================
   IMAGE OVERLAY
================================ */
        #course_slider .image::after,
        .content_wrap .image::after {
            background:
                linear-gradient(to top,
                    rgba(15, 118, 110, 0.18),
                    rgba(255, 255, 255, 0.05));
        }

        /* ================================
   DOTS
================================ */
        .owl-dot span {
            background: #99f6e4 !important;
        }

        .owl-dot.active span {
            background: #0654c2 !important;
        }

        /* ================================
   NAV BUTTONS
================================ */
        .owl-nav button {
            color: #0654c2 !important;
            background: rgba(255, 255, 255, 0.8) !important;

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            border: 1px solid rgba(15, 118, 110, 0.1);
        }

        .owl-nav button:hover {
            background: #0654c2 !important;
            color: #fff !important;
        }

        /* ================================
   FEATURE BOX TOP SHAPES
================================ */
        .feature-box:before,
        .about-post:before {
            background:
                linear-gradient(135deg,
                    #0654c2,
                    #0A84FF);

            opacity: 0.08;
        }

        /* ================================
   NEWS ALERT
================================ */
        .blink_text {
            color: #0654c2;
            text-shadow: 0 0 8px rgba(15, 118, 110, 0.4);
        }

        /* ================================
   PRICE / IMPORTANT TEXT
================================ */
        .pricing_sentence {
            background: rgba(15, 118, 110, 0.08);

            border: 1px dashed rgba(15, 118, 110, 0.25);

            color: #4354f1;
        }

        /* ================================
   CLIENT IMAGE BORDER
================================ */
        .client_pic {
            border: 5px solid rgba(15, 118, 110, 0.12);
        }

        /* ================================
   GLASSY HEADER EFFECT OPTIONAL
================================ */
        .glass-effect {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            border: 1px solid rgba(255, 255, 255, 0.25);

            box-shadow:
                0 8px 32px rgba(15, 118, 110, 0.10);

            border-radius: 24px;
        }



        /* slider */
        .hero-title {
            display: inline-block;

            padding: 18px 18px;

            border-radius: 24px;

            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, 0.55),
                    rgba(255, 255, 255, 0.25));

            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);

            border: 1px solid rgba(255, 255, 255, 0.35);

            box-shadow:
                0 10px 35px rgba(15, 118, 110, 0.15);

            color: #0654c2;

            font-size: 48px;
            font-weight: 800;

            line-height: 1.3;

            letter-spacing: 0.5px;

            position: relative;

            overflow: hidden;

            transition: 0.4s ease;
        }

        /* Glow Shape */
        .hero-title::before {
            content: "";

            position: absolute;

            top: -40px;
            right: -40px;

            width: 140px;
            height: 140px;

            background:
                radial-gradient(rgba(20, 184, 166, 0.25),
                    transparent);

            border-radius: 50%;
        }

        /* Hover */
        .hero-title:hover {
            transform: translateY(-5px);

            box-shadow:
                0 18px 45px rgba(15, 118, 110, 0.22);
        }

        /* Mobile */
        @media(max-width:768px) {

            .hero-title {
                font-size: 30px;

                padding: 18px 22px;

                border-radius: 18px;
            }
        }

        /* ====================================
   FIX SLIDER HEIGHT EXACTLY 350px
==================================== */

        /* MAIN SLIDER */
        .rev_slider_wrapper,
        #rev_slider,
        .rev_slider,
        .rev_slider ul,
        .rev_slider ul li,
        .tp-bgimg,
        .rev-slidebg {
            height: 100% !important;
            min-height: 350px !important;
            max-height: 100% !important;
        }

        /* IMAGE FIT */
        .rev-slidebg,
        .tp-bgimg {
            width: 100% !important;
            object-fit: cover !important;
            object-position: center center !important;
        }

        /* REMOVE EXTRA SPACE */
        .rev_slider_wrapper {
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        .rev_slider {
            margin-bottom: 0 !important;
        }

        /* REMOVE GAP BELOW SLIDER */
        .rev_slider_wrapper+section,
        .rev_slider_wrapper+div {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        /* ABOUT SECTION */
        #about {
            margin-top: 0 !important;
            padding-top: 50px !important;
        }

        /* MOBILE */
        @media(max-width:768px) {

            .rev_slider_wrapper,
            #rev_slider,
            .rev_slider,
            .rev_slider ul,
            .rev_slider ul li,
            .tp-bgimg,
            .rev-slidebg {
                height: 350px !important;
                min-height: 350px !important;
            }
        }
    </style>