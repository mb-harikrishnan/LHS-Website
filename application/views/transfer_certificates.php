<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Transfer Certificates</title>

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
<!-- Transfer Certificates -->
<section id="transfer_certificates" class="padding bg_light">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_bottom">
          <h2>Transfer Certificates</h2>
          <p>
            View and download student transfer certificates quickly and securely.
          </p>
        </div>
      </div>
    </div>

    <!-- TC Table -->
    <div class="row">
      <div class="col-md-12">

        <div class="tc_table_box wow fadeInUp">

          <div class="table-responsive">
            <table class="table tc_table">

              <thead>
                <tr>
                  <th><i class="fa fa-user"></i> Student Name</th>
                  <th><i class="fa fa-id-card"></i> TC Number</th>
                  <th><i class="fa fa-graduation-cap"></i> Admission No</th>
                  <th><i class="fa fa-download"></i> Download</th>
                </tr>
              </thead>

              <tbody>

                <?php foreach($transfer_details as $value){?>

                <tr>
                  <td><?php echo $value->c_name ; ?></td>
                  <td><?php echo $value->c_tc_number ; ?></td>
                  <td><?php echo $value->c_adminssion_number ; ?></td>
                  <td>
                      <a href="<?php echo base_url('assets/uploads/certificate/' . $value->c_certificate); ?>" 
                        class="download_btn" 
                        target="_blank">
                          <i class="fa fa-eye"></i> View
                      </a>
                  </td>
                </tr>

                <?php }?>

               

              </tbody>

            </table>
          </div>

        </div>

      </div>
    </div>

  </div>
</section>

<style>
  #transfer_certificates {
    position: relative;
  }


  /* Table Box */
  .tc_table_box {
    background: #fff;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  /* Table */
  .tc_table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0 12px;
  }

  /* Table Header */
  .tc_table thead tr th {
    background: linear-gradient(135deg, #0654c2, #0A84FF);
    color: #fff;
    border: none !important;
    padding: 18px 20px;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .tc_table thead tr th:first-child {
    border-radius: 12px 0 0 12px;
  }

  .tc_table thead tr th:last-child {
    border-radius: 0 12px 12px 0;
  }

  /* Table Body */
  .tc_table tbody tr {
    background: #f9fdfd;
    transition: 0.4s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
  }

  .tc_table tbody tr:hover {
    transform: translateY(-3px);
    background: #ffffff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  }

  .tc_table tbody td {
    padding: 20px;
    vertical-align: middle !important;
    border-top: none !important;
    color: #555;
    font-size: 15px;
  }

  .tc_table tbody tr td:first-child {
    border-radius: 12px 0 0 12px;
    font-weight: 600;
    color: #333;
  }

  .tc_table tbody tr td:last-child {
    border-radius: 0 12px 12px 0;
  }

  /* Download Button */
  .download_btn {
    display: inline-block;
    padding: 10px 22px;
    border-radius: 30px;
    background: linear-gradient(135deg, #ff9800, #ff6f00);
    color: #fff !important;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    transition: 0.4s;
  }

  .download_btn i {
    margin-right: 6px;
  }

  .download_btn:hover {
    background: linear-gradient(135deg, #0A84FF, #0654c2);
    transform: scale(1.05);
  }

  /* Mobile Responsive */
  @media(max-width:767px) {

    .tc_table thead {
      display: none;
    }

    .tc_table,
    .tc_table tbody,
    .tc_table tr,
    .tc_table td {
      display: block;
      width: 100%;
    }

    .tc_table tbody tr {
      margin-bottom: 20px;
      border-radius: 15px;
      overflow: hidden;
    }

    .tc_table tbody td {
      text-align: right;
      padding-left: 50%;
      position: relative;
    }

    .tc_table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: 20px;
      width: 45%;
      font-weight: 700;
      text-align: left;
      color: #222;
    }

  }
</style>