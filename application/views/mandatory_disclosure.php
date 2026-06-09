<!doctype html>
<html lang="en">

</html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>Little Hearts | Mandatory Disclosure</title>

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
<!-- Mandatory Disclosure -->
<!-- Mandatory Disclosure -->
<section id="mandatory_disclosure" class="padding bg_light disclosure_section">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title margin_bottom">
          <span class="subtitle">CBSE Mandatory Disclosure</span>
          <h2>Our Mandatory Disclosure</h2>
          <p>
            Transparency, trust, and excellence in education through complete institutional disclosure.
          </p>
        </div>
      </div>
    </div>

    <!-- A -->
    <div class="disclosure_card">
      <div class="card_title">
        <i class="fa fa-info-circle"></i>
        A: GENERAL INFORMATION
      </div>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Information</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Name of the School</td>
              <td>Little Hearts School</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Affiliation No.</td>
              <td>930601</td>
            </tr>
            <tr>
              <td>3</td>
              <td>School Code</td>
              <td>75576</td>
            </tr>
            <tr>
              <td>4</td>
              <td>Complete Address</td>
              <td>Little Hearts School, Kizhakkepram, North Paravur, Ernakulam, Kerala, Pin-683518</td>
            </tr>
            <tr>
              <td>5</td>
              <td>Principal Name</td>
              <td>Mrs. Pushpalatha P</td>
            </tr>
            <tr>
              <td>6</td>
              <td>Principal Qualification</td>
              <td>MA English, M.Ed, M.Phil</td>
            </tr>
            <tr>
              <td>7</td>
              <td>School Email ID</td>
              <td>littleheartsschool97@gmail.com</td>
            </tr>
            <tr>
              <td>8</td>
              <td>Contact Details</td>
              <td>0484-2446939</td>
            </tr>
     
                  <?php

                  foreach($general_information as $value){

                  
                if($value->c_type == 'general_information' && !empty($value->c_document))
                {
                    $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                ?>
                <tr>
                    <td>9</td>
                    <td>SARAS 6.0 </td>
                    <td>
                        <a href="<?php echo $pdf_path; ?>" 
                          target="_blank" 
                          class="view_btn">
                          View
                        </a>
                    </td>
                </tr>

                <?php
                }
                else
                {
                ?>
                <tr>
                    <td>9</td>
                    <td>SARAS 6.0 </td>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                          View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                </tr>



                <?php
                }  }
                ?>
            
              
          </tbody>
        </table>
      </div>
    </div>

    <!-- B -->
    <div class="disclosure_card">
      <div class="card_title">
        <i class="fa fa-file-text"></i>
        B: DOCUMENTS AND INFORMATION
      </div>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Information</th>
              <th>Documents</th>
            </tr>
          </thead>

          <tbody>

          <tr>
            <td>1</td>

            <td>
                Copies of Affiliation/Upgradation Letter and Recent Extension of Affiliation
            </td>

            <?php
            if(!empty($copy_of_affiliation))
            {
                $found = false;

                foreach($copy_of_affiliation as $value)
                {
                    if($value->c_type == 'copy_of_affiliation' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
        </tr>

            <tr>
              <td>2</td>
              <td>Copies of Societies/Trust/Company Registration/Renewal Certificate</td>
               <?php
            if(!empty($copy_of_societies))
            {
                $found = false;

                foreach($copy_of_societies as $value)
                {
                    if($value->c_type == 'copy_of_societies' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>3</td>
              <td>Copy of No Objection Certificate (NOC)</td>
                  <?php
            if(!empty($NOC))
            {
                $found = false;

                foreach($NOC as $value)
                {
                    if($value->c_type == 'NOC' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>4</td>
              <td>Copies of Recognition Certificate under RTE Act, 2009</td>
                  <?php
            if(!empty($copy_of_recognition))
            {
                $found = false;

                foreach($copy_of_recognition as $value)
                {
                    if($value->c_type == 'copy_of_recognition' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>5</td>
              <td>Copy of Valid Building Safety Certificate</td>
                  <?php
            if(!empty($copy_of_safty))
            {
                $found = false;

                foreach($copy_of_safty as $value)
                {
                    if($value->c_type == 'copy_of_safty' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>6</td>
              <td>Copy of Valid Fire Safety Certificate</td>
                  <?php
            if(!empty($copy_of_fire_and_safety))
            {
                $found = false;

                foreach($copy_of_fire_and_safety as $value)
                {
                    if($value->c_type == 'copy_of_fire_and_safety' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>7</td>
              <td>Copy of the DEO Certificate</td>
                  <?php
            if(!empty($DEO))
            {
                $found = false;

                foreach($DEO as $value)
                {
                    if($value->c_type == 'DEO' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>8</td>
              <td>Copies of Valid Water, Health and Sanitation Certificates</td>
                  <?php
            if(!empty($sanitation))
            {
                $found = false;

                foreach($sanitation as $value)
                {
                    if($value->c_type == 'sanitation' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>

            <tr>
              <td>9</td>
              <td>Certificate of Land</td>
                  <?php
            if(!empty($land))
            {
                $found = false;

                foreach($land as $value)
                {
                    if($value->c_type == 'land' && !empty($value->c_document))
                    {
                        $found = true;

                        $pdf_path = base_url('../assets/uploads/documents/' . $value->c_document);
                        ?>
                        
                        <td>
                            <a href="<?php echo $pdf_path; ?>" 
                              target="_blank" 
                              class="view_btn">
                                View
                            </a>
                        </td>

                        <?php
                    }
                }

                // if no matching document found
                if($found == false)
                {
                    ?>
                    <td>
                        <a href="javascript:void(0);" 
                          onclick="showEmptyMessage(this)"
                          class="view_btn">
                            View
                        </a>

                        <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                            Empty
                        </div>
                    </td>
                    <?php
                }
            }
            else
            {
                ?>
                <td>
                    <a href="javascript:void(0);" 
                      onclick="showEmptyMessage(this)"
                      class="view_btn">
                        View
                    </a>

                    <div class="empty_msg" style="color:red; display:none; margin-top:5px;">
                        Empty
                    </div>
                </td>
                <?php
            }
            ?>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="note_box">
        <strong>Note:</strong> Note: The schools need to upload the self-attested copies of the above-listed documents by Chairman/Manager/Secretary and Principal. In case it is noticed at a later stage that uploaded documents are not genuine, then the school shall be liable for action as per norms.
      </div>
    </div>

    <!-- C -->
    <div class="disclosure_card">
      <div class="card_title">
        <i class="fa fa-graduation-cap"></i>
        C: RESULT AND ACADEMICS
      </div>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Information</th>
              <th>Documents</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>1</td>
              <td>Fee Structure of the School</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>

            <tr>
              <td>2</td>
              <td>Annual Academic Calendar</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>

            <tr>
              <td>3</td>
              <td>List of School Management Committee (SMC)</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>

            <tr>
              <td>4</td>
              <td>List of Parents Teachers Association (PTA) Members</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>

            <tr>
              <td>5</td>
              <td>Last Three-Year Result of Board Examination</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- RESULT X -->
      <h4 class="result_heading">RESULT CLASS : X</h4>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Year</th>
              <th>Registered Students</th>
              <th>Students Passed</th>
              <th>Pass Percentage</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>1</td>
              <td>2022-23</td>
              <td>98</td>
              <td>98</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>2</td>
              <td>2023-24</td>
              <td>119</td>
              <td>119</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>3</td>
              <td>2024-25</td>
              <td>104</td>
              <td>104</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>4</td>
              <td>2025-26</td>
              <td>94</td>
              <td>94</td>
              <td><span class="badge_success">100%</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- RESULT XII -->
      <h4 class="result_heading">RESULT CLASS : XII</h4>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Year</th>
              <th>Registered Students</th>
              <th>Students Passed</th>
              <th>Pass Percentage</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>1</td>
              <td>2022-23</td>
              <td>62</td>
              <td>62</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>2</td>
              <td>2023-24</td>
              <td>75</td>
              <td>75</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>3</td>
              <td>2024-25</td>
              <td>66</td>
              <td>66</td>
              <td><span class="badge_success">100%</span></td>
            </tr>

            <tr>
              <td>4</td>
              <td>2025-26</td>
              <td>79</td>
              <td>79</td>
              <td><span class="badge_success">100%</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- D -->
    <div class="disclosure_card">
      <div class="card_title">
        <i class="fa fa-users"></i>
        D: STAFF (TEACHING)
      </div>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <tbody>
            <tr>
              <td>Principal</td>
              <td>Mrs. Pushpalatha P</td>
            </tr>

            <tr>
              <td>Staff Details</td>
              <td><a href="#" class="view_btn">View</a></td>
            </tr>

            <tr>
              <td>Total No. of Teachers</td>
              <td>59</td>
            </tr>

            <tr>
              <td>PGT</td>
              <td>12</td>
            </tr>

            <tr>
              <td>TGT</td>
              <td>26</td>
            </tr>

            <tr>
              <td>PRT</td>
              <td>21</td>
            </tr>

            <tr>
              <td>Teachers Section Ratio</td>
              <td>1.5</td>
            </tr>

            <tr>
              <td>Special Educator and Counsellor</td>
              <td>Biji Jose, M.Sc Applied Psychology</td>
            </tr>

            <tr>
              <td>Wellness Teacher</td>
              <td>Reshna E, M.P.Ed</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- E -->
    <div class="disclosure_card">
      <div class="card_title">
        <i class="fa fa-building"></i>
        E: SCHOOL INFRASTRUCTURE
      </div>

      <div class="table-responsive">
        <table class="table disclosure_table">
          <tbody>
            <tr>
              <td>Total Campus Area</td>
              <td>8539 Sq Mtr</td>
            </tr>

            <tr>
              <td>No. & Size of Class Rooms</td>
              <td>56, 2138 SQ.M</td>
            </tr>

            <tr>
              <td>No. & Size of Laboratories</td>
              <td>7, 929 SQ.M</td>
            </tr>

            <tr>
              <td>Internet Facility</td>
              <td>Yes</td>
            </tr>

            <tr>
              <td>No. of Girls Toilets</td>
              <td>50</td>
            </tr>

            <tr>
              <td>No. of Boys Toilets</td>
              <td>50</td>
            </tr>

            <tr>
              <td>Infrastructure Inspection Video</td>
              <td><a href="#" class="view_btn">Play Video</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<style>
  .disclosure_section {
    background: #f5f9ff;
  }

  .disclosure_card {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 35px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    transition: 0.4s;
  }

  .disclosure_card:hover {
    transform: translateY(-5px);
  }

  .card_title {
    font-size: 24px;
    font-weight: 700;
    color: #0a2c5e;
    margin-bottom: 25px;
    border-left: 5px solid #0654c2;
    padding-left: 15px;
  }

  .card_title i {
    color: #0654c2;
    margin-right: 10px;
  }

  .disclosure_table {
    margin-bottom: 0;
  }

  .disclosure_table thead {
    background: #0654c2;
    color: #fff;
  }

  .disclosure_table thead th {
    padding: 16px;
    border: none;
    font-size: 15px;
  }

  .disclosure_table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-color: #edf2f7;
    color: #444;
    font-size: 15px;
  }

  .disclosure_table tbody tr:hover {
    background: #f7fbff;
  }

  .view_btn {
    background: linear-gradient(45deg, #0654c2, #0A84FF);
    color: #fff;
    padding: 8px 18px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s;
    display: inline-block;
  }

  .view_btn:hover {
    color: #fff;
    transform: scale(1.05);
  }

  .note_box {
    margin-top: 20px;
    background: #fff8e6;
    border-left: 5px solid #ffc107;
    padding: 18px;
    border-radius: 10px;
    color: #555;
    font-size: 15px;
  }

  .result_heading {
    margin-top: 35px;
    margin-bottom: 20px;
    color: #0a2c5e;
    font-weight: 700;
  }

  .badge_success {
    background: #0654c2;
    color: #fff;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
  }

  @media(max-width:768px) {

    .main_title h2 {
      font-size: 30px;
    }

    .disclosure_card {
      padding: 20px;
    }

    .card_title {
      font-size: 20px;
    }

    .disclosure_table thead th,
    .disclosure_table tbody td {
      font-size: 13px;
      padding: 12px;
    }

  }
</style>

<script>
function showEmptyMessage(element)
{
    let msg = element.nextElementSibling;

    msg.style.display = 'block';

    setTimeout(function() {
        msg.style.display = 'none';
    }, 2000); // 2000 milliseconds = 2 seconds
}
</script>

