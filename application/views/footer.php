<style>
    footer::before{
        display: none;
    }
    .footer_logo {
    display: flex;
    
    width: fit-content;
}

.logo-brand img
 {
    width: 227px;

}
</style>

<footer class="lh-footer">

    <!-- Floating education doodles (decorative background) -->
    <div class="lh-footer-float" aria-hidden="true">
        <i class="fa fa-book"></i>
        <i class="fa fa-pencil"></i>
        <i class="fa fa-graduation-cap"></i>
        <i class="fa fa-flask"></i>
        <i class="fa fa-calculator"></i>
        <i class="fa fa-globe"></i>
        <i class="fa fa-music"></i>
        <i class="fa fa-paint-brush"></i>
        <i class="fa fa-futbol-o"></i>
        <i class="fa fa-lightbulb-o"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-heart"></i>
    </div>

    <div class="container">
        <div class="row">

            <!-- About -->
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">About Us<span class="divider-left"></span></h3>
                <a href="<?php echo base_url('home_page'); ?>" class="footer_logo bottom25">
                    <img style="  filter: brightness(0) invert(1);
" src="<?php echo base_url('assets/images/main_image/logonew12.png'); ?>" alt="Little Hearts School">
                </a>
                <p>Providing quality education, creativity, and values to help students achieve academic excellence and personal growth.</p>
                <ul class="social_icon top25">
                    <li><a href="https://www.facebook.com/LittleHeartsSchool" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="<?php echo base_url(); ?>" class="twitter"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="<?php echo base_url(); ?>" class="youtube"><i class="fa fa-youtube-play"></i></a></li>
                    <li><a href="<?php echo base_url(); ?>" class="instagram"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">Quick Links<span class="divider-left"></span></h3>
                <ul class="links">
                    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-angle-right"></i>Home</a></li>
                    <li><a href="<?php echo base_url('about_us'); ?>"><i class="fa fa-angle-right"></i>About Us</a></li>
                    <li><a href="<?php echo base_url('mission_vision'); ?>"><i class="fa fa-angle-right"></i>Mission &amp; Vision</a></li>
                    <li><a href="<?php echo base_url('admissions'); ?>"><i class="fa fa-angle-right"></i>Admissions</a></li>
                    <li><a href="<?php echo base_url('vaccancy'); ?>"><i class="fa fa-angle-right"></i>Vacancies</a></li>
                    <li><a href="<?php echo base_url('contact'); ?>"><i class="fa fa-angle-right"></i>Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-4 col-sm-4 footer_panel bottom25">
                <h3 class="heading bottom25">Keep in Touch <span class="divider-left"></span></h3>
                <p class="address"><i class="fa fa-map-marker"></i>AFFILIATED TO CBSE, NEW DELHI NO.930601, KIZHAKKEPRAM, N. PARAVUR - 683 513</p>
                <p class="address"><i class="fa fa-phone"></i>+91-9744693905&nbsp;|&nbsp;0484-2446939</p>
                <p class="address"><i class="fa fa-envelope"></i><a href="mailto:littleheartsschool97@gmail.com">littleheartsschool97@gmail.com</a></p>
                
            </div>

        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>Copyright &copy; 2016 <a href="<?php echo base_url('home_page'); ?>">Little Hearts School</a>. All rights reserved. | Developed and Managed by - Little Hearts School IT Department.</p>
                  
                </div>
            </div>
        </div>
    </div>
</footer>
<!--FOOTER ends-->

<script src="<?php echo base_url('assets/js/jquery-2.2.3.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootsnav.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.appear.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-countTo.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/functions.js'); ?>"></script>

<!-- NEW THEME: global design system styles + scripts (external) -->
<!-- NEW THEME: global scripts -->
<script src="<?php echo base_url('assets/js/school-theme.js'); ?>"></script>

</body>
</html>
