    <?php
    $pageTitle = 'Reports';
    $breadcrumb = 'Reports';
    $activePage = 'reports';
    $showGlobalSearch = false;
    ?>

    <!-- Report CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    <!-- ==========================================================
        REPORT CARD
    ========================================================== -->
    <div class="card">

        <div class="card-head">
            <div class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Mark Allocation  List
            </div>

            <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_subject'); ?>'">
                <i class="fa fa-plus"></i> Add Mark
            </button>
        </div>

        <div class="report-table-wrap">
            <table id="reportsDataTable"
                class="report-table display nowrap"
                style="width:100%">

                <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Class</th>
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Edit</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

             <tbody>
    <?php $count = 1; ?>

    <?php foreach ($details as $row) { ?>

        <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $row->cmName; ?></td>
            <td><?php echo $row->emDisplayName; ?></td>
            <td><?php echo $row->subjectsWithMarks; ?></td>
            <td>
                    <a href="<?php echo base_url('edit_allocation/'.$row->emdEmId.'/'.$row->emdCmId); ?>"
                        class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
            </td>

            <td>
                <button class="deleteBtn"
                        data-emid="<?php echo (int)$row->emdEmId; ?>"
                        data-cmid="<?php echo (int)$row->emdCmId; ?>">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </td>
        </tr>

    <?php } ?>
</tbody>
            </table>
        </div>

    </div>


    <!-- ==========================================================
        SCRIPTS
    ========================================================== -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
    $(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();

    let emId = $(this).data('emid');
    let cmId = $(this).data('cmid');
    let row  = $(this).closest('tr');

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this exam.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then(function (result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url: "<?php echo base_url('delete_allocation_list'); ?>",
            type: "POST",
            data: { emId: emId, cmId: cmId },
            success: function (response) {
                if ($.trim(response) == "1") {
                    row.fadeOut(300, function () { $(this).remove(); });
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Deleted Successfully', showConfirmButton: false, timer: 2000
                    });
                } else {
                    Swal.fire('Failed', 'Unable to delete record.', 'error');
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire('Error', 'Server Error', 'error');
            }
        });
    });
});
   </script>