<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">

      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Add Division
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('students_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

<form id="studentform" method="post">

<!-- ================= BASIC INFORMATION ================= -->
<div class="section-title">
    <i class="fa fa-info-circle"></i> Basic Information
</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Admission Number <span class="text-danger">*</span></label>
        <input type="text" name="admission_no" id="admission_no" class="news-select" placeholder="Enter Admission Number">
    </div>

    <div class="news-form-group">
        <label>Aadhar Number <span class="text-danger">*</span></label>
        <input type="text" name="aadhar_no" id="aadhar_no" maxlength="12" class="news-select" placeholder="Enter Aadhar Number">
    </div>

</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Student Name <span class="text-danger">*</span></label>
        <input type="text" name="student_name" id="student_name" class="news-select" placeholder="Enter Student Name">
    </div>

    <div class="news-form-group">
        <label>Gender</label>
        <select name="gender" id="gender" class="news-select">
            <option value="">Select Gender</option>
            <option value="1">Male</option>
            <option value="0">Female</option>
            <option value="2" >Other</option>
        </select>
    </div>

</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Date of Birth</label>
        <input type="date" name="dob" id="dob" class="news-select">
    </div>

    <div class="news-form-group">
        <label>Mobile Number</label>
        <input type="text" maxlength="10" name="mobile" id="mobile" class="news-select" placeholder="Enter Mobile Number">
    </div>

</div>

<!-- ================= ACADEMIC ================= -->

<div class="section-title">
    <i class="fa fa-graduation-cap"></i> Academic Information
</div>

<div class="form-row">

<div class="news-form-group">
    <label>Class</label>

    <select name="class" id="class" class="news-select select2">
        <option value="">Select Class</option>

        <?php foreach($class as $classvalue){ ?>
            <option value="<?php echo $classvalue->cmId; ?>">
                <?php echo $classvalue->cmName; ?>
            </option>
        <?php } ?>

    </select>
</div>

    <div class="news-form-group">
        <label>Division</label>
        <select name="division" class="news-select select2">
            <option value="">Select Division</option>
           <?php foreach($divition as $divitionvalue){ ?>
            <option value="<?php echo $divitionvalue->dmId; ?>">
                <?php echo $divitionvalue->dmName; ?>
            </option>
        <?php } ?>
        </select>
    </div>

</div>

<!-- ================= PERSONAL DETAILS ================= -->

<div class="section-title">
    <i class="fa fa-user"></i> Personal Details
</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Religion</label>
        <input type="text" name="religion" id="religion" class="news-select ">

        
    </div>

    <div class="news-form-group">
        <label>Caste</label>
        <input type="text" name="caste" id="caste" class="news-select ">
    </div>

</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Mother Tongue</label>
        <input type="text" name="mother_tongue" id="mother_tongue" class="news-select">
    </div>

</div>

<!-- ================= ADDRESS ================= -->

<div class="section-title">
    <i class="fa fa-map-marker"></i> Address Details
</div>

<div class="news-form-group">
    <label>Address</label>
    <textarea name="address" id="address" rows="4" class="news-select" placeholder="Enter Address"></textarea>
</div>

<div class="form-row">

    <div class="news-form-group">
        <label>Country</label>
        <select name="country" id="country" class="news-select select2">
            <option value="">Select Country</option>
                       <?php foreach($country as $countryvalue){ ?>
            <option value="<?php echo $countryvalue->country_id; ?>">
                <?php echo $countryvalue->name; ?>
            </option>
        <?php } ?>
        </select>
    </div>

    <div class="news-form-group">
        <label>State</label>
        <select name="state" id="state" class="news-select select2">
            <option value="">Select State</option>
                       <?php foreach($state as $statenvalue){ ?>
            <option value="<?php echo $statenvalue->code  ; ?>">
                <?php echo $statenvalue->name; ?>
            </option>
        <?php } ?>
        </select>
    </div>

</div>

<div class="news-btn-group">
    <button type="submit" class="submit-btn">
        <i class="fa fa-save"></i> Save Student
    </button>
</div>

</form>
       
      </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php if($this->session->flashdata('success')){ ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?php echo $this->session->flashdata("success"); ?>',
    confirmButtonColor: '#16a34a',
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php } ?>

<?php if($this->session->flashdata('error')){ ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?php echo $this->session->flashdata("error"); ?>',
    confirmButtonColor: '#dc2626'
});
</script>
<?php } ?>




      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script>
$(document).ready(function () {

    $('.select2').select2({
        placeholder: "Select Class",
        allowClear: true,
        width: '100%'
    });

});
</script>



<script>
$(document).ready(function () {

    $("#studentform").validate({

        ignore: [],

        rules: {

            admission_no: {
                required: true,
                remote: {
                    url: "<?php echo base_url('check_admission_number'); ?>",
                    type: "post",
                    data: {
                        admission_no: function () {
                            return $("#admission_no").val();
                        }
                    }
                }
            },

            aadhar_no: {
                required: true,
                digits: true,
                minlength: 12,
                maxlength: 12
            },

            student_name: {
                required: true,
                minlength: 3
            },

            gender: {
                required: true
            },

            dob: {
                required: true
            },

            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },

            class: {
                required: true
            },

            division: {
                required: true
            },

            religion: {
                required: true
            },

            caste: {
                required: true
            },

            mother_tongue: {
                required: true
            },

            address: {
                required: true
            },

            country: {
                required: true
            },

            state: {
                required: true
            }

        },

        messages: {

            admission_no: {
                required: "Please enter Admission Number.",
                remote: "Admission Number already exists."
            },

            aadhar_no: {
                required: "Please enter Aadhar Number.",
                digits: "Only numbers allowed.",
                minlength: "Aadhar must be 12 digits.",
                maxlength: "Aadhar must be 12 digits."
            },

            student_name: {
                required: "Please enter Student Name."
            },

            gender: {
                required: "Please select Gender."
            },

            dob: {
                required: "Please select Date of Birth."
            },

            mobile: {
                required: "Please enter Mobile Number.",
                digits: "Only numbers allowed.",
                minlength: "Mobile number must be 10 digits.",
                maxlength: "Mobile number must be 10 digits."
            },

            class: {
                required: "Please select Class."
            },

            division: {
                required: "Please select Division."
            },

            religion: {
                required: "Please enter Religion."
            },

            caste: {
                required: "Please enter Caste."
            },

            mother_tongue: {
                required: "Please enter Mother Tongue."
            },

            address: {
                required: "Please enter Address."
            },

            country: {
                required: "Please select Country."
            },

            state: {
                required: "Please select State."
            }

        },

        errorElement: "span",

        errorClass: "text-danger",

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },

        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },

        submitHandler: function (form) {

            $.ajax({

                url: "<?php echo base_url('insert_student'); ?>",

                type: "POST",

                data: $(form).serialize(),

                dataType: "json",

                beforeSend: function () {
                    $(".submit-btn").prop("disabled", true).html("Saving...");
                },

                success: function (response) {

                    $(".submit-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save Student');

                    if (response.status == 1) {

                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message
                        });

                        $("#studentform")[0].reset();
                        $('.select2').val('').trigger('change');

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message
                        });

                    }

                }

            });

            return false;

        }

    });

});
</script>