
<!doctype html>
<html lang="en"></html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>Little Hearts | Rules and Regulations</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">


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
<link rel="icon" href="<?php echo base_url('assets/images/favicon.png')?>">


<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
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
<section id="house_system" class="padding bg_light">
  <div class="container">

    <!-- Big Image -->
    <div class="row">
      <div class="col-md-12">
        <div class="house_banner">
          <img src="<?php echo base_url('assets/images/main_image/house.jpg'); ?>" 
               alt="House System" 
               class="img-responsive">
        </div>
      </div>
    </div>

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_top">
          <h2>Our House System</h2>
          <p>
            The House System encourages teamwork, leadership, discipline, and healthy competition among students through academic, cultural, and sports activities.
          </p>
        </div>
      </div>
    </div>

    <!-- House Cards -->
    <div class="row margin_top">

      <!-- Ragam -->
      <div class="col-md-3 col-sm-6">
        <div class="house_box ragam" style="background:#dc006e;">
          <h3>Ragam</h3>
         
        </div>
      </div>

      <!-- Sruthi -->
      <div class="col-md-3 col-sm-6">
        <div class="house_box sruthi" style="background:#ffffff; color:#333; border:2px solid #ddd;">
          <h3>Sruthi</h3>
          
        </div>
      </div>

      <!-- Thalam -->
      <div class="col-md-3 col-sm-6">
        <div class="house_box thalam" style="background:#d7da81; color:#333;">
          <h3>Thalam</h3>
       
        </div>
      </div>

      <!-- Layam -->
      <div class="col-md-3 col-sm-6">
        <div class="house_box layam" style="background:#9ad3ff; color:#333;">
          <h3>Layam</h3>
         
        </div>
      </div>

    </div>
  </div>
</section>

<style>

.house_banner img{
    width:100%;
    height:500px;
    object-fit:cover;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

.house_box{
    padding:40px 25px;
    border-radius:20px;
    text-align:center;
    margin-top:30px;
    min-height:250px;
    transition:0.4s;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* Ragam */
.house_box.ragam h3,
.house_box.ragam p{
    color:#ffe6f3;
}

/* Sruthi */
.house_box.sruthi h3,
.house_box.sruthi p{
    color:#444;
}

/* Thalam */
.house_box.thalam h3,
.house_box.thalam p{
    color:#5a4b00;
}

/* Layam */
.house_box.layam h3,
.house_box.layam p{
    color:#004d73;
}
.house_box p{
    font-size:16px;
    line-height:28px;
    color:#fff;
}

.house_box:hover{
    transform:translateY(-10px);
}

.house_box:nth-child(2) h3,
.house_box:nth-child(2) p{
    color:#333 !important;
}

@media screen and (max-width:768px){

.house_banner img{
    height:250px;
}

.house_box{
    min-height:auto;
}

}

</style>


