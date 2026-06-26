    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>


    

     
      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Vacancy List
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('add_vacancy'); ?>'">
          <i class="fa fa-upload"></i> Add Document
          </button>
        </div>

        <!-- Date Filter -->
         <form method="post" action="<?php echo base_url('vaccancy_list'); ?>">

    <div class="date-filter-box">

        <div class="filter-item">
            <label>FROM DATE</label>

            <div class="date-input-box">
                <i class="fa fa-calendar"></i>

                <input type="date"
                       id="fromDate"
                       name="fromDate"
                       value="<?php echo !empty(set_value('fromDate')) ? set_value('fromDate') : date('Y-m-d'); ?>"
                       class="filter-input">
            </div>
        </div>

        <div class="filter-item">
            <label>TO DATE</label>

            <div class="date-input-box">
                <i class="fa fa-calendar"></i>

                <input type="date"
                       id="toDate"
                       name="toDate"
                       value="<?php echo !empty(set_value('toDate')) ? set_value('toDate') : date('Y-m-d'); ?>"
                       class="filter-input">
            </div>
        </div>

        <div class="filter-btn-box">
            <button type="submit" class="btn-filter">
                <i class="fa fa-filter"></i> Filter
            </button>
        </div>

    </div>

</form>
        <p class="report-meta" style="padding:12px 24px 0" id="filterMeta">Showing all reports</p>

       <div class="report-table-wrap" id="reportTableWrap">

    <table class="report-table display nowrap" id="reportsDataTable" style="width:100%">

        <thead>
            <tr>
                <th>#SL</th>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php 
            $count = 1;

            foreach ($vacancy as $row){ 
            ?>

            <tr>

                <td><?php echo $count; ?></td>

                <td>
                    <?php echo date('d-m-Y', strtotime($row->d_date)); ?>
                </td>

                <td>
                    <?php echo $row->c_title; ?>
                </td>

                <td>
                    <button type="button"
                            class="view-btn"
                            onclick="openModal('<?php echo $row->n_slno; ?>')">
                        View
                    </button>
                </td>

                <td>
                    <button class="deleteBtn"
                            data-id="<?php echo $row->n_slno; ?>">
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


    <?php foreach ($vacancy as $row){ ?>

<div class="custom-modal" id="modal_<?php echo $row->n_slno; ?>">

    <div class="custom-modal-content">

        <span class="close-btn"
              onclick="closeModal('<?php echo $row->n_slno; ?>')">
            &times;
        </span>

        <h2 class="modal-title">
            <?php echo $row->c_title; ?>
        </h2>

<div class="modal-description">
    <?php echo htmlspecialchars_decode(nl2br($row->c_description)); ?>
</div>

    </div>

</div>

<?php } ?>
         


</div>   

      </div>

     





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



/* DATE FILTER DESIGN */

.date-filter-box{
    display:flex;
    align-items:end;
    gap:18px;
    background:#f7fbf8;
    padding:18px;
    border-radius:14px;
    border:1px solid #dbe7df;
    margin:18px 0 22px;
    flex-wrap:wrap;
}

.filter-item{
    flex:1;
    min-width:220px;
}

.filter-item label{
    display:block;
    font-size:12px;
    font-weight:700;
    color:#2d4f3d;
    margin-bottom:7px;
    letter-spacing:0.5px;
}

.date-input-box{
    position:relative;
}

.date-input-box i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#5e8b72;
    font-size:14px;
}

.filter-input{
    width:100%;
    height:48px;
    border:1px solid #cfe0d7;
    border-radius:12px;
    background:#fff;
    padding:0 16px 0 42px;
    font-size:14px;
    color:#374151;
    outline:none;
    transition:0.3s;
}

.filter-input:focus{
    border-color:#416e54;
    box-shadow:0 0 0 3px rgba(65,110,84,0.10);
}

/* FILTER BUTTON */

.filter-btn-box{
    display:flex;
    align-items:end;
}

.btn-filter{
    height:48px;
    padding:0 24px;
    border:none;
    border-radius:12px;
    background:#416e54;
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.btn-filter:hover{
    background:#2d4f3d;
}

/* MOBILE */

@media(max-width:768px){

    .date-filter-box{
        flex-direction:column;
        align-items:stretch;
    }

    .filter-item{
        width:100%;
    }

    .btn-filter{
        width:100%;
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
        autoWidth: false,
        pageLength: 10,

        order: [[0, 'desc']],

        columnDefs: [
            {
                orderable: false,
                targets: [3,4]
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

                url: "<?php echo base_url('delete_vacancy'); ?>",
                type: "POST",
                data: {id:id},

                success: function (response) {

                    console.log(response);

                    if($.trim(response) == '1')
                    {
                        row.fadeOut(500, function(){
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
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed'
                        });
                    }

                },

                error:function(xhr)
                {
                    console.log(xhr.responseText);

                    Swal.fire({
                        icon:'error',
                        title:'Server Error'
                    });
                }

            });

        }

    });

});

</script>



<script>

function openModal(id)
{
    document.getElementById('modal_' + id).classList.add('active');

    document.body.style.overflow = 'hidden';
}

function closeModal(id)
{
    document.getElementById('modal_' + id).classList.remove('active');

    document.body.style.overflow = 'auto';
}

/* CLOSE WHEN CLICK OUTSIDE */
window.onclick = function(event)
{
    let modals = document.getElementsByClassName('custom-modal');

    for(let i = 0; i < modals.length; i++)
    {
        if(event.target === modals[i])
        {
            modals[i].classList.remove('active');

            document.body.style.overflow = 'auto';
        }
    }
}

</script>
