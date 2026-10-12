<a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
<!--Loader-->
<div class="loader">
    <div class="bouncybox">
        <div class="bouncy"></div>
    </div>
</div>

<!--Footer-->
<footer class="padding-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">About Us<span class="divider-left"></span></h3>
                <a href="index.html" class="footer_logo bottom25"><img src="<?php echo base_url('assets/images/main_image/school_logo.png') ?>" alt="Edua"></a>
                <p> Providing quality education, creativity, and values to help students achieve academic excellence and personal growth.</p>
                <ul class="social_icon top25">
                    <li><a href="https://www.facebook.com/LittleHeartsSchool" class="facebook"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="<?php echo base_url() ?>" class="twitter"><i class="icon-twitter4"></i></a></li>
                    <li><a href="<?php echo base_url() ?>" class="dribble"><i class="icon-dribbble5"></i></a></li>
                    <li><a href="<?php echo base_url() ?>" class="instagram"><i class="icon-instagram"></i></a></li>
                    <li><a href="<?php echo base_url() ?>" class="vimo"><i class="icon-vimeo4"></i></a></li>
                </ul>
            </div>
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">Quick Links<span class="divider-left"></span></h3>
                <ul class="links">
                    <li><a href="<?php echo base_url() ?>"><i class="icon-chevron-small-right"></i>Home</a></li>
                    <li><a href="<?php echo base_url('about_us') ?>"><i class="icon-chevron-small-right"></i>About Us</a></li>
                    <li><a href="<?php echo base_url('mission_vision') ?>"><i class="icon-chevron-small-right"></i>Mission & Vision</a></li>
                    <li><a href="<?php echo base_url('vaccancy') ?>"><i class="icon-chevron-small-right"></i>Vacancies</a></li>
                    <li><a href="<?php echo base_url('contact') ?>"><i class="icon-chevron-small-right"></i>Contact Us</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">Keep in Touch <span class="divider-left"></span></h3>
                <p class=" address"><i class="icon-map-pin"></i>AFFILIATED TO CBSE, NEW DELHI NO.930601, KIZHAKKEPRAM, N. PARAVUR - 683 513</p>
                <p class=" address"><i class="icon-phone"></i>+91-9744693905|0484-2446939</p>
                <p class=" address"><i class="icon-mail"></i><a href="mailto:littleheartsschool97@gmail.com">littleheartsschool97@gmail.com</a></p>
                <div class="row wow bounceIn" data-wow-delay="300ms">
                    <div class="col-md-12">
                        <iframe 
                            src="https://www.google.com/maps?q=Little+Hearts+School+North+Paravur&output=embed"
                            width="100%"
                            height="150"
                            style="border:0; border-radius:10px;"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Copyright &copy; 2016 <a href="#.">Little Hearts School</a>. all rights reserved.</p>
                <p>Developed and Managed by - Little Hearts School IT Department.</p>
            </div>
        </div>
    </div>
</div>
<!--FOOTER ends-->

