<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Sports and Games</title>

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

<!-- Sports and Games Section -->
<section id="sports" class="padding">
  <div class="container">

    <div class="row">
      <div class="col-md-12 text-center">


        <div class="row">
          <div class="col-md-12 text-center">
            <div class="main_title margin_bottom">
              <h2>Sports and Games</h2>
              <p>
                Each individual in Little Hearts School is held in high esteem as they take part in various sports activities which enable them to develop their sportsman spirit.

                Our School conducts various indoor and outdoor activities.

                Training programmes are held in the school campus on all working days for the students. </p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="row">

      <!-- Activity Box -->


    <?php foreach($all_images as $value){ ?>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
       <?php 
       if ($value->c_type == 'Volley_Ball') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>        
           <h4>Volley Ball</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
      <?php 
       if ($value->c_type == 'Basket_Ball') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>   
                <h4>Basket Ball</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
         <?php 
       if ($value->c_type == 'Foot_Ball') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>         
          <h4>Foot Ball</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
      <?php 
       if ($value->c_type == 'Cricket') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>         
          <h4>Cricket</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
       <?php 
       if ($value->c_type == 'Kho-Kho') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>   
                <h4>Kho-Kho</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
      <?php 
       if ($value->c_type == 'Badminton') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>       
            <h4>Badminton</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
      <?php 
       if ($value->c_type == 'Roll_Ball') 
        {
            
        $img_path = base_url('assets/images/gallery/' . $value->c_images);
          ?>
                 <a href="<?php echo base_url('list_all_activities/'.$value->c_type); ?>">

                  <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
                  </a>  
      
          
        <?php } else { 
            $img_path = base_url('assets/images/empty_images/no-image-icon-6.png');
            ?>


            <img src="<?php echo $img_path; ?>" alt="Image" width="150">   
    
            
        <?php } ?>   
                <h4>Roll Ball</h4>
        </div>
      </div>

      <?php }?>


    </div>
  </div>
</section>



<style>
  /* ============================================================
   LITTLE HEARTS — v13 "SCRAPBOOK STICKER ALBUM" (internal CSS)
   Cards look like photos taped into a school scrapbook: striped
   washi tape + star sticker, candy-tint confetti tiles, ghosted
   placeholder icons, dashed perforation, crayon underline
   captions, folded page corner, collage tilt on hover.
   CSS ONLY — markup, PHP, links and DB untouched.
   ============================================================ */

/* ---- notebook-paper page canvas (matches the school theme) ---- */
#activities, #sports{
    --fl-navy:#0b2447; --fl-muted:#5b6b84; --fl-paper:#f6f9ff; --fl-gold:#f5a623;
    --acc:#8e6fe8;                          /* co-curricular accent */
    position:relative;
    background-color:var(--fl-paper);
    background-image:
        radial-gradient(circle at 8% 6%, color-mix(in srgb, var(--acc) 10%, transparent) 0, transparent 26%),
        radial-gradient(circle at 96% 92%, rgba(245,166,35,.12) 0, transparent 30%),
        radial-gradient(rgba(11,36,71,.055) 1.3px, transparent 1.3px);
    background-size:auto, auto, 26px 26px;
    overflow-x:hidden;
}
#sports{ --acc:#22a06b; }                   /* sports accent */

/* crayon doodle blobs floating at the page corners */
#activities::before, #sports::before{
    content:""; position:absolute; top:80px; right:3%; width:130px; height:130px; z-index:0;
    border-radius:42% 58% 62% 38%/48% 42% 58% 52%;
    border:3px dashed color-mix(in srgb, var(--acc) 38%, transparent);
    animation:lh-float 7s ease-in-out infinite; pointer-events:none;
}
#activities::after, #sports::after{
    content:""; position:absolute; bottom:120px; left:2.5%; width:88px; height:88px; z-index:0;
    border-radius:50%;
    background:radial-gradient(circle at 35% 35%, color-mix(in srgb, var(--fl-gold) 50%, transparent), transparent 62%);
    animation:lh-float 9s ease-in-out infinite reverse; pointer-events:none;
}
@keyframes lh-float{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-16px) rotate(8deg)}}

