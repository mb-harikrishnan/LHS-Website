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
            Add Mark Allocation
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('allocation_list'); ?>'">
          <i class="fa fa-upload"></i> List 
          </button>
        </div>

<form id="newsform" method="post">


        <div class="news-form-group">
            <label class="cd-label" for="cmId">Select Class</label>
            <select name="cmId" id="cmId" class="news-select">
                <option value="">-- Select Class --</option>
                <?php if(!empty($classes)){ foreach($classes as $class){ ?>
                <option value="<?php echo $class->cmId; ?>"><?php echo $class->cmName; ?></option>
                <?php } } ?>
            </select>
        </div>

        <div class="news-form-group">
         <label class="cd-label" for="cmId">Select Exam</label>

           <select name="emId" id="emId" class="news-select">
        <option value="">-- Select Exam --</option>
        <?php if(!empty($exams)){ foreach($exams as $exam){ 
          $examId = isset($exam->emId) ? $exam->emId : (isset($exam->examId) ? $exam->examId : (isset($exam->id) ? $exam->id : ''));
          $examName = isset($exam->emName) ? $exam->emName : (isset($exam->examName) ? $exam->examName : (isset($exam->name) ? $exam->name : ''));
        ?>
          <option value="<?php echo $examId; ?>"><?php echo $examName; ?></option>
        <?php } } ?>
      </select>
        </div>


      <!-- Custom Multi-select Dropdown Container -->
        <div class="news-form-group">
            <label class="cd-label">Select Subjects</label>

            <select name="smId[]" id="subjects" class="news-select" multiple="multiple">
                <?php if(!empty($subjects)){ 
                    foreach($subjects as $subject){

                        $subjectId = isset($subject->smId) ? $subject->smId :
                                    (isset($subject->subjectId) ? $subject->subjectId :
                                    (isset($subject->id) ? $subject->id : ''));

                        $subjectName = isset($subject->smName) ? $subject->smName :
                                    (isset($subject->subjectName) ? $subject->subjectName :
                                    (isset($subject->name) ? $subject->name : ''));
                ?>
                    <option value="<?php echo $subjectId; ?>">
                        <?php echo $subjectName; ?>
                    </option>
                <?php
                    }
                } ?>
            </select>
        </div>

       <div class="news-form-group">
    <label class="cd-label">Subject Marks</label>
    <div id="subjectMarksContainer">
        <small style="color:#6b7280;">Select subjects above to enter their marks.</small>
    </div>
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>
    /* Multiple Select2 */
.select2-container--default .select2-selection--multiple{
    min-height:40px !important;
    border:1px solid #d1d5db !important;
    border-radius:8px !important;
    background:#f9fafb !important;
    padding:4px 10px !important;
    display:flex !important;
    align-items:center;
}

.select2-container--default.select2-container--focus .select2-selection--multiple{
    border-color:#2563eb !important;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

/* Selected items */
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    background:#2563eb !important;
    border:none !important;
    color:#fff !important;
    border-radius:5px !important;
    padding:2px 8px !important;
    margin-top:3px !important;
}

.select2-container--default .select2-selection__choice__remove{
    color:#fff !important;
    margin-right:5px;
}

/* Search input */
.select2-container--default .select2-search--inline .select2-search__field{
    margin-top:5px !important;
    font-size:14px;
}

/* Dropdown */
.select2-dropdown{
    border-radius:8px;
    border:1px solid #d1d5db;
}
</style>

<script>
$(function(){

    $('#cmId').select2({
        width:'100%',
        placeholder:'Select Class'
    });

    $('#emId').select2({
        width:'100%',
        placeholder:'Select Exam'
    });

    $('#subjects').select2({
        width:'100%',
        placeholder:'Select Subjects',
        closeOnSelect:false
    });

});
</script>


