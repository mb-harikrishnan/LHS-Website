<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<!-- Report Page Styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<style>
/* ---------- Mark List Card (matches Marks Entry design) ---------- */
.card {
  padding:15px;
    border-radius:10px;
    box-shadow:none;
    border:1px solid #ddd;
}
.card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eef0f5;
    padding-bottom: 14px;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 10px;
}
.card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    color: #1e2a5e;
    font-size: 17px;
}
.card-action {
    background: #1e2a5e;
    color: #fff;
    border: none;
    padding: 9px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background .15s ease;
}
.card-action:hover { background: #16204a; }

/* Exam tag badge (e.g. "PA1") floating above the table, like the entry page */
.exam-tag {
    position: absolute;
    top: -12px;
    left: 24px;
    background: #fff;
    border: 1px solid #d3d8e4;
    border-radius: 8px;
    padding: 4px 14px;
    font-weight: 700;
    font-size: 13px;
    color: #1e2a5e;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.report-table-wrap{
    overflow-x:auto;
    border:1px solid #d9d9d9;
    border-radius:10px;
}
#marksTable{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    font-size:15px;
}
#marksTable th, #marksTable td {
    border: 1px solid #ddd;
    padding: 10px 12px;
    text-align: center;
    font-size: 13.5px;
}
#marksTable thead th{
    background:#f4f4f4;
    color:#23469d;
    font-weight:700;
    text-align:center;
    border:1px solid #d8d8d8;
    padding:10px 8px;
    vertical-align:middle;
}

#marksTable thead th small{
    display:block;
    margin-top:3px;
    color:#2d3d6e;
    font-size:13px;
    font-weight:600;
}

#marksTable tbody tr:hover {
    background:#fafafa;
}

#marksTable tbody td{
    border:1px solid #dcdcdc;
    padding:8px;
    text-align:center;
    vertical-align:middle;
    background:#fff;
}

.sl-cell{
    width:45px;
    color:#23469d;
    font-weight:600;
}

/* SL column */
#marksTable td.sl-cell {
    font-weight: 600;
    color: #444;
}

/* Admission No + Student Name styled like blue links, same as entry page */
#marksTable td.admission-cell,
#marksTable td.student-name-cell {
    color: #1e5fbf;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}



.admission-cell{
    width:120px;
    color:#23469d;
    font-weight:600;
    text-align:center !important;
}

.student-name-cell{
    min-width:280px;
    color:#23469d;
    font-weight:600;
    text-align:left !important;
    padding-left:12px !important;
}

.mark-input{
    width:70px;
    height:32px;
    border:1px solid #cfcfcf;
    border-radius:6px;
    text-align:center;
    font-size:18px;
    background:#fff;
    box-sizing:border-box;
}

.mark-input:focus{
    border-color:#23469d;
    outline:none;
    box-shadow:0 0 0 2px rgba(35,70,157,.15);
}

.mark-input[readonly]{
    background:#fff;
}

.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate{
    margin-top:12px;
}

/* Mark input boxes - plain bordered, like entry page */
.mark-input {
    width: 60px;
    padding: 6px 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    text-align: center;
    font-size: 13.5px;
    background: #fff;
    color: #333;
    transition: all .15s ease;
}
.mark-input[readonly] {
    background: #fff;
    color: #333;
}
.mark-input.editing {
    background: #fff;
    border-color: #1e2a5e;
    box-shadow: 0 0 0 2px rgba(30,42,94,.12);
    cursor: text;
}
.mark-input:focus {
    outline: none;
    border-color: #1e2a5e;
}

.table-actions {
    margin-top: 18px;
    display: flex;
    gap: 10px;
}
#editBtn, #updateBtn {
    padding: 10px 26px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: background .15s ease, opacity .15s ease;
}
#editBtn { background: #1e2a5e; color: #fff; }
#editBtn:hover { background: #16204a; }
#updateBtn { background: #1e9e5e; color: #fff; }
#updateBtn:hover { background: #167e49; }
#updateBtn:disabled { opacity: .7; cursor: not-allowed; }

/* DataTables tweaks to match theme */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d3d8e4;
    border-radius: 8px;
    padding: 6px 10px;
    margin-left: 6px;
}
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #d3d8e4;
    border-radius: 6px;
    padding: 4px 8px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 6px !important;
}

.mark-error {
    color: #d9534f;
    font-size: 10.5px;
    font-weight: 600;
    margin-top: 3px;
    line-height: 1.2;
}
.mark-input.input-error {
    border-color: #d9534f !important;
    box-shadow: 0 0 0 2px rgba(217,83,79,.15) !important;
}
</style>

<!-- Reports Table Card -->
<div class="card">

    <!-- <?php if (isset($exam) && !empty($exam)) { ?>
        <div class="exam-tag"><?= is_object($exam) ? $exam->emName : $exam ?></div>
    <?php } ?> -->

    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Mark List
        </div>

        <button class="card-action" onclick="window.location.href='<?php echo base_url('Marksentry_list'); ?>'">
            <i class="fa fa-upload"></i>  Mark List
        </button>
    </div>

    <div class="report-table-wrap" id="reportsDataTable">
        <table class="table" id="marksTable">
            <thead>
    <tr>
        <th>SL</th>
        <th>Admission No</th>
        <th>Student Name</th>
        <?php foreach ($subjects as $sub) { ?>
            <th>
                <?= $sub->smName ?>
                <br><small style="font-weight:500; text-transform:none;">
                    (Max: <?= isset($maxMarks[$sub->esSmId]) ? $maxMarks[$sub->esSmId] : '-' ?>)
                </small>
            </th>
        <?php } ?>
    </tr>
