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

<?php foreach($all_vacancy as $value){ ?>

<div class="vacancy_card">

    <!-- LEFT CONTENT -->
    <div class="vacancy_left">

        <span class="job_badge">
            Career Opportunity
        </span>

        <h2 class="vacancy_title">
            <?php echo $value->c_title; ?>
        </h2>

        <p class="vacancy_description">
            <?php echo nl2br($value->c_description); ?>
        </p>

        <button class="apply_button"
                onclick="showApplyForm('<?php echo $value->n_slno; ?>')">

            Apply Now

        </button>

    </div>

    <!-- RIGHT FORM -->
    <div class="vacancy_right">

        <div class="apply_form"
             id="applyForm<?php echo $value->n_slno; ?>">

            <h3>Job Application Form</h3>

            <form method="POST"
                  action="<?php echo base_url('submit_job_application');?>"
                  enctype="multipart/form-data">

                <input type="hidden"
                       name="job_id"
                       value="<?php echo $value->n_slno; ?>">

                <div class="form-group">
                    <input type="text"
                           name="full_name"
                           class="form-control"
                           placeholder="Enter Full Name"
                           required>
                </div>

                <div class="form-group">
                    <input type="text"
                           name="mobile"
                           class="form-control"
                           placeholder="Enter Contact Number"
                           required>
                </div>

                <div class="form-group">
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter Email Address"
                           required>
                </div>

                <div class="form-group">
                    <input type="file"
                           name="resume"
                           class="form-control"
                           required>
                </div>

                <button type="submit" class="submit_application_btn">
                    Submit Application
                </button>

            </form>

        </div>

    </div>

</div>

<?php } ?>

</div>
        </div>

        <?php }?>

      </div>
    </div>

  </div>
</section>




<style>
.vacancy_card{
    background:#fff;
    border-radius:20px;
    padding:50px;
    margin-bottom:40px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);

    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:50px;
    flex-wrap:wrap;
}
/* LEFT SIDE */
.vacancy_left{
    flex:1;
    min-width:300px;
}

/* RIGHT SIDE */
.vacancy_right{
    width:420px;
}


.job_badge{
    background:#edf4ff;
    color:#0654c2;
    padding:10px 22px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    display:inline-block;
    margin-bottom:25px;
}

.vacancy_title{
    font-size:48px;
    font-weight:800;
    color:#0654c2;
    margin-bottom:25px;
    line-height:60px;

    /* FIX LONG WORD */
    word-break:break-word;
}

.vacancy_description{
    color:#555;
    font-size:17px;
    line-height:34px;
    margin-bottom:35px;

    /* IMPORTANT */
    white-space:normal;
    overflow-wrap:break-word;
    word-wrap:break-word;
    word-break:break-word;

    max-width:100%;
}
/* BUTTON */
.apply_button{
    background:#ff0000;
    color:#fff;
    border:none;
    padding:14px 40px;
    border-radius:8px;
    font-size:17px;
    font-weight:600;
    transition:0.3s;
}

.apply_button:hover{
    background:#d80000;
}

/* FORM */
.apply_form{
    display:none;

    background:#f9fbff;
    border:1px solid #dce7ff;
    border-radius:18px;
    padding:35px;
}

.apply_form h3{
    color:#0654c2;
    font-size:38px;
    font-weight:700;
    text-align:center;
    margin-bottom:30px;
}

.form-control{
    height:55px;
    border-radius:10px;
    border:1px solid #d9d9d9;
    box-shadow:none;
    font-size:16px;
    padding-left:18px;
    margin-bottom:20px;
}

input[type="file"]{
    padding-top:12px;
}

.submit_application_btn{
    width:100%;
    height:55px;
    border:none;
    border-radius:10px;
    background:#0654c2;
    color:#fff;
    font-size:18px;
    font-weight:600;
    transition:0.3s;
}

.submit_application_btn:hover{
    background:#043b89;
}

/* MOBILE */
@media(max-width:991px){

    .vacancy_card{
        padding:30px;
        gap:30px;
    }

    .vacancy_right{
        width:100%;
    }

    .vacancy_title{
        font-size:34px;
        line-height:46px;
    }

    .apply_form h3{
        font-size:30px;
    }
}


</style>

<script src="<?php echo base_url('assets/js/jquery-2.2.3.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>

<script>

function showApplyForm(id)
{
    $('.apply_form').slideUp();

    $('#applyForm'+id).slideToggle();
}

</script>