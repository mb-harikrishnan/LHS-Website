<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Curriculum</title>

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

<!-- Fee Regulations -->
<section id="fee_regulations" class="padding bg_light fee_regulations_section">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_bottom">
          <span class="fee_tag"></span>
          <h2>Gallery</h2>
          <!-- <p>
          </p> -->
        </div>
      </div>
    </div>


<div class="container">


    <!-- FILTER BUTTONS -->
     <div class="filter-buttons">

        <button class="active" onclick="filterGallery('all', this)">
            All
        </button>

        <?php foreach($all_types as $type) { ?>

            <button onclick="filterGallery('<?php echo strtolower($type->c_type); ?>', this)">

                  <?php echo ucwords(str_replace('_', ' ', $type->c_type)); ?>

            </button>

        <?php } ?>

    </div>
    <!-- GALLERY -->
     <div class="row">

        <?php foreach($all_images as $value) { ?>

            <div class="col-md-4 gallery-item <?php echo strtolower($value->c_type); ?>">

                <div class="gallery-card">

                    <img src="<?php echo base_url('assets/images/gallery/'.$value->c_image); ?>" 
                         class="img-fluid">

                    <div class="overlay">
                        <?php echo ucfirst($value->c_type); ?>
                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</div>


    </div>
</div>

</div>
</section>



<style>
body{
    background:#f4f6f9;
    font-family:Arial, sans-serif;
}

.gallery-title{
    text-align:center;
    font-size:40px;
    font-weight:bold;
    margin:40px 0 20px;
    color:#222;
}

.filter-buttons{
    text-align:center;
    margin-bottom:30px;
}

.filter-buttons button{
    border:none;
    padding:10px 25px;
    margin:5px;
    border-radius:30px;
    background:#e4e7ec;
    color:#333;
    font-weight:600;
    transition:0.3s;
}

.filter-buttons button.active,
.filter-buttons button:hover{
    background:#0d6efd;
    color:#fff;
}

.gallery-card{
    position:relative;
    overflow:hidden;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.gallery-card img{
    width:100%;
    height:250px;
    object-fit:cover;
    transition:0.4s;
}

.gallery-card:hover img{
    transform:scale(1.1);
}

.overlay{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    background:rgba(0,0,0,0.6);
    color:#fff;
    text-align:center;
    padding:10px;
    font-size:18px;
    opacity:0;
    transition:0.4s;
}

.gallery-card:hover .overlay{
    opacity:1;
}
</style>




<script>
function filterGallery(type, btn){

    let items = document.querySelectorAll('.gallery-item');
    let buttons = document.querySelectorAll('.filter-buttons button');

    buttons.forEach(button => {
        button.classList.remove('active');
    });

    btn.classList.add('active');

    items.forEach(item => {

        if(type === 'all'){
            item.style.display = 'block';
        }
        else if(item.classList.contains(type)){
            item.style.display = 'block';
        }
        else{
            item.style.display = 'none';
        }

    });
}
</script>
