<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;

/*
 * Robust "is this a grade exam" check.
 * Previously the view read $exam->emIsGrade directly. If the controller
 * ever forgot to pass $exam, this threw a PHP fatal ("trying to get
 * property of non-object") which killed page rendering before the
 * select2 <script> tags ran — which is why the dropdowns looked broken.
 * Now we check $exam first, fall back to $allocation[0], and default to 0.
 */
$isGrade = 0;
if (isset($exam) && isset($exam->emIsGrade) && $exam->emIsGrade == 1) {
    $isGrade = 1;
} elseif (isset($allocation[0]->emIsGrade) && $allocation[0]->emIsGrade == 1) {
    $isGrade = 1;
}

/*
 * Build a list of subject IDs already allocated so they can be excluded
 * from the "Add More Subjects" list below. Without this, a user could
 * re-select an already-added subject and create a duplicate mark row.
 * Assumption: each $allocation row carries the subject id as emdSmId
 * (matching the emdCmId / emdEmId naming pattern already in this file).
 * If your row uses a different field name, change $row->emdSmId below.
 */
$alreadyAllocatedSubjectIds = [];
if (!empty($allocation)) {
    foreach ($allocation as $row) {
        if (isset($row->emdSmId)) {
            $alreadyAllocatedSubjectIds[] = $row->emdSmId;
        }
    }
}
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Edit Mark Allocation
          </div>
          <button class="card-action"
              onclick="window.location.href='<?php echo base_url('allocation_list'); ?>'">
          <i class="fa fa-upload"></i> List
          </button>
        </div>

<form id="editForm">

<?php
$className = $allocation[0]->cmName;
$examName  = $allocation[0]->emDisplayName;
?>

<input type="hidden" name="emId" value="<?php echo $allocation[0]->emdEmId;?>">
<input type="hidden" name="cmId" value="<?php echo $allocation[0]->emdCmId;?>">

    <div class="news-form-group">
        <label class="cd-label" for="cmIdDisplay">Select Class</label>
        <select id="cmIdDisplay" class="news-select" disabled>
            <option selected><?php echo $className; ?></option>
        </select>
    </div>

    <div class="news-form-group">
        <label class="cd-label" for="emIdDisplay">Select Exam</label>
        <select id="emIdDisplay" class="news-select" disabled>
            <option selected><?php echo $examName; ?></option>
        </select>
    </div>

    <div class="news-form-group">
        <label class="cd-label">Subject Marks</label>
        <div id="subjectMarksContainer">
            <?php foreach($allocation as $row){ ?>
            <div class="subject-mark-row" data-emdid="<?php echo $row->emdId; ?>" style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">

                <label style="min-width:140px;margin:0;"><?php echo $row->smName; ?></label>

                <input type="hidden" name="emdId[]" value="<?php echo $row->emdId; ?>">

                <input
                    type="number"
                    class="news-select subject-mark-input"
                    name="marks[]"
                    value="<?php echo $isGrade ? 100 : $row->emdMaxMark; ?>"
                    placeholder="Maximum Mark"
                    min="1"
                    <?php echo $isGrade ? 'readonly' : ''; ?>>

                <button type="button" class="delete-row-btn" data-emdid="<?php echo $row->emdId; ?>">
                    <i class="fa-solid fa-trash"></i>
                </button>

            </div>
            <?php } ?>
        </div>
    </div>

    <div class="news-form-group">
        <label class="cd-label" for="addSubjects">Add More Subjects</label>
        <select name="addSubjects[]" id="addSubjects" class="news-select" multiple="multiple">
            <?php if(!empty($subjects)){ foreach($subjects as $subject){
                $subjectId   = isset($subject->smId) ? $subject->smId : (isset($subject->id) ? $subject->id : '');
                $subjectName = isset($subject->smName) ? $subject->smName : (isset($subject->name) ? $subject->name : '');

                // Skip subjects that already have an allocation row above,
                // so they can't be picked twice.
                if (in_array($subjectId, $alreadyAllocatedSubjectIds)) {
                    continue;
                }
            ?>
                <option value="<?php echo $subjectId; ?>"><?php echo $subjectName; ?></option>
            <?php } } ?>
        </select>
        <div id="newSubjectMarksContainer" style="margin-top:10px;"></div>
    </div>

    <div class="news-btn-group">
        <button type="submit" class="submit-btn">
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Multiple Select2 (same theme as Add page) */
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

.select2-container--default .select2-search--inline .select2-search__field{
    margin-top:5px !important;
    font-size:14px;
}

.select2-dropdown{
    border-radius:8px;
    border:1px solid #d1d5db;
}

/* Single-select (disabled) fields styled to match, since select2
   doesn't always paint disabled selects consistently across browsers */
