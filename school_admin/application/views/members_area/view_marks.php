<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<!-- Report Page Styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<style>
/* ---------- Mark List Card ---------- */
.card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 24px 28px;
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
.card-badge {
    background: #eef1f9;
    color: #1e2a5e;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    margin-left: 8px;
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

.report-table-wrap {
    overflow-x: auto;
}

#marksTable {
    width: 100% !important;
}
#marksTable thead th {
    background: #f6f8fc;
    color: #1e2a5e;
    font-size: 12.5px;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 12px 10px;
    border-bottom: 2px solid #e2e6f0 !important;
    white-space: nowrap;
}
#marksTable tbody td {
    padding: 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f7;
    font-size: 14px;
    color: #333;
}
#marksTable tbody tr:hover {
    background: #f9fafc;
}
#marksTable tbody td:first-child {
    font-weight: 600;
    color: #1e2a5e;
}

.mark-input {
    width: 68px;
    padding: 7px 8px;
    border: 1px solid #d3d8e4;
    border-radius: 8px;
    text-align: center;
    font-size: 14px;
    background: #f4f5f8;
    color: #888;
    transition: all .15s ease;
}
.mark-input.editing {
    background: #fff;
    color: #1e2a5e;
    border-color: #1e2a5e;
    box-shadow: 0 0 0 3px rgba(30,42,94,.12);
    cursor: text;
}
.mark-input:focus {
    outline: none;
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
</style>

<!-- Reports Table Card -->
<div class="card">

    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Mark List
            <span class="card-badge" id="tableBadge"><?= count($students) ?> records</span>
        </div>

        <button class="card-action" onclick="window.location.href='<?php echo base_url('Marksentry_list'); ?>'">
            <i class="fa fa-upload"></i>  Mark List
        </button>
    </div>

    <div class="report-table-wrap" id="reportsDataTable">
        <table class="table" id="marksTable">
            <thead>
                <tr>
                    <th>Admission No</th>
                    <th>Student</th>
                    <?php foreach ($subjects as $sub) { ?>
                        <th><?= $sub->smName ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $stu) { ?>
                    <tr>
                        <td><?= $stu->smAdmissionNo ?></td>
                        <td><?= $stu->smName ?></td>
                        <?php foreach ($subjects as $sub) { ?>
                            <td>
                                <input
    type="text"
    class="mark-input"
    readonly
    data-student="<?= $stu->smId ?>"
    data-subject="<?= $sub->esSmId ?>"
    value="<?= isset($marks[$stu->smId][$sub->esSmId]) ? $marks[$stu->smId][$sub->esSmId] : '' ?>">
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
        order: [[0, 'desc']],
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