<!doctype html>
<html lang="en">



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
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
  <title>Apply Job</title>




  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>




<style>

    body{
        background:#f4f7fc;
        font-family:Arial, sans-serif;
    }

    .apply_section{
        padding:80px 0;
    }

    .apply_wrapper{
        background:#fff;
        border-radius:25px;
        overflow:hidden;
        box-shadow:0 10px 35px rgba(0,0,0,0.08);
    }

    /* LEFT SIDE */
    .job_details{
        background:linear-gradient(135deg,#0654c2,#0b72ff);
        color:#fff;
        padding:60px;
        height:100%;
    }

    .job_tag{
        display:inline-block;
        background:rgba(255,255,255,0.2);
        padding:10px 22px;
        border-radius:30px;
        font-size:14px;
        margin-bottom:25px;
    }

    .job_title{
        font-size:42px;
        font-weight:800;
        line-height:56px;
        margin-bottom:30px;
    }

    .job_description{
        font-size:17px;
        line-height:34px;
        color:#f1f1f1;
    }

    /* RIGHT SIDE */
    .application_form{
        padding:60px;
    }

    .form_title{
        font-size:34px;
        font-weight:700;
        color:#111;
        margin-bottom:35px;
    }

    .form-control{
        height:58px;
        border-radius:12px;
        border:1px solid #ddd;
        box-shadow:none;
        font-size:16px;
        margin-bottom:22px;
        padding-left:18px;
    }

    textarea.form-control{
        height:130px;
        padding-top:15px;
        resize:none;
    }

    input[type=file]{
        padding-top:14px;
    }

    .submit_btn{
        width:100%;
        height:58px;
        border:none;
        border-radius:12px;
        background:#0654c2;
        color:#fff;
        font-size:18px;
        font-weight:600;
        transition:0.3s;
    }

    .submit_btn:hover{
        background:#043b89;
    }

    /* MOBILE */
    @media(max-width:991px){

        .job_details,
        .application_form{
            padding:35px;
        }

        .job_title{
            font-size:30px;
            line-height:42px;
        }

        .form_title{
            font-size:28px;
        }

    }

</style>


</head>

<body>

<section class="apply_section">


<div class="container">

    <div class="apply_wrapper">

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-md-6">

                <div class="job_details">

                    <span class="job_tag">
                        Career Opportunity
                    </span>

                    <h1 class="job_title">
                        <?php echo $job->c_title; ?>
                    </h1>

                    <div class="job_description">
                        <?php echo nl2br($job->c_description); ?>
                    </div>

                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-6">

                <div class="application_form">

                    <h2 class="form_title">
                        Apply Now
                    </h2>

                    <form id="jobForm"
                        method="post"
                        enctype="multipart/form-data">

                        <input type="hidden" name="vacancy_id" value="<?php echo $job->n_slno; ?>">

                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>

                        <input type="text" name="mobile" class="form-control" placeholder="Contact Number" required>

                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>

                        <input type="file" name="resume" class="form-control" required>

                        <button type="submit" class="submit_btn">
                            Submit Application
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


</section>


<script src="<?php echo JS_PATH ?>jquery-3.6.0.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.validate.min.js"></script>
    <script src="<?php echo JS_PATH ?>jquery.dataTables.min.js"></script>
    <script src="<?php echo JS_PATH ?>dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    $('#jobForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "<?php echo base_url('submit_job_application'); ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (res) {

                if (res.status === 'success') {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });

                    $('#jobForm')[0].reset();

                } else {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Toast.fire({
                        icon: 'error',
                        title: res.message
                    });
                }
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Something went wrong!'
                });
            }
        });
    });

});
</script>
</body>
</html>
