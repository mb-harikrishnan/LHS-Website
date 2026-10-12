
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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



    <title>Images</title>

    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">

    <style>

        body{
            background:#f4f6f9;
            font-family:Arial, sans-serif;
        }

        .main_title{
            text-align:center;
            margin:40px 0;
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

        .gallery-img{
            width:100%;
            height:250px;
            object-fit:cover;
            transition:0.4s;
            cursor:pointer;
            display:block;
        }

        .gallery-card:hover .gallery-img{
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
            pointer-events:none;
        }

        .gallery-card:hover .overlay{
            opacity:1;
        }

        /* POPUP */

        .img-popup{
            display:none;
            position:fixed;
            z-index:99999;
            left:0;
            top:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.9);

            justify-content:center;
            align-items:center;
        }

        .img-popup img{
            max-width:90%;
            max-height:90%;
            border-radius:10px;
        }

        .close-btn{
            position:absolute;
            top:20px;
            right:35px;
            color:white;
            font-size:45px;
            cursor:pointer;
        }


        .popup-img-container{
            overflow:hidden;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        #popupImg{
            max-width:90%;
            max-height:90%;
            border-radius:10px;
            transition:transform 0.2s ease;
            cursor:zoom-in;
        }

    
.overlay{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;

    background:rgba(0,0,0,0.5);

    padding:12px 15px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    opacity:0;
    transition:0.4s;

    z-index:2;
}

.gallery-card:hover .overlay{
    opacity:1;
}

.overlay-title{
    font-size:18px;
    font-weight:600;
    color:#0d6efd;
}

.download-icon{
    width:35px;
    height:35px;

    border-radius:50%;

    background:#fff;
    color:#0d6efd;

    display:flex;
    justify-content:center;
    align-items:center;

    text-decoration:none;

    transition:0.3s;

    pointer-events:auto;
}

.download-icon:hover{
    background:#0d6efd;
    color:#fff;
}






    </style>

</head>

<body>

<section class="padding bg_light">

    <div class="container">

        <div class="main_title">
            <h2>IMAGES</h2>
        </div>

       

        <!-- GALLERY -->
        <div class="row">

            <?php foreach($gallery as $value) { ?>

              <div class="col-md-4 gallery-item <?php echo strtolower($value->c_type); ?>">
                    <div class="gallery-card">

                        <img src="<?php echo base_url('assets/images/gallery/'.$value->c_images); ?>"
                             class="gallery-img popup-image"
                             data-img="<?php echo base_url('assets/images/gallery/'.$value->c_images); ?>">
                       
                                <div class="overlay">

                                    <!-- <span class="overlay-title">
                                        <?php echo ucwords(str_replace('_', ' ', $value->c_type)); ?>
                                    </span> -->

                                    
                                    <a href="<?php echo base_url('assets/images/gallery/'.$value->c_images); ?>"
                                    download
                                    class="download-icon"
                                    onclick="event.stopPropagation();">

                                        <i class="fa fa-download"></i>

                                    </a>


                                </div>


                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>


<!-- POPUP -->

<div id="imgPopup" class="img-popup">

    <span class="close-btn">&times;</span>

    <div class="popup-img-container">
        <img id="popupImg">
    </div>

</div>



<script>

document.addEventListener("DOMContentLoaded", function(){

    let popup = document.getElementById("imgPopup");
    let popupImg = document.getElementById("popupImg");
    let closeBtn = document.querySelector(".close-btn");

    let scale = 1;

    // IMAGE CLICK
    document.querySelectorAll(".popup-image").forEach(function(img){

        img.addEventListener("click", function(){

            popup.style.display = "flex";

            popupImg.src = this.getAttribute("data-img");

            scale = 1;

            popupImg.style.transform = "scale(1)";

        });

    });

    // CLOSE BUTTON
    closeBtn.addEventListener("click", function(){

        popup.style.display = "none";

    });

    // CLOSE OUTSIDE
    popup.addEventListener("click", function(e){

        if(e.target === popup)
        {
            popup.style.display = "none";
        }

    });

    // MOUSE WHEEL ZOOM
    popupImg.addEventListener("wheel", function(e){

        e.preventDefault();

        if(e.deltaY < 0)
        {
            scale += 0.1;
        }
        else
        {
            scale -= 0.1;

            if(scale < 1)
            {
                scale = 1;
            }
        }

        popupImg.style.transform = "scale(" + scale + ")";

    });

});

</script>




<script>

function filterGallery(category, button)
{
    // ALL GALLERY ITEMS
    let items = document.querySelectorAll(".gallery-item");

    // REMOVE ACTIVE CLASS FROM ALL BUTTONS
    document.querySelectorAll(".filter-buttons button").forEach(function(btn){

        btn.classList.remove("active");

    });

    // ADD ACTIVE CLASS TO CLICKED BUTTON
    button.classList.add("active");

    // SHOW/HIDE ITEMS
    items.forEach(function(item){

        if(category === "all")
        {
            item.style.display = "block";
        }
        else
        {
            if(item.classList.contains(category))
            {
                item.style.display = "block";
            }
            else
            {
                item.style.display = "none";
            }
        }

    });
}

</script>



</body>
</html>
