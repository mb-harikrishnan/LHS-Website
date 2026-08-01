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
            Add Employee
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('employee_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

   <div class="employee-form-card">
<form id="newsform" method="post" action="<?php echo base_url('insert_employee'); ?>">

    <div class="form-grid">

        <!-- Name -->
        <div class="news-form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text"
                   id="name"
                   name="name"
                   class="news-select"
                   placeholder="Enter Name">
        </div>

        <!-- Password -->
        <div class="news-form-group">
            <label>Password <span class="text-danger">*</span></label>
            <input type="password"
                   id="password"
                   name="password"
                   class="news-select"
                   placeholder="Enter Password">
        </div>

        <!-- Mobile -->
        <div class="news-form-group">
            <label>Mobile <span class="text-danger">*</span></label>
            <input type="text"
                   id="mobile"
                   name="mobile"
                   maxlength="10"
                   class="news-select"
                   placeholder="Enter Mobile Number">
        </div>

        <!-- Designation -->
        <div class="news-form-group">
            <label>Designation <span class="text-danger">*</span></label>
            <input type="text"
                   id="designation"
                   name="designation"
                   class="news-select"
                   placeholder="Enter Designation">
        </div>

        <!-- Class -->
        <div class="news-form-group">
            <label>Class <span class="text-danger">*</span></label>

            <select id="class_id"
                    name="class_id"
                    class="news-select select-search">
                <option value="">Select Class</option>

                <?php
                $class_master = $this->db->query("SELECT * FROM class_master")->result();

                foreach($class_master as $class){ ?>
                    <option value="<?= $class->cmId; ?>">
                        <?= $class->cmName; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Division -->
        <div class="news-form-group">
            <label>Division <span class="text-danger">*</span></label>

            <select id="division_id"
                    name="division_id"
                    class="news-input select-search">
                <option value="">Select Division</option>

                <?php
                $division_master = $this->db->query("SELECT * FROM division_master")->result();

                foreach($division_master as $division){ ?>
                    <option value="<?= $division->dmId; ?>">
                        <?= $division->dmName; ?>
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

<!-- jQuery -->


<style>
    /* Validation error text */
label.error,
span.error {
    color: #dc2626;
    font-size: 13px;
    margin-top: 5px;
    display: block;
    font-weight: 500;
}

/* Red border for invalid fields */
input.error,
select.error,
textarea.error {
    border: 1px solid #dc2626 !important;
}

/* Select2 red border */
.select2-container--default .select2-selection--single.error,
.select2-container--default .select2-selection--single {
    border-color: #dc2626;
}
</style>

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

    ignore: [],

    rules: {

           name: {
            required: true,
            remote: {
                url: "<?php echo base_url('check_name_exist'); ?>",
                type: "post",
                data: {
                    name: function () {
                        return $("#name").val();
                    },
                    employee_id: function () {
                        return $("#employee_id").val(); // Hidden field for edit
                    }
                }
            }
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
        },

        designation: {
            required: true
        },

        class_id: {
            required: true
        },

        division_id: {
            required: true
        }

    },

    messages: {

        name: {
            required: "Please enter name",
            remote: "Name already exists"
        },

        

        password: {
            required: "Please enter password",
            minlength: "Password must contain at least 6 characters"
        },

        mobile: {
            required: "Please enter mobile number",
            digits: "Only numbers allowed",
            minlength: "Enter valid mobile number",
            maxlength: "Enter valid mobile number"
        },

        designation: {
            required: "Please enter designation"
        },

        class_id: {
            required: "Please select class"
        },

        division_id: {
            required: "Please select division"
        }

    },

    errorElement: "span",
    errorClass: "error",

    errorPlacement: function (error, element) {

        if (element.hasClass("select-search")) {
            error.insertAfter(element.next(".select2"));
        } else {
            error.insertAfter(element);
        }

    }

});

</script>



<script>
$(document).ready(function () {

    $('.select-search').select2({
        width: '100%',
        placeholder: 'Select Option',
        allowClear: true
    });

});
</script>