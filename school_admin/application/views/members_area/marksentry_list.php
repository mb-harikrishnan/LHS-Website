<?php
$pageTitle    = 'Reports';
$breadcrumb   = 'Reports';
$activePage   = 'reports';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<style>
.filter-form {
    padding: 20px;
}
.filter-row {
    display: flex;
    align-items: center;
    margin-bottom: 14px;
}
.filter-row label {
    width: 90px;
    font-weight: 600;
    color: #1a1a4d;
    font-size: 15px;
}
.filter-row select {
    flex: 0 0 220px;
    padding: 6px 10px;
    border: 1px solid #999;
    border-radius: 4px;
    font-size: 14px;
    background: #fff;
}
.filter-actions {
    display: flex;
    align-items: center;
    margin-top: 10px;
}
.btn-filter {
    background-color: #2b2fa0;
    color: #fff;
    border: none;
    padding: 10px 28px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
}
.btn-filter:hover {
    background-color: #22267f;
}
.btn-reset {
    margin-left: 20px;
    color: #6c2bd9;
    text-decoration: underline;
    font-weight: 600;
    font-size: 15px;
}
.btn-reset:hover {
    color: #4b1c99;
}

.no-data-msg {
    text-align: center;
    padding: 30px 20px;
    color: #6b7280;
    font-size: 15px;
    font-weight: 500;
}
</style>

<!-- ============================================================
     Filter Form
============================================================= -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-head">
        <div class="card-title">Filter Report</div>
    </div>
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="<?php echo base_url('Marksentry_list'); ?>" class="filter-form">

        <div class="filter-row">
            <label>Class</label>
            <select name="class_id" class="form-control">
                <option value="">-- Select --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?php echo $c->cmId; ?>"
                        <?php echo ($selectedClass == $c->cmId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c->cmName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-row">
            <label>Division</label>
            <select name="div_id" class="form-control">
                <option value="">-- Select --</option>
                <?php foreach ($divisions as $d): ?>
                    <option value="<?php echo $d->dmId; ?>"
                        <?php echo ($selectedDiv == $d->dmId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($d->dmName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-row">
            <label>Exam</label>
            <select name="exam_id" class="form-control">
                <option value="">-- Select --</option>
                <?php foreach ($exams as $ex): ?>
                    <option value="<?php echo $ex->emId; ?>"
                        <?php echo ($selectedExam == $ex->emId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ex->emName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter">Filter</button>
        </div>

    </form>
</div>


<!-- ============================================================
     Report Table
============================================================= -->
<!-- ============================================================
     Report Table
============================================================= -->
<div class="card">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Mark Entry List
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_mark_entry'); ?>'">
            <i class="fa fa-upload"></i> Add Mark
        </button>
    </div>

    <?php if (!empty($pivot)): ?>

        <table id="reportsDataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Division</th>
                    <?php foreach ($subjectList as $subj): ?>
                        <th><?php echo htmlspecialchars($subj); ?></th>
                    <?php endforeach; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($pivot as $studentId => $stud): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($stud['name']); ?></td>
                        <td><?php echo htmlspecialchars($stud['class']); ?></td>
                        <td><?php echo htmlspecialchars($stud['division']); ?></td>
                        <?php foreach ($subjectList as $subj): ?>
                            <td><?php echo isset($stud['marks'][$subj]) ? htmlspecialchars($stud['marks'][$subj]) : '-'; ?></td>
                        <?php endforeach; ?>
              <td>
                <a href="<?php echo base_url('edit_marks/'.$studentId.'/'.$stud['examId']); ?>"
                   class="btn btn-sm btn-primary">Edit</a>
            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="no-data-msg">
            No data found
        </div>

    <?php endif; ?>
</div>
<!-- ============================================================
     Vendor Scripts
============================================================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
   if ($('#reportsDataTable').length) {
        $('#reportsDataTable').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search reports...",
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No reports found",
                info: "Showing _START_ to _END_ of _TOTAL_ reports",
                paginate: { previous: "Prev", next: "Next" }
            }
        });
    }

    $(document).on('click', '.deleteBtn', function (e) {
        e.preventDefault();
        var id  = $(this).data('id');
        var row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: "<?php echo base_url('delete_students'); ?>",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    if ($.trim(response) == '1') {
                        row.fadeOut(500, function () { $(this).remove(); });
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: 'Deleted Successfully', showConfirmButton: false, timer: 2000
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Delete Failed' });
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Server Error' });
                }
            });
        });
    });
});
</script>