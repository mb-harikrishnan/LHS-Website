
<body>
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
        <a href="index.html" class="footer_logo bottom25"><img src="<?php echo base_url('assets/images/main_image/school_logo.png')?>" alt="Edua"></a>
        <p>          Providing quality education, creativity, and values to help students achieve academic excellence and personal growth.</p>
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
          <li><a href="<?php echo base_url('about_us') ?>"><i class="icon-chevron-small-right"></i>Company</a></li>
          <!-- <li><a href="<?php echo base_url('services') ?>"><i class="icon-chevron-small-right"></i>Services</a></li> -->
          <li><a href="<?php echo base_url('teachers') ?>"><i class="icon-chevron-small-right"></i>Our Team</a></li>
          <li><a href="<?php echo base_url('mission_vision') ?>"><i class="icon-chevron-small-right"></i>Mission & Vision</a></li>
          <li><a href="<?php echo base_url('certifications') ?>"><i class="icon-chevron-small-right"></i>Certifications</a></li>
          <!-- <li><a href="#."><i class="icon-chevron-small-right"></i>Blog</a></li> -->
          <!-- <li><a href="#."><i class="icon-chevron-small-right"></i>Shop</a></li> -->
          <!-- <li><a href="#."><i class="icon-chevron-small-right"></i>Privacy Policy</a></li> -->
          <!-- <li><a href="#."><i class="icon-chevron-small-right"></i>Contact Us</a></li> -->
        </ul>
      </div>
      <div class="col-md-4 col-sm-4 footer_panel bottom25">
        <h3 class="heading bottom25">Keep in Touch <span class="divider-left"></span></h3>
        <p class=" address"><i class="icon-map-pin"></i>AFFILIATED TO CBSE, NEW DELHI NO.930601, KIZHAKKEPRAM, N. PARAVUR - 683 513</p>
        <p class=" address"><i class="icon-phone"></i>+91-9744693905|0484-2446939</p>
        <p class=" address"><i class="icon-mail"></i><a href="mailto:littleheartsschool97@gmail.com">littleheartsschool97@gmail.com</a></p>
        <img src="<?php echo base_url('assets/images/footer-map.png')?>" alt="we are here" class="img-responsive">
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
   /* ===== FOOTER DESIGN ===== */

footer{
    background:linear-gradient(135deg,#f0fffc 0%, #e6fffb 100%);
    padding-top:70px;
    position:relative;
    overflow:hidden;
    border-top:1px solid rgba(15,118,110,0.15);
}

/* Footer Logo */
.footer_logo img{
    max-width:180px;
}

/* Heading */
footer .heading{
    color:#222;
    font-size:28px;
    font-weight:700;
    margin-bottom:22px;
}

footer .divider-left{
    width:70px;
    height:4px;
    background:linear-gradient(to right,#0f766e,#14b8a6);
    display:block;
    margin-top:12px;
    border-radius:20px;
}

/* Paragraph */
footer p{
    color:#666;
    line-height:1.9;
    font-size:15px;
}

/* Quick Links */
.links{
    list-style:none;
    padding:0;
    margin:0;
}

.links li{
    margin-bottom:14px;
}

.links li a{
    color:#555;
    font-size:15px;
    text-decoration:none;
    transition:0.3s ease;
    display:inline-block;
}

.links li a i{
    color:#0f766e;
    margin-right:10px;
    transition:0.3s;
}

.links li a:hover{
    color:#0f766e;
    transform:translateX(5px);
}

/* Contact */
.address{
    margin-bottom:18px;
    display:flex;
    align-items:flex-start;
    gap:12px;
}

.address i{
    color:#0f766e;
    font-size:18px;
    margin-top:4px;
}

/* Email */
.address a{
    color:#555;
    text-decoration:none;
}

.address a:hover{
    color:#0f766e;
}

/* Social Icons */
.social_icon{
    padding:0;
    margin:25px 0 0;
    list-style:none;
    display:flex;
    gap:12px;
}

.social_icon li a{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#0f766e;
    font-size:18px;
    box-shadow:0 8px 20px rgba(15,118,110,0.12);
    transition:0.4s ease;
}

.social_icon li a:hover{
    background:linear-gradient(135deg,#0f766e,#14b8a6);
    color:#fff;
    transform:translateY(-4px);
}

/* Footer Map */
.footer_panel img{
    margin-top:15px;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
}

/* COPYRIGHT */
.copyright{
    background:#fff;
    padding:22px 0;
    border-top:1px solid rgba(15,118,110,0.12);
}

.copyright p{
    margin:5px 0;
    color:#666;
    font-size:14px;
}

.copyright a{
    color:#0f766e;
    font-weight:600;
    text-decoration:none;
}

/* SCROLL TOP */
.scrollToTop{
    width:48px;
    height:48px;
    border-radius:50%;
    background:linear-gradient(135deg,#0f766e,#14b8a6);
    color:#fff !important;
    text-align:center;
    line-height:48px;
    position:fixed;
    bottom:25px;
    right:25px;
    z-index:999;
    box-shadow:0 10px 25px rgba(15,118,110,0.25);
    transition:0.4s;
}

.scrollToTop:hover{
    transform:translateY(-5px);
}

/* MOBILE */
@media(max-width:768px){

    footer{
        padding-top:55px;
    }

    footer .heading{
        font-size:24px;
    }

    .footer_panel{
        margin-bottom:35px;
    }

    .social_icon{
        justify-content:flex-start;
    }
}

/* FIX FOOTER LINK COLORS */

.links li a,
.links li a:visited,
.links li a:focus{
    color:#555 !important;
}

/* Hover */
.links li a:hover{
    color:#0f766e !important;
}

/* Social Icons Fix */
.social_icon li a{
    color:#0f766e !important;
    background:#fff !important;
}

/* Social Hover */
.social_icon li a:hover{
    color:#fff !important;
    background:linear-gradient(135deg,#0f766e,#14b8a6) !important;
}

/* About Text */
.footer_panel p{
    color:#666 !important;
}

/* Contact Links */
.footer_panel .address,
.footer_panel .address a{
    color:#555 !important;
}

/* Footer Bottom */
.copyright p{
    color:#666 !important;
}

.copyright a{
    color:#0f766e !important;
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










    
</body>