<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Little Hearts | Vacancy</title>

    
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/edua-icons.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/animate.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.carousel.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.transitions.css') ?> ">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/cubeportfolio.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/settings.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootsnav.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/loader.css') ?>">
  <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
</head>


<!--Search-->
<div id="search">
  <button type="button" class="close">×</button>
  <form>
    <input type="search" value="" placeholder="Search here...."  required/>
    <button type="submit" class="btn btn_common blue">Search</button>
  </form>
</div>

<!--Page Header-->
<section class="page_header padding-top">
  <div class="container">
    <div class="row">
      <div class="col-md-12 page-content">
        <h1>Contact Us</h1>
        <p>We would love to hear from you! Whether you have questions, feedback, admissions enquiries, or need any assistance, feel free to contact us.</p>  
        <div class="page_nav">
      <span>You are here:</span> <a href="<?echo base_url()?>">Home</a> <span><i class="fa fa-angle-double-right"></i>Contact Us</span>
      </div>
      </div>
    </div>
  </div>
</section>



<!--Contact Deatils -->
<section id="contact" class="padding">
  <div class="container">
    <div class="row padding-bottom">
      <div class="col-md-4 contact_address heading_space wow fadeInLeft" data-wow-delay="4500ms">
        <h2 class="heading heading_space">Get in Touch <span class="divider-left"></span></h2>
        <p>
        We would love to hear from you! Whether you have questions, feedback, admissions enquiries, or need any assistance, feel free to contact us. Our team is always ready to help and provide you with the information you need.
        </p>
        <div class="address">
          <i class="icon icon-map-pin border_radius"></i>
          <h4>Visit Us</h4>
          <p>KIZHAKKEPRAM, N. PARAVUR - 683 513.</p>
        </div>
        <div class="address">
          <i class="icon icon-mail border_radius"></i>
          <h4>Email Us</h4>
          <p><a href="littleheartsschool97@gmail.com">littleheartsschool97@gmail.com</a></p>
        </div>
        <div class="address">
          <i class="icon icon-phone4 border_radius"></i>
          <h4>Call Us</h4>
          <p>+91-9744693905|0484-2446939</p>
        </div>
      </div>
      <div class="col-md-8 wow fadeInRight" data-wow-delay="4500ms">
        <h2 class="heading heading_space">Fill the Contact Form<span class="divider-left"></span></h2>
        <form class="form-inline findus" id="contact-form" onSubmit="return false">
          <div class="row">
            <div class="col-md-12">
              <div id="result"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-sm-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Name"  name="name" id="name" >
              </div>
            </div>
            <div class="col-md-4 col-sm-4">
              <div class="form-group">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" >
              </div>
            </div>
            <div class="col-md-4 col-sm-4">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Mobile" name="mobile" id="mobile" >
              </div>
            </div>
            <div class="col-md-12">
              <textarea placeholder="Comment"  name="message" id="message"></textarea>
<button type="submit" class="btn_common yellow border_radius" id="btn_submit">
    Submit
</button>            </div>
          </div>
        </form>
        <ul class="social_icon black top30">
          <li><a href="#." class="facebook"><i class="fa fa-facebook"></i></a></li>
          <li><a href="#." class="twitter"><i class="icon-twitter4"></i></a></li>
          <li><a href="#." class="dribble"><i class="icon-dribbble5"></i></a></li>
          <li><a href="#." class="instagram"><i class="icon-instagram"></i></a></li>
        </ul>
      </div>
    </div>
    <!-- <div class="row wow bounceIn" data-wow-delay="300ms">
      <div class="col-md-12">
        <div id="map" style="width:100%; height:400px;" ></div>
      </div>
    </div> -->

   <div class="row wow bounceIn" data-wow-delay="300ms">
    <div class="col-md-12">
        <iframe 
            src="https://www.google.com/maps?q=Little+Hearts+School+North+Paravur&output=embed"
            width="100%"
            height="450"
            style="border:0; border-radius:10px;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>
  </div>
</section>

<script src="<?php echo base_url('assets/js/jquery-2.2.3.js')?>"></script>

<script src="<?php echo base_url('assets/js/jquery.validate.min.js')?>"></script>

<style>
.error{
    color:red;
    font-size:14px;
    margin-top:5px;
    display:block;
}

.alert{
    margin-top:15px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){

    // Allow only numbers in mobile
    $("#mobile").on("input", function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Remove error while typing
    $("#name, #email, #mobile, #message").on("keyup change", function(){

        $(this).next(".error").remove();

    });

    $("#btn_submit").click(function(e){

        e.preventDefault();

        $(".error").remove();

        var name    = $("#name").val().trim();
        var email   = $("#email").val().trim();
        var mobile  = $("#mobile").val().trim();
        var message = $("#message").val().trim();

        var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        var valid = true;

        // Name validation
        if(name == '')
        {
            $("#name").after('<span class="error">Enter Name</span>');
            valid = false;
        }

        // Email validation
        if(email == '')
        {
            $("#email").after('<span class="error">Enter Email</span>');
            valid = false;
        }
        else if(!email_pattern.test(email))
        {
            $("#email").after('<span class="error">Enter Valid Email</span>');
            valid = false;
        }

        // Mobile validation
        if(mobile == '')
        {
            $("#mobile").after('<span class="error">Enter Mobile Number</span>');
            valid = false;
        }
        else if(mobile.length != 10)
        {
            $("#mobile").after('<span class="error">Mobile Number Must Be 10 Digits</span>');
            valid = false;
        }

        // Message validation
        if(message == '')
        {
            $("#message").after('<span class="error">Enter Message</span>');
            valid = false;
        }

        if(valid == false)
        {
            return false;
        }

        $.ajax({

            url:"<?php echo base_url('submit_contact'); ?>",
            type:"POST",

            data:{
                name:name,
                email:email,
                mobile:mobile,
                message:message
            },

            beforeSend:function(){

                $("#btn_submit").html('Please Wait...');
                $("#btn_submit").prop('disabled', true);

            },

success:function(response)
{
    $("#btn_submit").html('Submit');
    $("#btn_submit").prop('disabled', false);

    response = response.trim();

    if(response.indexOf('"status":1') !== -1)
    {
        $("#contact-form")[0].reset();

        Swal.fire({
            toast: true,
            position: 'bottom',
            icon: 'success',
            title: 'Submitted Successfully',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    }
    else
    {
        Swal.fire({
            toast: true,
            position: 'bottom',
            icon: 'error',
            title: 'Something Went Wrong',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    }
},
            error:function(){

                $("#btn_submit").html('Submit');
                $("#btn_submit").prop('disabled', false);

                $("#result").html(
                    "<div class='alert alert-danger'>Server Error</div>"
                );

            }

        });

    });

});
</script>