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
            Role List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_user_role'); ?>'">
            <i class="fa fa-upload"></i> Add Role
        </button>
    </div>


    <div class="report-table-wrap" id="reportTableWrap">
        <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

            <thead>
                <tr>
                    <th>#SL</th>
                       
                        <th>Name</th>
                   
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 1;

                foreach ($role as $row) {

                  
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row->role_name; ?></td>
                 
                   <td>
                    <?php if($row->status == 1){ ?>
                        <button class="btn btn-success toggleStatusBtn"
                                data-id="<?php echo $row->role_id; ?>"
                                data-status="1">
                            Enable
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-danger toggleStatusBtn"
                                data-id="<?php echo $row->role_id; ?>"
                                data-status="0">
                            Disable
                        </button>
                    <?php } ?>
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


<style>
.toggleStatusBtn{
    border: none;
    color: #fff;
    padding: 6px 14px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
}

.btn-success{
    background: #16a34a;
}

.btn-danger{
    background: #dc2626;
}

.btn-success:hover{
    background: #15803d;
}

.btn-danger:hover{
    background: #b91c1c;
}
</style>

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
  $(document).on("click", ".toggleStatusBtn", function () {

    var btn = $(this);
    var id = btn.data("id");
    var status = btn.data("status");

    var newStatus = (status == 1) ? 0 : 1;

    $.ajax({
        url: "<?php echo base_url('update_role_status'); ?>",
        type: "POST",
        data: {
            id: id,
            status: newStatus
        },
        success: function (res) {

            if (res == 1) {

                if (newStatus == 1) {

                    btn.removeClass("btn-danger")
                       .addClass("btn-success")
                       .html('<i class="fa fa-check"></i> Enable')
                       .data("status", 1);

                } else {

                    btn.removeClass("btn-success")
                       .addClass("btn-danger")
                       .html('<i class="fa fa-times"></i> Disable')
                       .data("status", 0);

                }

            } else {

                Swal.fire("Error", "Status update failed.", "error");

            }

        }
    });

});
</script>