<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Vaccancy</title>

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

<!--Page Header-->
<section id="vacancy" class="padding bg_light">
  <div class="container">

    <div class="row">
      <div class="col-md-12 text-center">

      <?php if(empty($all_vacancy)){?>

        <div class="vacancy_box">

          <i class="fa fa-briefcase vacancy_icon"></i>

          <h2>Vacancy</h2>

          <p class="tagline">
            Join our passionate educational community and grow with us.
          </p>

          <div class="vacancy_content">
            <h4>No Current Openings</h4>
            <p>
              There are currently no vacancies available at this time.
              Please check back later for future career opportunities.
            </p>
          </div>

        </div>
        <?php }else{?>


        <div class="row">
<div class="vacancy_wrapper">

<?php foreach($all_vacancy as $value){ ?>

<div class="vacancy_card">

    <div class="vacancy_content">

        <span class="vacancy_tag">
            We're Hiring
        </span>

        <h4 class="vacancy_title">
            <?php echo $value->c_title; ?>
        </h4>

        <p class="vacancy_description">
            <?php echo nl2br($value->c_description); ?>
        </p>

        <div class="vacancy_footer">

            <a href="<?php echo base_url('apply_job/'.$value->n_slno); ?>" class="apply_btn">
                Apply Now
            </a>

        </div>

    </div>

</div>

<?php } ?>

</div>
</div>
        </div>

        <?php }?>

      </div>
    </div>

  </div>
</section>




<style>
/* MAIN SECTION */
#vacancy{
    background:#f4f7fc;
}

/* WRAPPER */
.vacancy_wrapper{
    width:100%;
}

/* CARD */
.vacancy_card{
    background:#fff;
    border-radius:22px;
    padding:45px;
    margin-bottom:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:0.3s ease;
    border-left:6px solid #0654c2;
}

.vacancy_card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 40px rgba(0,0,0,0.12);
}

/* TAG */
.vacancy_tag{
    display:inline-block;
    background:#eaf2ff;
    color:#0654c2;
    padding:8px 20px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    margin-bottom:22px;
}

/* TITLE */
.vacancy_title{
    font-size:38px;
    font-weight:800;
    color:#111;
    margin-bottom:20px;
    line-height:52px;
    word-break:break-word;
}

/* DESCRIPTION */
.vacancy_description{
    font-size:17px;
    line-height:32px;
    color:#555;
    margin-bottom:35px;

    white-space:normal;
    word-wrap:break-word;
    overflow-wrap:break-word;
}

/* FOOTER */
.vacancy_footer{
    display:flex;
    align-items:center;
    justify-content:flex-start;
}

/* APPLY BUTTON */
.apply_btn{
    background:#0654c2;
    color:#fff !important;
    padding:15px 35px;
    border-radius:10px;
    text-decoration:none;
    font-size:16px;
    font-weight:600;
    transition:0.3s ease;
    display:inline-block;
}

.apply_btn:hover{
    background:#043b89;
    transform:scale(1.03);
}

/* MOBILE */
@media(max-width:768px){

    .vacancy_card{
        padding:28px;
    }

    .vacancy_title{
        font-size:28px;
        line-height:40px;
    }

    .vacancy_description{
        font-size:15px;
        line-height:28px;
    }

    .apply_btn{
        width:100%;
        text-align:center;
    }
}


</style>

    <!-- Scripts -->
    <script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.dataTables.min.js"></script>
    <script src="<?php echo JS_PATH ?>dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


