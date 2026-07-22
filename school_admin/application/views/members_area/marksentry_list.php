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
            Mark List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
        </div>
        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_mark_entry'); ?>'">
            <i class="fa fa-upload"></i> Add Mark
        </button>
    </div>


    <div class="report-table-wrap" id="reportTableWrap">
        <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

            <thead>
                <tr>
                    <th>#SL</th>
                        <th>exam </th>
                        <th>class</th>
                        <th>divition </th>
                   
                    <th>menu </th>
                </tr>
            </thead>

           <tbody>

<?php
$i=1;
foreach($details as $row){
?>

<tr>

<td><?= $i++; ?></td>

<td><?= $row->emName; ?></td>

<td><?= $row->cmName; ?></td>

<td><?= $row->dmName; ?></td>

<td>

<a class="btn btn-primary btn-sm"
href="<?= base_url('view_marks_students/'.$row->esEmId.'/'.$row->esCmId.'/'.$row->esDmId);?>">

View

</a>

</td>

</tr>

<?php } ?>

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