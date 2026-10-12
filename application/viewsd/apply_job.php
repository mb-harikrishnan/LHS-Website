<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Little Hearts | Vacancy</title>

    
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
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">

    <style>

        body{
            background:#f4f7fc;
            font-family:Arial,sans-serif;
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
    line-height:30px;
    color:#f1f1f1;
    text-align:justify;
    word-wrap:break-word;
    overflow-wrap:break-word;
    white-space:normal;
}
.job_description p{
    margin-bottom:15px;
}

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
        }

        .submit_btn:hover{
            background:#043b89;
        }

        @media(max-width:991px){

            .job_details,
            .application_form{
                padding:35px;
            }

            .job_title{
                font-size:30px;
                line-height:42px;
            }
        }

        .swal2-container{
    z-index:99999;
}

.swal2-popup.swal2-toast{
    margin-bottom:80px !important;
}


.back_btn{
    display:inline-block;
    margin-bottom:25px;
    background:#f1f4ff;
    color:#0654c2;
    padding:10px 20px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
}

.back_btn:hover{
    background:#0654c2;
    color:#fff;
    text-decoration:none;
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
                          <a href="<?php echo base_url('vaccancy') ?>" class="back_btn">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>

                        <h2 class="form_title">
                            Apply Now
                        </h2>

                        <form id="jobForm" enctype="multipart/form-data">

                            <input type="hidden"
                                   name="vacancy_id"
                                   value="<?php echo $job->n_slno; ?>">

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Full Name"
                                   required>

                            <input type="text"
                                   name="mobile"
                                   class="form-control"
                                   placeholder="Contact Number"
                                   required>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Email Address"
                                   required>

                            <input type="file"
                                   name="resume"
                                   class="form-control"
                                   required>

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


<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function () {

    $('#jobForm').on('submit', function(e){

        e.preventDefault();

        console.log("FORM SUBMITTED");

        let formData = new FormData(this);

        $.ajax({

            url: "<?php echo base_url('submit_job_application'); ?>",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            dataType: "json",

            beforeSend:function(){

                $('.submit_btn').html('Submitting...');
                $('.submit_btn').prop('disabled', true);

            },

           success:function(response){

                console.log(response);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                if(response.status == 'success'){

                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    $('#jobForm')[0].reset();

                }else{

                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });

                }

                $('.submit_btn').html('Submit Application');
                $('.submit_btn').prop('disabled', false);

            },

            error:function(xhr){

                console.log(xhr.responseText);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Something went wrong'
                });

                $('.submit_btn').html('Submit Application');
                $('.submit_btn').prop('disabled', false);

            }
        });

    });

});

</script>

</body>
</html>