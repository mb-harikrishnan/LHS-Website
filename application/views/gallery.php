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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>




<div class="container">

    <h1 class="gallery-title">Image Gallery</h1>

    <!-- FILTER BUTTONS -->
    <div class="filter-buttons">
        <button class="active" onclick="filterGallery('all', this)">All</button>
        <button onclick="filterGallery('nature', this)">Nature</button>
        <button onclick="filterGallery('cars', this)">Cars</button>
        <button onclick="filterGallery('food', this)">Food</button>
    </div>

    <!-- GALLERY -->
    <div class="row">

        <!-- Nature -->
        <div class="col-md-4 gallery-item nature">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?1">
                <div class="overlay">Nature Image</div>
            </div>
        </div>

        <div class="col-md-4 gallery-item nature">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?2">
                <div class="overlay">Nature Image</div>
            </div>
        </div>

        <!-- Cars -->
        <div class="col-md-4 gallery-item cars">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?3">
                <div class="overlay">Car Image</div>
            </div>
        </div>

        <div class="col-md-4 gallery-item cars">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?4">
                <div class="overlay">Car Image</div>
            </div>
        </div>

        <!-- Food -->
        <div class="col-md-4 gallery-item food">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?5">
                <div class="overlay">Food Image</div>
            </div>
        </div>

        <div class="col-md-4 gallery-item food">
            <div class="gallery-card">
                <img src="https://picsum.photos/500/400?6">
                <div class="overlay">Food Image</div>
            </div>
        </div>

    </div>
</div>




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
