<?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;
?>



<!-- CARD -->
<div class="card">

    <!-- CARD HEADER -->
    <div class="card-head">
        <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="var(--green)" stroke-width="2" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>

            Homepage Slider List
        </div>

        <button class="card-action"
                onclick="window.location.href='<?php echo base_url('add_slider'); ?>'">
            <i class="fa fa-upload"></i> Add Video
        </button>
    </div>

    <!-- FILTER -->
    

    <p class="report-meta">
        Showing all reports
    </p>

    <!-- TABLE -->
    <div class="report-table-wrap">

        <table id="reportsDataTable"
               class="report-table display nowrap"
               style="width:100%">

            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Type</th>
                <th>Video</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>

            <?php if(!empty($image)) { ?>

                <?php $i = 1; foreach($image as $row) { ?>

                    <tr>

                        <!-- SL NO -->
                        <td>
                            <?php echo $i++; ?>
                        </td>

                        <!-- DATE -->
                        <td>
                            <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
                        </td>

                        <!-- TYPE -->
                        <td>
                            <span class="report-type-badge">
                                <?php echo ucfirst(str_replace('_', ' ', $row->c_title)); ?>
                            </span>
                        </td>

                        <td><?php if($row->c_upload_type == 'image') { ?>

                    <img src="<?php echo base_url('../assets/images/gallery/'.$row->c_file); ?>" 
                         alt="Image"
                         style="width:120px; height:auto; border-radius:8px;">

                <?php } elseif($row->c_upload_type == 'video') { ?>

                    <video width="220" height="120" controls>
                        <source src="<?php echo base_url('../assets/images/gallery/'.$row->c_file); ?>" type="video/webm">
                        Your browser does not support the video tag.
                    </video>

                <?php } elseif($row->c_upload_type == 'link') { ?>

                    <iframe 
                        width="220" 
                        height="120"
                        src="<?php echo $row->c_file; ?>" 
                        frameborder="0"
                        allowfullscreen>
                    </iframe>

                <?php } ?>

            </td>

                    
                        <!-- ACTION -->
                        <td>

                            <button type="button"
                                    class="table-btn delete-btn deleteBtn"
                                    data-id="<?php echo $row->n_slno; ?>">

                                <i class="fa fa-trash"></i> Delete

                            </button>

                        </td>

                    </tr>

                <?php } ?>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<!-- CSS -->



<style>

.card{
    background:#ffffff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 4px 20px rgba(0,0,0,0.06);
    border:1px solid #e8ecef;
    overflow:hidden;
}

/* =========================
   FILTER AREA
========================= */
/* CARD HEADER SMALL */

.card-head{
    padding:10px 0 !important;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
}
/* FILTER BAR SMALL */

.filter-bar{
    display:flex;
    justify-content:space-between;
    align-items:end;
    gap:12px;
    margin-top:10px;
    flex-wrap:wrap;
    background:#f8faf9;
    padding:12px 15px;
    border-radius:10px;
}

.filter-group label{
    display:block;
    font-size:12px;
    font-weight:600;
    color:#0f2419;
    margin-bottom:5px;
}

.form-select{
    min-width:220px;
    height:40px;
    border:1px solid #d7e0db;
    border-radius:10px;
    padding:0 14px;
    font-size:13px;
    background:#fff;
    transition:0.3s;
}

.form-select:focus{
    border-color:#0f2419;
    outline:none;
    box-shadow:0 0 0 3px rgba(15,36,25,0.10);
}

/* =========================
   BUTTONS
========================= */

.btn-primary{
    background:#416e54;
    border:none;
    color:#fff;
    padding:10px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:0.3s;
}

.btn-primary:hover{
    background:#18382a;
    transform:translateY(-1px);
}

/* SMALL TITLE */

.card-title{
    font-size:16px;
    font-weight:700;
}


.card-action{
   background:#5e8b72;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.card-action:hover{
    background:#416e54;
}

/* =========================
   TABLE DESIGN
========================= */
/* TABLE HEADER GREEN */

.report-table thead tr,
.report-table thead th{
    background:#416e54 !important;
    color:#ffffff !important;
}

/* HEADER TEXT STYLE */

.report-table thead th{
    padding:18px 16px;
    font-size:15px;
    font-weight:700;
    text-transform:uppercase;
    border:none !important;
    letter-spacing:0.5px;
}

/* ROUNDED CORNERS */

.report-table thead th:first-child{
    border-top-left-radius:14px;
}

.report-table thead th:last-child{
    border-top-right-radius:14px;
}

/* DATATABLE SORT ICON COLOR */

table.dataTable thead .sorting:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc:after{
    color:#ffffff !important;
    opacity:1 !important;
}

/* BODY */

.report-table tbody td{
    padding:18px 16px;
    font-size:14px;
    color:#374151;
    border-bottom:1px solid #edf2ef;
    vertical-align:middle;
    background:#fff;
}

.report-table tbody tr{
    transition:0.25s;
}

.report-table tbody tr:hover td{
    background:#f5faf7;
}

/* =========================
   BADGES
========================= */

.report-type-badge{
    background:#e7f5ed;
    color:#0f7a43;
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
    text-transform:capitalize;
}

/* =========================
   PDF BUTTON
========================= */

.pdf-btn{
    background:#fff1f2;
    color:#dc2626;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:0.3s;
}

.pdf-btn:hover{
    background:#dc2626;
    color:#fff;
}

/* =========================
   ACTION BUTTONS
========================= */

.action-btn-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.table-btn{
    border:none;
    padding:10px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:0.3s;
    cursor:pointer;
}

.download-btn{
    background:#5e8b72;
    color:#fff;
}

.download-btn:hover{
    background:#416e54;
}

.deleteBtn{
    background:#dc2626 !important;
    color:#fff !important;
}

.deleteBtn:hover{
    background:#b91c1c !important;
}

/* =========================
   DATATABLE DESIGN
========================= */

/* PAGINATION DESIGN */

.dataTables_wrapper .dataTables_paginate{
    margin-top:15px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{
    background:#e8f3ec !important;
    color:#416e54 !important;
    border:none !important;
    border-radius:10px !important;
    padding:7px 14px !important;
    margin:0 4px !important;
    font-weight:600;
    transition:0.3s;
}

/* ACTIVE PAGE */

.dataTables_wrapper .dataTables_paginate .paginate_button.current{
    background:#416e54 !important;
    color:#fff !important;
    border:none !important;
}

/* HOVER */

.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
    background:#5d8a70 !important;
    color:#fff !important;
}

/* REMOVE BLACK BORDER */

.dataTables_wrapper .dataTables_paginate .paginate_button:focus{
    outline:none !important;
    box-shadow:none !important;
}


/* TOP DATATABLE AREA */

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter{
    margin-bottom:18px;
}

/* SHOW ENTRIES TEXT */

.dataTables_wrapper .dataTables_length label{
    font-size:15px;
    font-weight:600;
    color:#416e54;
}

/* DROPDOWN */

.dataTables_wrapper .dataTables_length select{
    border:1px solid #cfe0d7 !important;
    border-radius:10px !important;
    padding:8px 35px 8px 12px !important;
    height:42px;
    background:#f7fbf8 !important;
    color:#416e54 !important;
    font-weight:600;
    outline:none !important;
    box-shadow:none !important;
}

/* SEARCH LABEL */

.dataTables_wrapper .dataTables_filter label{
    font-size:15px;
    font-weight:600;
    color:#416e54;
}

/* SEARCH INPUT */

.dataTables_wrapper .dataTables_filter input{
    width:260px !important;
    height:44px !important;
    border:1px solid #cfe0d7 !important;
    border-radius:12px !important;
    padding:0 15px !important;
    background:#f7fbf8 !important;
    color:#333 !important;
    font-size:14px !important;
    outline:none !important;
    box-shadow:none !important;
    transition:0.3s;
}

/* SEARCH FOCUS */

.dataTables_wrapper .dataTables_filter input:focus{
    border-color:#416e54 !important;
    background:#fff !important;
    box-shadow:0 0 0 3px rgba(65,110,84,0.10) !important;
}

/* MOBILE */

@media(max-width:768px){

    .dataTables_wrapper .dataTables_filter{
        margin-top:15px;
        text-align:left !important;
    }

    .dataTables_wrapper .dataTables_filter input{
        width:100% !important;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter{
        width:100%;
    }
}

/* =========================
   EMPTY TEXT
========================= */

.no-file{
    color:#9ca3af;
    font-size:13px;
    font-weight:500;
}

/* =========================
   MOBILE
========================= */

@media(max-width:768px){

    .card{
        padding:16px;
    }

    .filter-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .form-select{
        width:100%;
        min-width:100%;
    }

    .btn-primary,
    .card-action{
        width:100%;
        justify-content:center;
    }

    .report-table thead th,
    .report-table tbody td{
        padding:14px 12px;
        font-size:13px;
    }

    .table-btn{
        width:100%;
        justify-content:center;
    }

    .action-btn-group{
        flex-direction:column;
    }
}

</style>

<style>
.table-video{
    width:250px;
    height:140px;
    border-radius:10px;
    border:none;
     pointer-events:none;

}
.report-table-wrap{
    width:100%;
    overflow-x:auto;
    margin-top:20px;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}

.report-table thead tr{
    background:#1f4e3d;
}

.report-table thead th{
    color:#fff;
    padding:14px;
    font-size:14px;
    font-weight:600;
    text-align:left;
    border:none;
}

.report-table tbody td{
    padding:14px;
    border-bottom:1px solid #e5e5e5;
    font-size:14px;
    color:#444;
    vertical-align:middle;
}

.report-table tbody tr:hover{
    background:#f8fbf9;
    transition:0.3s;
}

.report-type-badge{
    background:#e7f7ef;
    color:#198754;
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
    text-transform:capitalize;
}

.table-video{
    width:180px;
    height:100px;
    border-radius:8px;
    object-fit:cover;
    border:1px solid #ddd;
}

.table-btn{
    border:none;
    padding:8px 14px;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.delete-btn{
    background:#dc3545;
    color:#fff;
}

.delete-btn:hover{
    background:#bb2d3b;
}

.no-file{
    color:#999;
    font-size:13px;
}

.report-meta{
    padding:12px 24px 0;
    color:#666;
}

.filter-bar{
    display:flex;
    align-items:end;
    justify-content:space-between;
    gap:15px;
    padding:20px 24px 0;
    flex-wrap:wrap;
}

.filter-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.form-select{
    padding:10px 12px;
    min-width:220px;
    border:1px solid #ddd;
    border-radius:8px;
}

</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DATATABLE CSS -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<link rel="stylesheet"
      href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- DATATABLE JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DATATABLE -->
<script>

$(document).ready(function () {

    $('#reportsDataTable').DataTable({
        responsive: true,
        pageLength: 10,
        autoWidth: false,

        columnDefs: [
            {
                orderable: false,
                targets: [3, 4]
            }
        ],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search reports...",
            lengthMenu: "Show _MENU_ entries"
        }
    });

});

</script>

<!-- DELETE -->
<script>

$(document).on('click', '.deleteBtn', function (e) {

    e.preventDefault();

    let id  = $(this).data('id');
    let row = $(this).closest('tr');

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    })

    .then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: "<?php echo base_url('delete_slider'); ?>",
                type: "POST",
                data: {id:id},

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




