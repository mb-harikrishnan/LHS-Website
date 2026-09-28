<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>

<!-- Report Page Styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/report.css'); ?>">

<!-- Reports Table Card -->
<div class="card">
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Academic List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_academic'); ?>'">
            <i class="fa fa-upload"></i> Add Academic
        </button>
    </div>


    <div class="report-table-wrap" id="reportTableWrap">
        <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

            <thead>
                <tr>
                    <th>#SL</th>
                        <th>Year</th>
                   
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 1;

                foreach ($academic as $row) {

                  
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row->amYear; ?></td>
                 
                    <td>
                        <button class="deleteBtn" data-id="<?php echo $row->amId; ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                <?php
                $count++;
                }
                ?>
            </tbody>

        </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<!-- Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<!-- Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    $('#reportsDataTable').DataTable({

        responsive: true,
        autoWidth: false,
        pageLength: 10,

        order: [[0, 'desc']],

        columnDefs: [
            {
                orderable: false,
                // targets: [3, 4]
            }
        ],

        language: {
            search: "",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No reports found",
            info: "Showing _START_ to _END_ of _TOTAL_ reports",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        }

    });

});
</script>

<script>
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

        if (result.isConfirmed) {

            $.ajax({

                url: "<?php echo base_url('delete_accademic'); ?>",
                type: "POST",
                data: { id: id },

                success: function (response) {

                    if ($.trim(response) == '1') {
                        row.fadeOut(500, function () {
                            $(this).remove();
                        });

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Deleted Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed'
                        });
                    }

                },

                error: function (xhr) {
                    console.log(xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error'
                    });
                }

            });

        }

    });

});
</script>
