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
        <table class="report-table display nowrap" id="reportsDataTable" >

            <thead>
                <tr>
                    <th>#SL</th>
                        <th>exam </th>
                        <th>class</th>
                        <th>divition </th>
                        <th>Edit </th>
                        <th>Menu </th>
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

Edit

</a>

</td>

<td>
    <button type="button" class="btn btn-primary btn-sm final-submit-btn"
            data-em="<?= $row->esEmId; ?>"
            data-cm="<?= $row->esCmId; ?>"
            data-dm="<?= $row->esDmId; ?>"
            onclick="checkAndFinalSubmit(this)">
        Final Submit
    </button>
</td>

</tr>

<?php } ?>

</tbody>

        </table>
    </div>
</div>


<style>

/* ================================
   REPORT TABLE - CLEAN DESIGN
================================ */

.report-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 0 0 16px 16px;
}

/* Remove unwanted DataTables borders */
#reportsDataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: none !important;
}

/* Table header */
#reportsDataTable thead th {
    background: #203d8f !important;
    color: #fff !important;
    font-weight: 600;
    padding: 14px 14px !important;
    border: none !important;
    white-space: nowrap;
}

/* Table body */
#reportsDataTable tbody td {
    padding: 12px 14px !important;
    border: none !important;
    border-bottom: 1px solid #e5e7eb !important;
    color: #374151;
}

/* Remove border from last row */
#reportsDataTable tbody tr:last-child td {
    border-bottom: none !important;
}

/* Row hover */
#reportsDataTable tbody tr:hover {
    background: #f8faff !important;
}

/* Zebra rows - very light */
#reportsDataTable tbody tr:nth-child(even) {
    background: #fafbfc;
}

/* View button */
#reportsDataTable .btn-primary {
    background: #203d8f !important;
    border: none !important;
    color: #fff !important;
    padding: 9px 22px !important;
    border-radius: 12px !important;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

#reportsDataTable .btn-primary:hover {
    background: #162f73 !important;
}

/* ================================
   DATATABLE TOP CONTROLS
================================ */

.dataTables_wrapper .dataTables_length {
    margin-bottom: 20px !important;
    color: #203d8f;
    font-weight: 500;
}

.dataTables_wrapper .dataTables_length select {
    min-width: 80px;
    padding: 8px 28px 8px 12px !important;
    border: 1px solid #cfd8ea !important;
    border-radius: 12px !important;
    background: #fff;
    outline: none;
}

/* Search box */
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 20px !important;
}

.dataTables_wrapper .dataTables_filter input {
    width: 325px !important;
    height: 55px !important;
    padding: 0 18px !important;
    border: 1px solid #d7e0ef !important;
    border-radius: 16px !important;
    background: #f8fbff !important;
    outline: none !important;
    font-size: 15px;
    margin-left: 0 !important;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #203d8f !important;
    box-shadow: 0 0 0 3px rgba(32, 61, 143, 0.08);
}

/* ================================
   BOTTOM INFO + PAGINATION
================================ */

.dataTables_wrapper .dataTables_info {
    padding-top: 18px !important;
    color: #203d8f !important;
    font-size: 14px;
}

/* Pagination container */
.dataTables_wrapper .dataTables_paginate {
    padding-top: 12px !important;
    display: flex !important;
    justify-content: flex-end;
    align-items: center;
    gap: 8px !important;
}

/* All pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    min-width: 42px !important;
    height: 42px !important;
    padding: 10px 14px !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 12px !important;
    background: #eef4fc !important;
    color: #7b8494 !important;
    font-weight: 600;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}

/* Hover */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #dce7fa !important;
    color: #203d8f !important;
    border: none !important;
}

/* Active page */
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #203d8f !important;
    color: #fff !important;
    border: none !important;
}

/* Disabled Previous / Next */
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    background: #f0f4fa !important;
    color: #b8bec8 !important;
    cursor: not-allowed;
    border: none !important;
}

/* ================================
   MOBILE
================================ */

@media (max-width: 768px) {

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none !important;
        text-align: left !important;
        margin-bottom: 12px !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
        max-width: 100% !important;
    }

    .dataTables_wrapper .dataTables_info {
        float: none !important;
        text-align: left !important;
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: none !important;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

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
    pageLength: 10,
    autoWidth: false,

    columnDefs: [
        { orderable: false, targets: [2, 3] } // Edit and Action
    ],

    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search reports...",
        lengthMenu: "Show _MENU_ entries",
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


<script>
    function checkAndFinalSubmit(btn) {
    const emId = btn.dataset.em;
    const cmId = btn.dataset.cm;
    const dmId = btn.dataset.dm;

    Swal.fire({
        title: 'Checking...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: '<?php echo base_url("check_marks_status"); ?>/' + emId + '/' + cmId + '/' + dmId,
        method: 'GET',
        dataType: 'json',
        success: function (res) {
            Swal.close();
            if (res.complete === true) {
                window.location.href = '<?php echo base_url("view_marks_all"); ?>/' + emId + '/' + cmId + '/' + dmId;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Marks Incomplete',
                    html: 'Please enter marks for <b>all students</b> before final submission.' +
                          (res.missing ? '<br><br>Missing: ' + res.missing + ' student(s)' : ''),
                    confirmButtonColor: '#203d8f'
                });
            }
        },
        error: function () {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to check mark status. Please try again.'
            });
        }
    });
}
</script>