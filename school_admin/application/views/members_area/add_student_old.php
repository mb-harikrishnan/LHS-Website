    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>



     
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
              onclick="window.location.href='<?php echo base_url('divition_list'); ?>'">
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


      <style>
        .text-danger{
            color: red;
        }

        .section-title{
    font-size:20px;
    font-weight:700;
    color:#4f46e5;
    margin:35px 0 20px;
    display:flex;
    align-items:center;
    gap:10px;
    border-bottom:1px solid #e5e7eb;
    padding-bottom:10px;
}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
}

.news-form-group{
    margin-bottom:20px;
}

.news-form-group textarea{
    resize:none;
}

@media(max-width:768px){

.form-row{
    grid-template-columns:1fr;
}

}
/* =========================================
   PAGE HEADER
========================================= */
.page-header{
    margin-bottom:30px;
}

.page-eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:13px;
    font-weight:700;
    color:#1e3a8a;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin-bottom:10px;
}

.eyebrow-pulse{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#2563eb;
    animation:pulse 1.8s infinite;
}

@keyframes pulse{
    0%{
        transform:scale(.9);
        opacity:.7;
    }
    70%{
        transform:scale(1.4);
        opacity:0;
    }
    100%{
        transform:scale(.9);
        opacity:0;
    }
}

.page-title{
    font-size:34px;
    font-weight:800;
    color:#111827;
    margin:0;
}

.page-sub{
    color:#6b7280;
    margin-top:8px;
    font-size:15px;
}

/* =========================================
   CARD
========================================= */
.card{
    background:#ffffff;
    border-radius:26px;
    padding:35px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
    border:1px solid #eef2f7;
}

.card-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:35px;
}

.card-title{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:21px;
    font-weight:700;
    color:#111827;
}

/* =========================================
   ACTION BUTTON
========================================= */
.card-action{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:12px 22px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 22px rgba(37,99,235,.22);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 28px rgba(37,99,235,.32);
}

/* =========================================
   FORM
========================================= */
#newsform{
    width:100%;
}

.news-form-group{
    margin-bottom:28px;
}

.news-form-group label{
    display:block;
    margin-bottom:12px;
    font-size:14px;
    font-weight:700;
    color:#374151;
}

/* =========================================
   SELECT BOX
========================================= */
.news-select{
    width:100%;
    padding:15px 18px;
    border-radius:16px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:15px;
    color:#111827;
    transition:.3s ease;
    outline:none;
}

.news-select:focus{
    border-color:#2563eb;
    background:#fff;
    box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

/* =========================================
   UPLOAD AREA
========================================= */
.upload-area{
    position:relative;
    border:2px dashed #2563eb;
    border-radius:24px;
    background:linear-gradient(to bottom,#eff6ff,#ffffff);
    padding:45px 25px;
    text-align:center;
    cursor:pointer;
    transition:.35s ease;
    overflow:hidden;
}

.upload-area:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 35px rgba(37,99,235,.12);
}

.upload-area.dragover{
    background:#dbeafe;
    border-color:#1e40af;
}

.upload-content{
    pointer-events:none;
}

.upload-icon{
    width:85px;
    height:85px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:34px;
    color:#1e3a8a;
}

.upload-content h4{
    margin:0 0 8px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}

.upload-content p{
    margin:0 0 20px;
    color:#6b7280;
    font-size:14px;
}

.browse-btn{
    border:none;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:12px 22px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(37,99,235,.2);
}

.submit-btn{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:#fff;
    padding:14px 26px;
    border-radius:14px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:10px;
    transition:.3s ease;
    box-shadow:0 10px 22px rgba(37,99,235,.22);
}


.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 30px rgba(37,99,235,.35);
    background:linear-gradient(135deg,#1e40af,#3b82f6);
}

.submit-btn:active{
    transform:scale(.98);
}

.submit-btn i{
    font-size:14px;
}


.select2-container .select2-selection--single{
    height:52px !important;
    border:1px solid #d1d5db !important;
    border-radius:16px !important;
    background:#f9fafb !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height:52px !important;
    padding-left:18px !important;
    color:#111827;
}

.select2-container--default .select2-selection--single .select2-selection__arrow{
    height:52px !important;
    right:12px !important;
}

.select2-dropdown{
    border-radius:12px;
    border:1px solid #d1d5db;
}

.select2-search__field{
    border-radius:8px !important;
}




</style>

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