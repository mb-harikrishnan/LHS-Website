<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | News</title>

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
  <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">


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
<!-- News Section -->
<section id="news" class="padding bg_light">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_bottom">
          <h2>Latest News</h2>
          <p>Stay updated with the latest announcements and activities</p>
        </div>
      </div>
    </div>

    <!-- News List -->
    <div class="row">
      <div class="col-md-12">

      <?php foreach($news_list as $value){

        if($value->c_news!=''){
        
        ?>

        <div class="news_box">
          <h4><?php echo $value->c_title;?></h4>
          <p>
            <?php echo $value->c_news;?>
          </p>
          <span><i class="fa fa-calendar"></i>    <?php echo date('F j Y', strtotime($value->d_date)); ?>
</span>
        </div>

        <?php }else{  ?>

         <div class="news_box">NO NEWS</div>

         <?php } }?>

       

      </div>
    </div>

  </div>
</section>

<style>
  .news_box {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #0654c2;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
  }

  .news_box h4 {
    margin-top: 0;
    color: #222;
    font-weight: 600;
  }

  .news_box p {
    color: #666;
    margin: 10px 0;
  }

  .news_box span {
    color: #888;
    font-size: 14px;
  }
</style>