/* ---- heading: Fredoka with marker-highlight underline ---- */
#activities .main_title h2, #sports .main_title h2{
    position:relative; display:inline-block; z-index:1;
    font-family:'Fredoka','Baloo 2',cursive !important;
    color:var(--fl-navy); letter-spacing:.3px;
}
#activities .main_title h2::after, #sports .main_title h2::after{
    content:""; position:absolute; left:50%; bottom:-4px; width:110%; height:12px; z-index:-1;
    border-radius:6px; transform:translateX(-50%) rotate(-.6deg);
    background:linear-gradient(90deg,
        color-mix(in srgb, var(--acc) 28%, transparent),
        color-mix(in srgb, var(--fl-gold) 34%, transparent));
}
#activities .main_title p, #sports .main_title p{
    color:var(--fl-muted); font-size:15.5px; line-height:1.9; max-width:860px; margin:16px auto 0;
}
#activities .container, #sports .container{ position:relative; z-index:1; }

/* ---- flex rows so every card in a row shares one height ---- */
#activities .row, #sports .row{ display:flex; flex-wrap:wrap; }
#activities .row::before, #activities .row::after,
#sports .row::before, #sports .row::after{ display:none; }
#activities .row > [class*="col-"]{ margin-bottom:28px; }
#sports     .row > [class*="col-"]{ margin-bottom:28px; }

/* ============================================================
   THE CARD — one sticker page per activity
   ============================================================ */
#activities .activity_box, #sports .activity_box{
    --tint:#2f6fed; --tint-deep:#123a8f;    /* default tint (slot 1) */
    position:relative;
    display:flex; flex-direction:column;
    width:100%; height:100%;
    background:#fff;
    border:2px solid #e9eff9;
    border-radius:22px;
    box-shadow:7px 8px 0 rgba(11,36,71,.06), 0 18px 36px rgba(11,36,71,.10);
    transition:transform .4s cubic-bezier(.34,1.56,.64,1), box-shadow .4s ease, border-color .4s ease;
}

/* per-card candy tints cycling 6 colours (neighbours always differ) */
#activities .row > [class*="col-"]:nth-child(6n+2) .activity_box,
#sports     .row > [class*="col-"]:nth-child(6n+2) .activity_box{ --tint:#22a06b; --tint-deep:#0d6b45; }
#activities .row > [class*="col-"]:nth-child(6n+3) .activity_box,
#sports     .row > [class*="col-"]:nth-child(6n+3) .activity_box{ --tint:#f5a623; --tint-deep:#b97a06; }
#activities .row > [class*="col-"]:nth-child(6n+4) .activity_box,
#sports     .row > [class*="col-"]:nth-child(6n+4) .activity_box{ --tint:#8e6fe8; --tint-deep:#5b3fc0; }
#activities .row > [class*="col-"]:nth-child(6n+5) .activity_box,
#sports     .row > [class*="col-"]:nth-child(6n+5) .activity_box{ --tint:#e0475f; --tint-deep:#9c2537; }
#activities .row > [class*="col-"]:nth-child(6n+6) .activity_box,
#sports     .row > [class*="col-"]:nth-child(6n+6) .activity_box{ --tint:#0ea5e9; --tint-deep:#0b6f9e; }

/* scrapbook collage tilt, alternating down the grid */
#activities .row > [class*="col-"]:nth-child(odd)  .activity_box,
#sports     .row > [class*="col-"]:nth-child(odd)  .activity_box{ transform:rotate(-.9deg); }
#activities .row > [class*="col-"]:nth-child(even) .activity_box,
#sports     .row > [class*="col-"]:nth-child(even) .activity_box{ transform:rotate(.8deg); }

