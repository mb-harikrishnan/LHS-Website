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
    text-decoration: none;
}
.card-action:hover { background: #16204a; color: #fff; }

.card-actions-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.card-action.excel-btn {
    background: #1e9e5e;
}
.card-action.excel-btn:hover {
    background: #167e49;
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
    padding: 4px 6px;
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

#marksTable td.sl-cell {
    font-weight: 600;
    color: #444;
}

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
    width:55px;
    height:26px;
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

.exam-info {
    display: flex;
    gap: 30px;
    padding: 15px 20px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

.exam-info strong {
    color: var(--green);
    margin-right: 5px;
}
</style>

<!-- Reports Table Card -->
<div class="card">

    <div class="exam-info">
        <div>
            <strong>Exam:</strong>
            <?= isset($exam) ? (is_object($exam) ? $exam->emName : $exam) : '-' ?>
        </div>

        <div>
            <strong>Class:</strong>
            <?= isset($class) ? (is_object($class) ? $class->cmName : $class) : '-' ?>
        </div>

        <div>
            <strong>Division:</strong>
            <?= isset($division) ? (is_object($division) ? $division->dmName : $division) : '-' ?>
        </div>
    </div>

    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Mark List
        </div>

        <div class="card-actions-group">
            <a class="card-action excel-btn"
               href="<?php echo base_url('export_marks_excel/'.$examId.'/'.$classId.'/'.$divisionId); ?>">
                <i class="fa fa-file-excel-o"></i> Export to Excel
            </a>

            <button class="card-action" onclick="window.location.href='<?php echo base_url('Marksentry_list'); ?>'">
                <i class="fa fa-upload"></i> Mark List
            </button>
        </div>
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
                            <?= htmlspecialchars($sub->smName) ?>
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
                        <td class="admission-cell"><?= htmlspecialchars($stu->smAdmissionNo) ?></td>
                        <td class="student-name-cell"><?= htmlspecialchars($stu->smName) ?></td>
                        <?php foreach ($subjects as $sub) { ?>
                            <td>
                                <input
                                    type="text"
                                    class="mark-input"
                                    readonly
                                    data-student="<?= $stu->smId ?>"
                                    data-subject="<?= $sub->esSmId ?>"
                                    data-grade="<?= $isGrade ?>"
                                    data-max="<?= isset($maxMarks[$sub->esSmId]) ? $maxMarks[$sub->esSmId] : '' ?>"
                                    value="<?= isset($marks[$stu->smId][$sub->esSmId]) ? $marks[$stu->smId][$sub->esSmId] : '' ?>">
                                <div class="mark-error" style="display:none;"></div>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-actions">
        <button id="editBtn" type="button"><i class="fa fa-pencil"></i> Edit Marks</button>
        <button id="updateBtn" type="button" style="display:none;"><i class="fa fa-save"></i> Update Marks</button>
    </div>
</div>

<!-- ================= Scripts ================= -->

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

    $('#marksTable').DataTable({
        responsive: true,
        paging: true,
        pageLength: 25,
        autoWidth: false,
        ordering: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search students...",
            lengthMenu: "Show _MENU_ entries",
        }
    });

    var examId     = "<?= $examId ?>";
    var classId    = "<?= $classId ?>";
    var divisionId = "<?= $divisionId ?>";

    // ---- Toggle edit mode ----
    $('#editBtn').on('click', function () {
        $('.mark-input').prop('readonly', false).addClass('editing');
        $('#editBtn').hide();
        $('#updateBtn').show();
    });

    // ---- Validate + Update ----
    $('#updateBtn').on('click', function () {
        var hasError = false;
        var payload = [];

        $('.mark-input').each(function () {
            var $input = $(this);
            var $errorBox = $input.siblings('.mark-error');
            var val = $.trim($input.val());
            var max = parseFloat($input.data('max'));
            var isGrade = $input.data('grade') == 1;

            $input.removeClass('input-error');
            $errorBox.hide().text('');

            if (val === '') {
                hasError = true;
                $input.addClass('input-error');
                $errorBox.text('Required').show();
                return;
            }

            if (!isGrade) {
                var num = parseFloat(val);
                if (isNaN(num) || num < 0 || (max && num > max)) {
                    hasError = true;
                    $input.addClass('input-error');
                    $errorBox.text('Invalid').show();
                    return;
                }
            }

            payload.push({
                student_id: $input.data('student'),
                subject_id: $input.data('subject'),
                mark: val
            });
        });

        if (hasError) {
            Swal.fire({
                icon: 'warning',
                title: 'Please fix the highlighted marks',
                text: 'Enter a valid mark for every student and subject before updating.',
                confirmButtonColor: '#1e2a5e'
            });
            return;
        }

        $('#updateBtn').prop('disabled', true).text('Updating...');

        $.ajax({
            url: '<?php echo base_url("update_marks_all"); ?>',
            method: 'POST',
            data: {
                exam_id: examId,
                class_id: classId,
                division_id: divisionId,
                marks: payload
            },
            dataType: 'json',
            success: function (res) {
                $('#updateBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Update Marks');
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Marks Updated',
                        confirmButtonColor: '#1e9e5e'
                    }).then(function () {
                        $('.mark-input').prop('readonly', true).removeClass('editing');
                        $('#updateBtn').hide();
                        $('#editBtn').show();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: res.message || 'Please try again.'
                    });
                }
            },
            error: function () {
                $('#updateBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Update Marks');
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Unable to update marks. Please try again.'
                });
            }
        });
    });

});
</script>