.select2-container--default.select2-container--disabled .select2-selection--single{
    background:#f3f4f6 !important;
    border:1px solid #d1d5db !important;
    border-radius:8px !important;
    height:40px !important;
    display:flex;
    align-items:center;
    cursor:not-allowed;
}

.select2-container--default.select2-container--disabled .select2-selection__arrow{
    display:none;
}

/* Delete button to match Add page's accent styling */
.delete-row-btn{
    width:44px;
    height:40px;
    flex-shrink:0;

    border:none;
    border-radius:8px;

    background:#ef4444;
    color:#fff;

    display:flex;
    align-items:center;
    justify-content:center;

    cursor:pointer;
    transition:.3s;
}

.delete-row-btn i{
    font-size:16px;
}

.delete-row-btn:hover{
    background:#dc2626;
}
</style>

<script>
// Passed once from PHP so all JS logic below stays in sync with the
// server-side $isGrade flag computed at the top of this file.
var IS_GRADE_EXAM = <?php echo $isGrade ? 1 : 0; ?>;

$(function(){

    // Style the disabled class/exam fields like the Add page selects
    $('#cmIdDisplay').select2({
        width: '100%',
        minimumResultsForSearch: -1
    });

    $('#emIdDisplay').select2({
        width: '100%',
        minimumResultsForSearch: -1
    });

    $('#addSubjects').select2({
        width: '100%',
        placeholder: 'Select subjects to add',
        closeOnSelect: false
    });

    // Rebuild the "newly added" rows whenever the Add Subjects selection changes
    $('#addSubjects').on('change', function () {

        var selected = $(this).select2('data'); // [{id, text}, ...]
        var container = $('#newSubjectMarksContainer');

        // Preserve marks already typed for subjects still selected
        var existing = {};
        container.find('.new-subject-mark-input').each(function () {
            existing[$(this).data('smid')] = $(this).val();
        });

        container.empty();

        selected.forEach(function (subj) {

            // Rule: grade exams always lock every mark to 100.
            // Non-grade exams start blank and stay editable.
            var prevVal = existing[subj.id] !== undefined
                ? existing[subj.id]
                : (IS_GRADE_EXAM ? 100 : '');

            var readOnly = IS_GRADE_EXAM ? 'readonly' : '';

            var row = $(
                '<div class="subject-mark-row" style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">' +
                    '<label style="min-width:140px;margin:0;">' + subj.text + '</label>' +
                    '<input type="hidden" name="newSmId[]" value="' + subj.id + '">' +
                    '<input type="number" ' +
                        'class="news-select new-subject-mark-input" ' +
                        'name="newMarks[]" ' +
                        'data-smid="' + subj.id + '" ' +
                        'value="' + prevVal + '" ' +
                        readOnly + ' ' +
                        'min="1">' +
                '</div>'
            );

            container.append(row);
        });
    });

});
</script>

<script>
    $("#editForm").submit(function(e){

    e.preventDefault();

    var invalid = false;
    $('.new-subject-mark-input').each(function(){
        var val = $(this).val();
        if (val === '' || isNaN(val) || Number(val) <= 0) {
            invalid = true;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (invalid) {
        Swal.fire('Error', 'Please enter a valid mark for every newly added subject.', 'error');
        return;
    }

    $.ajax({

        url:"<?php echo base_url('update_allocation');?>",

        type:"POST",

        data:$(this).serialize(),

        dataType:"json",

        beforeSend: function () {
            $(".submit-btn").prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        },

        success:function(res){

            $(".submit-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Update');

            if(res.status=="success")
            {
                Swal.fire({
                    icon:'success',
                    title:'Updated',
                    text:'Allocation Updated Successfully'
                }).then(function(){

                    window.location.href="<?php echo base_url('allocation_list');?>";

                });
            }
            else
            {
                Swal.fire('Error',res.message,'error');
            }

        },

        error: function(){
            $(".submit-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Update');
            Swal.fire('Error', 'Something went wrong', 'error');
        }

    });

});
</script>

<script>
    $(document).on('click', '.delete-row-btn', function(){

    var btn = $(this);
    var emdId = btn.data('emdid');
    var row = btn.closest('.subject-mark-row');

    Swal.fire({
        icon: 'warning',
        title: 'Delete this allocation?',
        text: 'This cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        confirmButtonColor: '#dc2626'
    }).then(function(result){

        if(!result.isConfirmed) return;

        $.ajax({
            url: "<?php echo base_url('delete_allocation'); ?>",
            type: "POST",
            data: { emdId: emdId },
            dataType: "json",
            success: function(res){
                if(res.status == "success"){
                    row.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(){
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
});
</script>