<script>
  $(document).ready(function () {

    // Initialize Select2
    $('#cmId').select2({
        width: '100%',
        placeholder: 'Select Class'
    });

    $('#emId').select2({
        width: '100%',
        placeholder: 'Select Exam'
    });

    $('#subjects').select2({
        width: '100%',
        placeholder: 'Select Subjects',
        closeOnSelect: false
    });

    // Validation
    $("#newsform").validate({

        ignore: ":hidden:not(select)",

        rules: {
            cmId: {
                required: true
            },
            emId: {
                required: true
            },
            "smId[]": {
                required: true
            },
            marks: {
                required: true,
                number: true,
                min: 1
            }
        },

        messages: {
            cmId: {
                required: "Please select Class."
            },
            emId: {
                required: "Please select Exam."
            },
            "smId[]": {
                required: "Please select at least one Subject."
            },
            marks: {
                required: "Please enter Exam Mark.",
                number: "Please enter valid marks.",
                min: "Marks must be greater than zero."
            }
        },

        errorElement: "small",
        errorClass: "text-danger",

        errorPlacement: function (error, element) {

            if (element.hasClass("select2-hidden-accessible")) {
                error.insertAfter(element.next(".select2"));
            } else {
                error.insertAfter(element);
            }

        },

        highlight: function (element) {

            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next('.select2').find('.select2-selection')
                    .addClass("is-invalid");
            } else {
                $(element).addClass("is-invalid");
            }

        },

        unhighlight: function (element) {

            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next('.select2').find('.select2-selection')
                    .removeClass("is-invalid");
            } else {
                $(element).removeClass("is-invalid");
            }

        },

        submitHandler: function (form) {

    // Manual validation: every selected subject needs a mark
    var missing = false;
    $('.subject-mark-input').each(function(){
        var val = $(this).val();
        if (val === '' || isNaN(val) || Number(val) <= 0) {
            missing = true;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if ($('.subject-mark-input').length === 0) {
        Swal.fire('Error', 'Please select at least one subject.', 'error');
        return false;
    }

    if (missing) {
        Swal.fire('Error', 'Please enter a valid mark for every selected subject.', 'error');
        return false;
    }

    $.ajax({
        url: "<?php echo base_url('save_exam_mark_details'); ?>",
        type: "POST",
        data: $(form).serialize(),
        dataType: "json",
        beforeSend: function () {
            $(".submit-btn").prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        },
        success: function (response) {
            $(".submit-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Submit');

            if (response.status == "success") {
                Swal.fire({ icon: "success", title: "Success", text: response.message });
                form.reset();
                $('#cmId,#emId,#subjects').val(null).trigger('change');
                $('#subjectMarksContainer').html('<small style="color:#6b7280;">Select subjects above to enter their marks.</small>');
            } else {
                Swal.fire({ icon: "error", title: "Error", text: response.message });
            }
        },
        error: function () {
            $(".submit-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Submit');
            Swal.fire({ icon: "error", title: "Error", text: "Something went wrong." });
        }
    });

    return false;
}

    });

    // Remove validation immediately when Select2 value changes
    $('#cmId').on('change', function () {
        $(this).valid();
    });

    $('#emId').on('change', function () {
        $(this).valid();
    });

 // Rebuild per-subject mark inputs whenever subject selection changes
$('#subjects').on('change', function () {

    var selected = $(this).select2('data'); // [{id, text}, ...]
    var container = $('#subjectMarksContainer');

    // Preserve any marks already typed, keyed by subject id
    var existing = {};
    container.find('.subject-mark-input').each(function(){
        existing[$(this).data('smid')] = $(this).val();
    });

    container.empty();

    if (selected.length === 0) {
        container.html('<small style="color:#6b7280;">Select subjects above to enter their marks.</small>');
        return;
    }

    selected.forEach(function (subj) {

        var prevVal = existing[subj.id] !== undefined ? existing[subj.id] : '';

        var row = $(
            '<div class="news-form-group subject-mark-row" style="display:flex;align-items:center;gap:10px;">' +
                '<label style="min-width:140px;margin:0;">' + subj.text + '</label>' +
                '<input type="number" ' +
                       'name="marks[' + subj.id + ']" ' +
                       'class="news-select subject-mark-input" ' +
                       'data-smid="' + subj.id + '" ' +
                       'placeholder="Enter mark" ' +
                       'value="' + prevVal + '" ' +
                       'min="1">' +
            '</div>'
        );

        container.append(row);
    });

    $(this).valid(); // re-check subjects[] validity
});

    // Remove error while typing
    $('#marks').on('keyup blur', function () {
        $(this).valid();
    });

});
</script>