</thead>
<tbody>
    <?php $i = 1; foreach ($students as $stu) { ?>
        <tr>
            <td class="sl-cell"><?= $i++ ?></td>
            <td class="admission-cell"><?= $stu->smAdmissionNo ?></td>
            <td class="student-name-cell"><?= $stu->smName ?></td>
            <?php foreach ($subjects as $sub) { ?>
                <td>
                    <input
                        type="text"
                        class="mark-input"
                        readonly
                        data-student="<?= $stu->smId ?>"
                        data-subject="<?= $sub->esSmId ?>"
                        data-max="<?= isset($maxMarks[$sub->esSmId]) ? $maxMarks[$sub->esSmId] : '' ?>"
                        value="<?= isset($marks[$stu->smId][$sub->esSmId]) ? $marks[$stu->smId][$sub->esSmId] : '' ?>">
                    <div class="mark-error" style="display:none;"></div>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</tbody>
        </table>

        <div class="table-actions">
            <button id="editBtn" class="btn" type="button">Edit</button>
            <button id="updateBtn" class="btn" type="button" style="display:none;">Update</button>
        </div>
    </div>
</div>

<!-- ================= Scripts (loaded ONCE — remove any duplicates from your layout) ================= -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    var table = $('#marksTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: '_all' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No reports found",
            info: "Showing _START_ to _END_ of _TOTAL_ reports",
            paginate: { previous: "Prev", next: "Next" }
        }
    });

    // ---- EDIT BUTTON (event delegation — survives DataTables redraws) ----
    $(document).on('click', '#editBtn', function () {
        $(".mark-input").prop("readonly", false).addClass("editing");
        $("#editBtn").hide();
        $("#updateBtn").show();
    });

    // ---- UPDATE BUTTON ----
    $(document).on('click', '#updateBtn', function () {

     // Validate all inputs first
    let hasError = false;
    $(".mark-input").each(function () {
        if (!validateMarkInput($(this))) {
            hasError = true;
        }
    });

    if (hasError) {
        Swal.fire("Invalid Marks", "Please fix the highlighted marks before updating.", "warning");
        return; // stop here, don't send AJAX
    }

        let marks = [];

        $(".mark-input").each(function () {
            marks.push({
                student_id: $(this).data("student"),
                subject_id: $(this).data("subject"),
                mark: $(this).val().trim()
            });
        });

        $("#updateBtn").prop("disabled", true).text("Updating...");

        $.ajax({
            url: "<?php echo base_url('updateMarks') ?>",
            type: "POST",
            dataType: "json",
            data: {
                exam: <?= json_encode(isset($exam) ? $exam : null) ?>,
                class: <?= json_encode(isset($class) ? $class : null) ?>,
                division: <?= json_encode(isset($division) ? $division : null) ?>,
                marks: marks
            },
            success: function (res) {
                if (res.status == "success") {
                    $(".mark-input").prop("readonly", true).removeClass("editing");
                    $("#updateBtn").hide().prop("disabled", false).text("Update");
                    $("#editBtn").show();

                    Swal.fire("Success", "Marks Updated", "success");
                } else {
                    Swal.fire("Error", res.message || "Something went wrong", "error");
                    $("#updateBtn").prop("disabled", false).text("Update");
                }
            },
            error: function () {
                Swal.fire("Error", "Server error while updating marks", "error");
                $("#updateBtn").prop("disabled", false).text("Update");
            }
        });
    });

});
</script>



<!-- keyboard -->

<script>
$(document).on('keydown', '.mark-input', function (e) {

    // Only work when inputs are editable
    if ($(this).prop('readonly')) return;

    var $inputs = $('.mark-input:visible');
    var currentIndex = $inputs.index(this);

    // Figure out how many columns (subjects) there are per row
    var colCount = $('#marksTable thead th').length - 3; // minus SL, Admission No, Student Name

    var targetIndex = null;

    switch (e.key) {
        case 'ArrowRight':
            targetIndex = currentIndex + 1;
            break;
        case 'ArrowLeft':
            targetIndex = currentIndex - 1;
            break;
        case 'ArrowDown':
            targetIndex = currentIndex + colCount;
            break;
        case 'ArrowUp':
            targetIndex = currentIndex - colCount;
            break;
        case 'Enter':
            e.preventDefault(); // stop form submit / newline
            targetIndex = currentIndex + 1; // move to next cell like Tab
            break;
        default:
            return; // let all other keys behave normally
    }

    if (targetIndex !== null && targetIndex >= 0 && targetIndex < $inputs.length) {
        e.preventDefault();
        $inputs.eq(targetIndex).focus().select();
    }
});
</script>


<script>
    // ---- MARK VALIDATION (max mark per subject) ----
$(document).on('input', '.mark-input', function () {
    validateMarkInput($(this));
});

function validateMarkInput($input) {
    var val = $input.val().trim();
    var max = parseFloat($input.data('max'));
    var $error = $input.next('.mark-error');

    // Clear previous state
    $input.removeClass('input-error');
    $error.hide().text('');

    if (val === '') return true; // empty is allowed

    var num = parseFloat(val);

    if (isNaN(num) || !/^\d+(\.\d+)?$/.test(val)) {
        $input.addClass('input-error');
        $error.text('Numbers only').show();
        return false;
    }

    if (!isNaN(max) && num > max) {
        $input.addClass('input-error');
        $error.text('Max ' + max).show();
        return false;
    }

    if (num < 0) {
        $input.addClass('input-error');
        $error.text('Invalid').show();
        return false;
    }

    return true;
}
</script>