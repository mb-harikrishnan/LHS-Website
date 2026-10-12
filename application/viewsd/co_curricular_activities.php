<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Co-Curricular Activities</title>

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

<!-- Co-Curricular Activities Section -->
<section id="activities" class="padding">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_bottom">
          <h2>Co-Curricular Activities</h2>
          <p>
            We encourage students to explore their talents through a wide range of co-curricular and sports activities. </p>
        </div>
      </div>
    </div>

    <div class="row">
      <?php foreach($all_images as $value){ ?>

      <!-- Activity Box -->
      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
          
      <?php 
       if ($value->c_type == 'Library') 
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
        <h4>Library</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
        <?php 
       if ($value->c_type == 'Extra_Curricular_Activities') 
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
                <h4>Extra Curricular Activities</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Sports') 
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
                     <h4>Sports (Indoor & Outdoor)</h4>
        </div>
      </div>

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

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Dance') 
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
                   <h4>Dance</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Music') 
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
                        <h4>Music</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Yoga') 
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
                      <h4>Yoga</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Karate') 
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
                      <h4>Karate</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Chess') 
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
               <h4>Chess</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Drawing') 
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
               <h4>Drawing</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Painting') 
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
                  <h4>Painting</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Roller_Skating') 
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
                  <h4>Roller Skating</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Transportation_Facility') 
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
                  <h4>Transportation Facility</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Educational_Tours_Excursions') 
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
                  <h4>Educational Tours / Excursions</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Computer_Labs') 
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
                  <h4>Computer Labs</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Science_Labs') 
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
                  <h4>Science Labs</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Smart_Class_Facilities') 
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
                  <h4>Smart Class Facilities</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
<?php 
       if ($value->c_type == 'Stationary_to_Students') 
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
                  <h4>Stationary to Students</h4>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="activity_box">
       <?php 
       if ($value->c_type == 'Low_Achievers') 
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
          
                  <h4>Special Classes for Low Achievers</h4>
        </div>
      </div>


     <?php }?>
    </div>
  </div>
</section>



<style>
  /* Activity Section */
  .activity_box {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: 0.3s;
    text-align: center;
  }

  .activity_box:hover {
    transform: translateY(-5px);
  }

  .activity_box img {
    width: 100%;
    height: 220px;
    object-fit: cover;
  }

  .activity_box h4 {
    padding: 15px 10px;
    font-size: 18px;
    font-weight: 600;
    color: #222;
  }
</style>