/* ---- washi tape + star sticker holding the "photo" down ---- */
#activities .activity_box::before, #sports .activity_box::before{
    content:"★";
    position:absolute; top:-13px; left:50%; z-index:6;
    display:flex; align-items:center; justify-content:center;
    width:92px; height:26px;
    transform:translateX(-50%) rotate(-3deg);
    font-size:13px; color:rgba(255,255,255,.95); line-height:1;
    background:repeating-linear-gradient(45deg, rgba(245,166,35,.9) 0 8px, rgba(245,166,35,.6) 8px 16px);
    border-left:2px dashed rgba(255,255,255,.75);
    border-right:2px dashed rgba(255,255,255,.75);
    border-radius:3px;
    box-shadow:0 3px 7px rgba(11,36,71,.18);
    transition:transform .4s cubic-bezier(.34,1.56,.64,1);
}
/* folded page corner */
#activities .activity_box::after, #sports .activity_box::after{
    content:"";
    position:absolute; right:2px; bottom:2px; z-index:5;
    width:26px; height:26px;
    background:linear-gradient(to top left, #e6edf8 47%, rgba(255,255,255,0) 50%);
    border-radius:0 0 18px 0;
    box-shadow:-3px -3px 6px rgba(11,36,71,.07);
    transition:width .35s ease, height .35s ease;
}

/* ---- media tile: real photo (wrapped in <a>) ---- */
#activities .activity_box a, #sports .activity_box a{
    position:relative;
    display:flex; align-items:center; justify-content:center;
    flex:1 0 auto; min-height:210px;
    overflow:hidden;
    border-radius:20px 20px 0 0;
    background:
        radial-gradient(rgba(255,255,255,.2) 1.6px, transparent 1.6px) 0 0/20px 20px,
        radial-gradient(circle at 50% 28%, rgba(255,255,255,.28), transparent 56%),
        linear-gradient(135deg, var(--tint), var(--tint-deep));
}
#activities .activity_box a img, #sports .activity_box a img{
    display:block; width:100% !important; height:100% !important;
    min-height:210px; object-fit:cover;
    filter:saturate(1.06);
    transition:transform .65s cubic-bezier(.22,1,.36,1);
}
/* star sticker badge on photo cards */
#activities .activity_box a::before, #sports .activity_box a::before{
    content:"★";
    position:absolute; top:12px; left:12px; z-index:3;
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    background:#fff; color:var(--tint); font-size:15px; line-height:1;
    box-shadow:0 4px 10px rgba(11,36,71,.25);
    transition:transform .4s cubic-bezier(.34,1.56,.64,1);
}
/* tint fade at the photo base + shine sweep on hover */
#activities .activity_box a::after, #sports .activity_box a::after{
    content:""; position:absolute; inset:0; z-index:2; pointer-events:none;
    background:
        linear-gradient(105deg, transparent 25%, rgba(255,255,255,.35) 50%, transparent 75%) no-repeat,
        linear-gradient(to top, color-mix(in srgb, var(--tint-deep) 48%, transparent), transparent 46%);
    background-size:60% 100%, 100% 100%;
    background-position:-80% 0, 0 0;
    transition:background-position .65s ease;
}
/* #activities .activity_box:hover a img, #sports .activity_box:hover a img{ transform:scale(1.09) rotate(.5deg); }
#activities .activity_box:hover a::after, #sports .activity_box:hover a::after{ background-position:170% 0, 0 0; }
#activities .activity_box:hover a::before, #sports .activity_box:hover a::before{ transform:rotate(-18deg) scale(1.15); } */

/* ---- media tile: placeholder icon (no <a>) → ghost porthole ---- */
#activities .activity_box > img, #sports .activity_box > img{
    display:block;
    flex:1 0 auto; min-height:210px;
    width:100% !important; height:auto !important;
    object-fit:contain;
    padding:42px 34px;
    border-radius:20px 20px 0 0;
    background:
        radial-gradient(rgba(255,255,255,.2) 1.6px, transparent 1.6px) 0 0/20px 20px,
        radial-gradient(circle at 50% 28%, rgba(255,255,255,.28), transparent 56%),
        linear-gradient(135deg, var(--tint), var(--tint-deep));
    filter:grayscale(1) opacity(.55);
    mix-blend-mode:multiply;
    transition:transform .45s cubic-bezier(.34,1.56,.64,1);
}
/* #activities .activity_box:hover > img, #sports .activity_box:hover > img{ transform:translateY(-5px) rotate(-3deg); } */

