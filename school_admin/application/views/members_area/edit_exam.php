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
            Edit Exam
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('exam_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>


<form id="newsform">

<input type="hidden"
       name="id"
       value="<?php echo $exam->emId; ?>">

<div class="news-form-group">
    <label>Exam Name <span style="color:red">*</span></label>

    <input type="text"
           id="exam_name"
           name="exam_name"
           class="news-select"
           value="<?php echo $exam->emDisplayName; ?>">

    <small id="class_error" style="color:red;display:none;"></small>
</div>

<div class="news-form-group">
    <label>Abbreviation <span style="color:red">*</span></label>

    <input type="text"
           id="abbreviation"
           name="abbreviation"
           class="news-select"
           value="<?php echo $exam->emName; ?>">

    <small id="abbreviation_error" style="color:red;display:none;"></small>
</div>


<!-- Term -->
<div class="news-form-group">
    <label>Term <span style="color:red">*</span></label>

    <select name="term_id" id="term_id" class="news-select">
        <option value="">-- Select Term --</option>

        <?php foreach($term as $row){ ?>
            <option value="<?= $row->tmId; ?>"
                <?= ($row->tmId == $exam->emTmId) ? 'selected' : ''; ?>>
                <?= $row->tmName; ?>
            </option>
        <?php } ?>

    </select>

    <small id="term_error" style="color:red;display:none;"></small>
</div>

<!-- Options -->
<div class="news-form-group">
    <label>Options</label>

    <div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:8px;">

        <label>
            <input type="checkbox"
                   name="is_opened"
                   value="1"
                   <?= ($exam->emIsOpened == 1) ? 'checked' : ''; ?>>
            Opened
        </label>

        <label>
            <input type="checkbox"
                   name="is_ongoing"
                   value="1"
                   <?= ($exam->emIsOngoing == 1) ? 'checked' : ''; ?>>
            Ongoing
        </label>

        <label>
            <input type="checkbox"
                   name="is_id_grade"
                   value="1"
                   <?= ($exam->emIsGrade == 1) ? 'checked' : ''; ?>>
            Grade
        </label>

        <label>
            <input type="checkbox"
                   name="active"
                   value="1"
                   <?= ($exam->emActive == 1) ? 'checked' : ''; ?>>
            Active
        </label>

    </div>
</div>

<div class="news-btn-group">

<button class="submit-btn">
<i class="fa fa-save"></i> Update
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

    var examId = $("input[name='id']").val();

    // Exam Name Validation
    $("#exam_name").on("keyup blur", function () {

        var exam_name = $.trim($(this).val());

        if (exam_name == "") {
            $("#class_error").html("Exam Name is required.").show();
            return;
        }

        $.ajax({
            url: "<?php echo base_url('check_exam_name_edit'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                exam_name: exam_name,
                id: examId
            },
            success: function (response) {

                if (response.status == "invalid") {
                    $("#class_error").html(response.message).show();
                }
                else if (response.status == "exists") {
                    $("#class_error").html("Exam name already exists.").show();
                }
                else {
                    $("#class_error").hide();
                }

            }
        });

    });


    // Abbreviation Validation
    $("#abbreviation").on("keyup blur", function () {

        var abbreviation = $.trim($(this).val());

        if (abbreviation == "") {
            $("#abbreviation_error").html("Abbreviation is required.").show();
            return;
        }

        $.ajax({
            url: "<?php echo base_url('check_abbreviation_edit'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                abbreviation: abbreviation,
                id: examId
            },
            success: function (response) {

                if (response.status == "invalid") {
                    $("#abbreviation_error").html(response.message).show();
                }
                else if (response.status == "exists") {
                    $("#abbreviation_error").html("Abbreviation already exists.").show();
                }
                else {
                    $("#abbreviation_error").hide();
                }

            }
        });

    });


    // Update Form
   $("#newsform").submit(function (e) {

    e.preventDefault();

    var exam_name = $.trim($("#exam_name").val());
    var abbreviation = $.trim($("#abbreviation").val());
    var term_id = $("#term_id").val();

    $("#class_error").hide();
    $("#abbreviation_error").hide();
    $("#term_error").hide();

    if (exam_name == "") {
        $("#class_error").html("Exam Name is required.").show();
        return false;
    }

    if (abbreviation == "") {
        $("#abbreviation_error").html("Abbreviation is required.").show();
        return false;
    }

    if (term_id == "") {
        $("#term_error").html("Please select a term.").show();
        $("#term_id").focus();
        return false;
    }

    if ($("#class_error").is(":visible") ||
        $("#abbreviation_error").is(":visible") ||
        $("#term_error").is(":visible")) {
        return false;
    }

    $.ajax({
        url: "<?php echo base_url('update_exam'); ?>",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function (response) {

            if (response.status == "success") {

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message
                }).then(function () {
                    window.location = "<?php echo base_url('exam_list'); ?>";
                });

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
                text: "Something went wrong."
            });

        }
    });

});


});

</script>