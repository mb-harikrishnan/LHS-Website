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
            Employee List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_employee'); ?>'">
            <i class="fa fa-upload"></i> Add Employee
        </button>
    </div>


    <div class="report-table-wrap" id="reportTableWrap">
        <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Password</th>
                    <th>Mobile</th>
                    <th>Designation</th>
                    <th>Class</th>
                    <th>Division</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 1;

                foreach ($details as $row) {

                    // NOTE: consider using $this->db->get_where('class_master', ['cmId' => $row->emClass])
                    // and query bindings ($this->db->query($sql, [$param])) instead of string
                    // concatenation to avoid SQL injection risk.
                    $emClass    = $row->emClass;
                    $select     = "SELECT cmName FROM class_master WHERE cmId = '$emClass'";
                    $classResult = $this->db->query($select);
                    $className  = $classResult->row()->cmName ?? '';

                    $emDiv      = $row->emDiv;
                    $selectDiv  = "SELECT dmName FROM division_master WHERE dmId = '$emDiv'";
                    $divResult  = $this->db->query($selectDiv);
                    $divName    = $divResult->row()->dmName ?? '';
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row->emTS)); ?></td>
                    <td><?php echo $row->emName; ?></td>
                    <td><?php echo $row->emPassword; ?></td>
                    <td><?php echo $row->emPhoneNo; ?></td>
                    <td><?php echo $row->emDesigId; ?></td>
                    <td><?php echo $className; ?></td>
                    <td><?php echo $divName; ?></td>
                    <td>
                        <button class="deleteBtn" data-id="<?php echo $row->emId; ?>">
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
                targets: [3, 4]
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

                url: "<?php echo base_url('delete_employee'); ?>",
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

<script>
function openModal(id) {
    document.getElementById('modal_' + id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById('modal_' + id).classList.remove('active');
    document.body.style.overflow = 'auto';
}

/* CLOSE WHEN CLICK OUTSIDE */
window.onclick = function (event) {
    let modals = document.getElementsByClassName('custom-modal');

    for (let i = 0; i < modals.length; i++) {
        if (event.target === modals[i]) {
            modals[i].classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
}
</script>