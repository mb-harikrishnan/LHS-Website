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
            Add Exam
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('exam_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform" method="post">

    <div class="news-form-group">
        <label for="class_name">Exam Name <span style="color:red">*</span></label>

        <input type="text"
               id="exam_name"
               name="exam_name"
               class="news-select"
               placeholder="Enter Exam Name">

        <small id="class_error" style="color:red;display:none;"></small>
    </div>

    <div class="news-form-group">
        <label for="class_name">Abbreviation Of  Exam <span style="color:red">*</span></label>

        <input type="text"
               id="abbreviation"
               name="abbreviation"
               class="news-select"
               placeholder="Enter Abbreviation">

        <small id="abbreviation_error" style="color:red;display:none;"></small>
    </div>

    <div class="news-btn-group">
        <button type="submit" class="submit-btn">
            <i class="fa fa-save"></i> Submit
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

$(document).ready(function () {

    // ============================
    // Check Exam Name Availability
    // ============================
    $("#exam_name").on("keyup blur", function () {

        var exam_name = $.trim($(this).val());

        if (exam_name === "") {
            $("#class_error")
                .html("Exam Name is required.")
                .show();
            return;
        }
        if (exam_name === "") {
            $("#class_error")
                .html("Exam Name is required.")
                .show();
            return;
        }

        $("#class_error").hide();

        $.ajax({
            url: "<?php echo base_url('check_exam_name'); ?>",
            type: "POST",
            data: {
                exam_name: exam_name
            },
            dataType: "json",
            success: function (response) {

                if (response.status === "invalid") {
                    $("#class_error")
                        .html(response.message)
                        .show();
                }
                else if (response.status === "exists") {
                    $("#class_error")
                        .html("Exam name already exists.")
                        .show();
                }
                else {
                    $("#class_error").hide();
                }

            }
        });

    });



    // Check Abbreviation
$("#abbreviation").on("keyup blur", function () {

    var abbreviation = $.trim($(this).val());

    if (abbreviation === "") {
        $("#abbreviation_error")
            .html("Abbreviation is required.")
            .show();
        return;
    }

    $("#abbreviation_error").hide();

    $.ajax({
        url: "<?php echo base_url('check_abbreviation'); ?>",
        type: "POST",
        data: {
            abbreviation: abbreviation
        },
        dataType: "json",
        success: function (response) {

            if (response.status === "invalid") {
                $("#abbreviation_error")
                    .html(response.message)
                    .show();
            }
            else if (response.status === "exists") {
                $("#abbreviation_error")
                    .html("Abbreviation already exists.")
                    .show();
            }
            else {
                $("#abbreviation_error").hide();
            }

        }
    });

});


    // ============================
    // Submit Form
    // ============================
    $("#newsform").on("submit", function (e) {

        e.preventDefault();

        var exam_name = $.trim($("#exam_name").val());
        var abbreviation = $.trim($("#abbreviation").val());

        if (exam_name === "") {
            $("#class_error")
                .html("Exam Name is required.")
                .show();
            return false;
        }

        if (abbreviation === "") {
            Swal.fire({
                icon: "warning",
                title: "Validation",
                text: "Abbreviation is required."
            });
            return false;
        }

        if ($("#class_error").is(":visible")) {
            return false;
        }

        $.ajax({
            url: "<?php echo base_url('insert_exam'); ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            success: function (response) {

                if (response.status === "success") {

                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message
                    });

                    $("#newsform")[0].reset();
                    $("#class_error").hide();

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message
                    });

                }

            },

            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong. Please try again."
                });
            }

        });

    });

});

</script>