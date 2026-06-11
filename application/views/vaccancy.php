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

            <div class="col-md-6 col-sm-12">
              
              <div class="job_card">

                <div class="job_top">
                  <span class="job_date">
                    <i class="fa fa-calendar"></i>
                    Posted On :
                    <?php echo date('d M Y', strtotime($value->d_date)); ?>
                  </span>
                </div>

                <h3 class="job_title">
                  <?php echo $value->c_title; ?>
                </h3>

                <p class="job_description">
                  <?php echo nl2br($value->c_description) ; ?>
                </p>

              <div class="job_btn_area">
  
                <button type="button"
                        class="apply_btn"
                        data-toggle="modal"
                        data-target="#applyJobModal"
                        onclick="setJobId('<?php echo $value->n_slno; ?>')">

                    Apply Now

                </button>

              </div>

              </div>

            </div>

          <?php } ?>

        </div>

        <?php }?>

      </div>
    </div>

  </div>
</section>




<!-- APPLY JOB MODAL -->
<div class="modal fade" id="applyJobModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Job Application Form</h3>

        <button type="button" class="close" data-dismiss="modal">
          &times;
        </button>
      </div>

      <div class="modal-body">

        <form method="post"
              action="<?php echo base_url('submit_job_application'); ?>"
              enctype="multipart/form-data">

          <input type="hidden" name="job_id" id="job_id">

          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                  <option value="">Select</option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Other</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Marital Status</label>
                <select name="marital_status" class="form-control">
                  <option value="">Select</option>
                  <option>Single</option>
                  <option>Married</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="mobile" class="form-control" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>State</label>
                <input type="text" name="state" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Expected Salary</label>
                <input type="text" name="expected_salary" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Joining Availability</label>
                <input type="text" name="joining_availability" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Total Experience</label>
                <input type="text" name="total_experience" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Previous School Name</label>
                <input type="text" name="previous_school" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Resume / CV</label>
                <input type="file" name="resume" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Educational Certificates</label>
                <input type="file" name="education_certificate" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Experience Certificate</label>
                <input type="file" name="experience_certificate" class="form-control">
              </div>
            </div>

          </div>

          <div class="text-center">
            <button type="submit" class="apply_btn">
              Submit Application
            </button>
          </div>

        </form>

      </div>

    </div>

  </div>
</div>


<style>
  .vacancy_box {
    background: #fff;
    padding: 50px 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    max-width: 700px;
    margin: auto;
  }

  .vacancy_icon {
    font-size: 60px;
    color: #0654c2;
    margin-bottom: 20px;
  }

  .vacancy_box h2 {
    font-size: 40px;
    font-weight: 700;
    color: #222;
    margin-bottom: 10px;
  }

  .tagline {
    color: #666;
    font-size: 18px;
    margin-bottom: 30px;
  }

  .vacancy_content {
    background: #f8f8f8;
    padding: 25px;
    border-radius: 10px;
  }

  .vacancy_content h4 {
    color: #333;
    font-weight: 600;
    margin-bottom: 15px;
  }

  .vacancy_content p {
    color: #777;
    font-size: 16px;
    line-height: 28px;
  }



  /* else case design */

.job_card {
  background: #fff;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  transition: 0.3s;
  text-align: center;

  /* REMOVE FIXED HEIGHT ISSUE */
  height: auto;

  /* LONG TEXT BREAK */
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-word;
}

.job_card:hover {
  transform: translateY(-5px);
}

.job_top {
  margin-bottom: 15px;
}

.job_date {
  display: inline-block;
  background: #f2f6ff;
  color: #0654c2;
  padding: 8px 15px;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 600;
}

/* .job_title {
  font-size: 32px;
  font-weight: 700;
  color: #222;
  margin-bottom: 15px; */

  /* BREAK LONG WORDS */
  /* overflow-wrap: break-word;
  word-break: break-word;
} */


  .job_title {
  font-size: 32px;
  font-weight: 700;
  color: #0654c2; /* TITLE COLOR */
  margin-bottom: 15px;

  overflow-wrap: break-word;
  word-break: break-word;
}

.job_description {
  color: #666;
  font-size: 16px;
  line-height: 30px;
  margin-bottom: 25px;

  /* IMPORTANT */
  white-space: normal;
  overflow-wrap: break-word;
  word-break: break-word;
}

.job_btn_area {
  margin-top: 20px;
}

.apply_btn {
  background: #0654c2;
  color: #fff !important;
  padding: 12px 30px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  transition: 0.3s;
}

.apply_btn:hover {
  background: #043d8c;
}


/* modal design */


.modal-header {
  background: #0654c2;
  color: #fff;
}

.modal-title {
  color: #fff;
  font-weight: 700;
}

.close {
  color: #fff !important;
  opacity: 1;
}

.form-group {
  margin-bottom: 20px;
}

.form-control {
  height: 45px;
  border-radius: 8px;
  box-shadow: none;
}

textarea.form-control {
  height: 100px;
}

</style>

<script src="<?php echo base_url('assets/js/jquery-2.2.3.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script>
  function setJobId(id)
  {
      document.getElementById('job_id').value = id;
  }
</script>