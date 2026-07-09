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
            Add Employee
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('employee_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

   <div class="employee-form-card">

<form id="newsform"
      method="post"
      action="<?php echo base_url('insert_employee'); ?>">

    <div class="form-grid">

        <!-- Name -->
        <div class="news-form-group">
            <label>Name</label>
            <input type="text"
                   name="name"
                   class="news-input"
                   placeholder="Enter Name">
        </div>

        <!-- Password -->
        <div class="news-form-group">
            <label>Password</label>
            <input type="password"
                   name="password"
                   class="news-input"
                   placeholder="Enter Password">
        </div>

        <!-- Mobile -->
        <div class="news-form-group">
            <label>Mobile</label>
            <input type="text"
                   name="mobile"
                   class="news-input"
                   placeholder="Enter Mobile Number">
        </div>

        <!-- Designation -->
        <div class="news-form-group">
            <label>Designation</label>
            <input type="text"
                   name="designation"
                   class="news-input"
                   placeholder="Enter Designation">
        </div>

       <!-- Class -->
<div class="news-form-group">
    <label>Class</label>

    <select name="class_id" class="news-input select-search">
        <option value="">Select Class</option>

        <?php 
        $sql = "SELECT * FROM classmaster";
        $class_master = $this->db->query($sql)->result();

        foreach($class_master as $class){ ?>
            <option value="<?php echo $class->cmId; ?>">
                <?php echo $class->cmName; ?>
            </option>
        <?php } ?>
    </select>
</div>

<!-- Division -->
<div class="news-form-group">
    <label>Division</label>

    <select name="division_id" class="news-input select-search">
        <option value="">Select Division</option>

        <?php
        $sql = "SELECT * FROM divisionmaster";
        $division_master = $this->db->query($sql)->result();

        foreach($division_master as $division){ ?>
            <option value="<?php echo $division->dmId; ?>">
                <?php echo $division->dmName; ?>
            </option>
        <?php } ?>
    </select>
</div>
    </div>

    <div class="submit-section">
        <button type="submit" class="submit-btn">
            <i class="fa fa-save"></i> Submit
        </button>
    </div>

</form>

</div>
      </div>
      <!-- End Reports Table Card -->

    <style>
.employee-form-card{
    background:#fff;
    padding:35px;
    border-radius:16px;
}

/* GRID */
.form-grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:25px;
}

/* MOBILE */
@media(max-width:768px){
    .form-grid{
        grid-template-columns:1fr;
    }
}

/* LABEL */
.news-form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
    color:#374151;
}

/* INPUT */
.news-input{
    width:100%;
    height:52px;
    border:1px solid #d1d5db;
    border-radius:10px;
    padding:0 16px;
    font-size:15px;
    background:#fff;
    transition:0.3s;
}

/* FOCUS */
.news-input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.10);
    outline:none;
}

/* PLACEHOLDER */
.news-input::placeholder{
    color:#9ca3af;
}

/* SELECT */
select.news-input{
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='gray' viewBox='0 0 16 16'%3E%3Cpath d='M1.5 5l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat;
    background-position:right 15px center;
    background-size:14px;
    padding-right:40px;
}

/* BUTTON AREA */
.submit-section{
    text-align:center;
    margin-top:35px;
}

/* BUTTON */
.submit-btn{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:14px 50px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.submit-btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}


.validation-error{
    color:#dc2626;
    font-size:13px;
    margin-top:5px;
    display:block;
}
</style>

<!-- jQuery -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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




<script>

$("#newsform").validate({

    rules: {

        name: {
            required: true
        },

        password: {
            required: true,
            minlength: 6
        },

        mobile: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
        }

    },

    messages: {

        name: {
            required: "Please enter name"
        },

        password: {
            required: "Please enter password",
            minlength: "Password must be minimum 6 characters"
        },

        mobile: {
            required: "Please enter mobile number",
            digits: "Only numbers allowed",
            minlength: "Mobile number must be 10 digits",
            maxlength: "Mobile number must be 10 digits"
        }

    },

    errorElement: 'span',

    errorPlacement: function(error, element) {
        error.addClass('validation-error');
        error.insertAfter(element);
    }

});

</script>

<style>

/* SELECT2 DESIGN */
.select2-container{
    width:100% !important;
}

.select2-container--default .select2-selection--single{
    height:52px !important;
    border:1px solid #d1d5db !important;
    border-radius:10px !important;
    padding:10px 16px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height:30px !important;
    padding-left:0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow{
    height:50px !important;
    right:10px !important;
}

.select2-dropdown{
    border-radius:10px !important;
    border:1px solid #d1d5db !important;
}

.select2-search__field{
    border:1px solid #d1d5db !important;
    border-radius:6px !important;
    padding:8px !important;
}

</style>

<script>
$(document).ready(function () {

    $('.select-search').select2({
        placeholder: "Select Option",
        allowClear: true
    });

});
</script>