/* ---- caption: cream strip under the photo ---- */
#activities .activity_box h4, #sports .activity_box h4{
    position:relative;
    display:flex; align-items:center; justify-content:center;
    min-height:66px; margin:0; padding:12px 16px 15px;
    text-align:center;
    font-family:'Fredoka','Baloo 2',cursive !important;
    font-size:16.5px; font-weight:600; letter-spacing:.3px; line-height:1.35;
    color:var(--fl-navy);
    background:linear-gradient(color-mix(in srgb, var(--tint) 9%, #fff), #fff 72%);
    border-top:3px dashed color-mix(in srgb, var(--tint) 45%, #fff);
    border-radius:0 0 20px 20px;
}
/* crayon underline */
#activities .activity_box h4::before, #sports .activity_box h4::before{
    content:""; position:absolute; left:50%; bottom:6px; transform:translateX(-50%);
    width:34px; height:4px; border-radius:4px;
    background:linear-gradient(90deg, var(--tint), color-mix(in srgb, var(--fl-gold) 70%, var(--tint)));
    opacity:.85; transition:width .35s ease;
}
/* arrow slides in on hover */
#activities .activity_box h4::after, #sports .activity_box h4::after{
    content:"→"; margin-left:7px; color:var(--tint);
    opacity:0; transform:translateX(-8px);
    transition:opacity .3s ease, transform .3s ease;
}
/* #activities .activity_box:hover h4::before, #sports .activity_box:hover h4::before{ width:58px; }
#activities .activity_box:hover h4::after, #sports .activity_box:hover h4::after{ opacity:1; transform:translateX(0); } */

/* ---- hover: sticker lifts and straightens ---- */
/* #activities .row > [class*="col-"] .activity_box:hover,
#sports     .row > [class*="col-"] .activity_box:hover{
    transform:translateY(-10px) rotate(0deg);
    box-shadow:9px 12px 0 rgba(11,36,71,.07), 0 30px 58px rgba(11,36,71,.2);
    border-color:color-mix(in srgb, var(--tint) 55%, #fff);
} */
/* #activities .activity_box:hover::before, #sports .activity_box:hover::before{ transform:translateX(-50%) rotate(-1deg) scale(1.07); }
#activities .activity_box:hover::after, #sports .activity_box:hover::after{ width:32px; height:32px; } */

/* cascade on reveal … but instant hover response */
#activities .row > [class*="col-"]:nth-child(4n+2) .activity_box,
#sports     .row > [class*="col-"]:nth-child(4n+2) .activity_box{ transition-delay:.04s; }
#activities .row > [class*="col-"]:nth-child(4n+3) .activity_box,
#sports     .row > [class*="col-"]:nth-child(4n+3) .activity_box{ transition-delay:.08s; }
#activities .row > [class*="col-"]:nth-child(4n+4) .activity_box,
#sports     .row > [class*="col-"]:nth-child(4n+4) .activity_box{ transition-delay:.12s; }
/* #activities .row > [class*="col-"] .activity_box:hover,
#sports     .row > [class*="col-"] .activity_box:hover{ transition-delay:0s; } */

/* ---- responsive ---- */
@media (max-width:991px){
    #activities .activity_box a, #sports .activity_box a,
    #activities .activity_box a img, #sports .activity_box a img{ min-height:190px; }
    #activities .activity_box > img, #sports .activity_box > img{ min-height:190px; padding:36px 28px; }
}
@media (max-width:767px){
    #activities .row > [class*="col-"]:nth-child(n) .activity_box,
    #sports     .row > [class*="col-"]:nth-child(n) .activity_box{ transform:none; }
    #activities .activity_box a, #sports .activity_box a,
    #activities .activity_box a img, #sports .activity_box a img{ min-height:165px; }
    #activities .activity_box > img, #sports .activity_box > img{ min-height:165px; padding:30px 24px; }
    #activities .activity_box h4, #sports .activity_box h4{ font-size:15px; min-height:58px; }
    #activities .activity_box::before, #sports .activity_box::before{ width:74px; height:22px; top:-11px; font-size:11px; }
    #activities .activity_box::after, #sports .activity_box::after{ width:20px; height:20px; }
    #activities .main_title h2, #sports .main_title h2{ font-size:27px !important; }
    #activities::before, #sports::before{ width:70px; height:70px; }
}

/* ---- respect reduced-motion preferences ---- */
@media (prefers-reduced-motion:reduce){
    #activities *, #sports *{ animation:none !important; transition:none !important; }
}
</style>