<style>
    /* ===== GLASSY FOOTER — NO BOXES, FLOWING LAYOUT ===== */

    footer {
        background: linear-gradient(135deg, #0f2340 0%, #163a63 45%, #0a2e52 100%);
        padding-top: 80px;
        position: relative;
        overflow: hidden;
    }

    /* Soft glowing blobs for atmosphere */
    footer::before,
    footer::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        filter: blur(90px);
        z-index: 0;
        opacity: 0.35;
        pointer-events: none;
    }

    footer::before {
        width: 380px;
        height: 380px;
        background: #0A84FF;
        top: -140px;
        left: -100px;
    }

    footer::after {
        width: 420px;
        height: 420px;
        background: #35c7c1;
        bottom: -160px;
        right: -120px;
    }

    footer .container {
        position: relative;
        z-index: 1;
    }

    /* Columns sit directly on the gradient — divided by a thin glass line, no card background */
    .footer_panel {
        padding: 0 15px;
        position: relative;
    }

    /* Vertical divider between columns on desktop */
    @media(min-width:768px) {
        .footer_panel + .footer_panel::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 1px;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0.25), rgba(255,255,255,0));
        }
    }

    /* Footer Logo */
    .footer_logo {
        display: inline-block;
        padding: 8px;
        border-radius: 16px;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }

    .footer_logo img {
        max-width: 150px;
        display: block;
        border-radius: 10px;
    }

    /* Heading */
    footer .heading {
        color: #ffffff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: 0.3px;
    }

    footer .divider-left {
        width: 55px;
        height: 3px;
        background: linear-gradient(to right, #35c7c1, #0A84FF);
        display: block;
        margin-top: 10px;
        border-radius: 20px;
    }

    /* Paragraph */
    footer p {
        color: rgba(255,255,255,0.7);
        line-height: 1.9;
        font-size: 15px;
    }

    /* Quick Links */
    .links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .links li {
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        max-width: 220px;
    }

    .links li:last-child {
        border-bottom: none;
    }

    .links li a {
        color: rgba(255,255,255,0.75) !important;
        font-size: 15px;
        text-decoration: none;
        transition: 0.3s ease;
        display: inline-block;
    }

    .links li a i {
        color: #35c7c1;
        margin-right: 10px;
        transition: 0.3s;
    }

    .links li a:hover {
        color: #ffffff !important;
        transform: translateX(6px);
    }

    /* Contact */
    .address {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        color: rgba(255,255,255,0.75) !important;
    }

    .address i {
        color: #fff;
        font-size: 15px;
        margin-top: 2px;
        background: linear-gradient(135deg, #0A84FF, #35c7c1);
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(10, 132, 255, 0.35);
    }

    .address a {
        color: rgba(255,255,255,0.75) !important;
        text-decoration: none;
    }

    .address a:hover {
        color: #ffffff !important;
    }

    /* Social Icons - glassy circles, no card behind them */
    .social_icon {
        padding: 0;
        margin: 25px 0 0;
        list-style: none;
        display: flex;
        gap: 12px;
    }

    .social_icon li a {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff !important;
        font-size: 16px;
        transition: 0.4s ease;
    }

    .social_icon li a:hover {
        background: linear-gradient(135deg, #0A84FF, #35c7c1) !important;
        border-color: transparent;
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 10px 24px rgba(10, 132, 255, 0.4);
    }

    /* Map — subtle glass frame only around the iframe itself */
    .footer_panel .row {
        margin-top: 10px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.15);
        box-shadow: 0 12px 30px rgba(0,0,0,0.35);
    }

    /* COPYRIGHT strip */
    .copyright {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 22px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 1;
        margin-top: 50px;
    }

    .copyright p {
        margin: 5px 0;
        color: rgba(255,255,255,0.55) !important;
        font-size: 13px;
    }

    .copyright a {
        color: #35c7c1 !important;
        font-weight: 600;
        text-decoration: none;
    }

    /* SCROLL TOP */
    .scrollToTop {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(10, 132, 255, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff !important;
        text-align: center;
        line-height: 48px;
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 999;
        box-shadow: 0 10px 25px rgba(6, 84, 194, 0.35);
        transition: 0.4s;
    }

    .scrollToTop:hover {
        transform: translateY(-5px);
        background: rgba(53, 199, 193, 0.9);
    }

    /* MOBILE */
    @media(max-width:768px) {

        footer {
            padding-top: 55px;
        }

        footer .heading {
            font-size: 21px;
        }

        .footer_panel {
            margin-bottom: 40px;
        }

        .footer_panel + .footer_panel::before {
            display: none;
        }

        .social_icon {
            justify-content: flex-start;
        }
    }
</style>


<script src="<?php echo base_url('assets/js/jquery-2.2.3.js') ?>"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyAOBKD6V47-g_3opmidcmFapb3kSNAR70U"></script>
<script src="<?php echo base_url('assets/js/gmaps.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootsnav.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.appear.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-countTo.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.parallax-1.1.3.js') ?>"></script>
<script src="<?php echo base_url('assets/js/owl.carousel.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.cubeportfolio.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.themepunch.tools.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.themepunch.revolution.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/revolution.extension.layeranimation.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/revolution.extension.navigation.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/revolution.extension.parallax.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/revolution.extension.slideanims.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/revolution.extension.video.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/wow.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/functions.js') ?>"></script>