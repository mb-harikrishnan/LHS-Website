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
                        
                            <div class="filter-group">
                                <label>From Date</label>
                                <input type="date" id="fromDate" name="fromDate"  value="<?php echo !empty(set_value('fromDate')) ? set_value('fromDate') : date('Y-m-d'); ?>" class="filter-input">
                            </div>

                            <div class="filter-group">
                                <label>To Date</label>
                                <input type="date" id="toDate" name="toDate" value="<?php echo !empty(set_value('toDate')) ? set_value('toDate') : date('Y-m-d'); ?>" class="filter-input">
                            </div>



                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">Filter</button>
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
  /* =========================================
   PAGE HEADER
========================================= */
.page-header{
    margin-bottom:30px;
}

.page-eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:13px;
    font-weight:700;
    color:#16a34a;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin-bottom:10px;
}

.eyebrow-pulse{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    animation:pulse 1.8s infinite;
}

@keyframes pulse{
    0%{
        transform:scale(.9);
        opacity:.7;
    }
    70%{
        transform:scale(1.4);
        opacity:0;
    }
    100%{
        transform:scale(.9);
        opacity:0;
    }
}

.page-title{
    font-size:34px;
    font-weight:800;
    color:#111827;
    margin:0;
}

.page-sub{
    margin-top:8px;
    color:#6b7280;
    font-size:15px;
}

/* =========================================
   CARD
========================================= */
.card{
    background:#ffffff;
    border-radius:26px;
    padding:30px;
    border:1px solid #eef2f7;
    box-shadow:0 12px 40px rgba(0,0,0,0.06);
}

.card-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.card-title{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}

/* =========================================
   ACTION BUTTON
========================================= */
.card-action{
    border:none;
    outline:none;
    background:linear-gradient(135deg,#15803d,#22c55e);
    color:#fff;
    padding:13px 22px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 24px rgba(34,197,94,.25);
}

.card-action:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 30px rgba(34,197,94,.35);
}

/* =========================================
   FILTER BOX
========================================= */
.date-filter-box{
    display:flex;
    align-items:end;
    gap:20px;
    flex-wrap:wrap;
    background:#f9fafb;
    border:1px solid #e5e7eb;
    padding:24px;
    border-radius:22px;
    margin-bottom:25px;
}

.filter-group{
    flex:1;
    min-width:220px;
}

.filter-group label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:700;
    color:#374151;
}

.filter-input{
    width:100%;
    height:52px;
    padding:0 16px;
    border-radius:14px;
    border:1px solid #d1d5db;
    background:#fff;
    font-size:15px;
    color:#111827;
    outline:none;
    transition:.3s ease;
}

.filter-input:focus{
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,.12);
}

.filter-actions{
    display:flex;
    align-items:end;
}

.btn-filter{
    border:none;
    outline:none;
    height:52px;
    padding:0 28px;
    border-radius:14px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    box-shadow:0 10px 24px rgba(34,197,94,.2);
}

.btn-filter:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(34,197,94,.3);
}

/* =========================================
   REPORT META
========================================= */
.report-meta{
    margin-bottom:15px;
    color:#6b7280;
    font-size:14px;
    font-weight:500;
}

/* =========================================
   TABLE WRAPPER
========================================= */
.report-table-wrap{
    width:100%;
    overflow-x:auto;
}

/* =========================================
   TABLE
========================================= */
.report-table{
    width:100% !important;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    border-radius:18px;
    background:#fff;
}

.report-table thead tr{
    background:linear-gradient(135deg,#14532d,#15803d);
}

.report-table thead th{
    padding:18px 16px;
    color:#fff;
    font-size:14px;
    font-weight:700;
    border:none !important;
    white-space:nowrap;
}

.report-table tbody td{
    padding:16px;
    font-size:14px;
    color:#374151;
    border-bottom:1px solid #eef2f7;
    vertical-align:middle;
}

.report-table tbody tr{
    transition:.25s ease;
}

.report-table tbody tr:hover{
    background:#f0fdf4;
}

/* =========================================
   VIEW BUTTON
========================================= */
.view-btn{
    border:none;
    outline:none;
    background:#2563eb;
    color:#fff;
    padding:9px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
}

.view-btn:hover{
    background:#1d4ed8;
    transform:translateY(-1px);
}

/* =========================================
   DELETE BUTTON
========================================= */
.deleteBtn{
    border:none;
    outline:none;
    background:#dc2626;
    color:#fff;
    padding:10px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:.3s ease;
}

.deleteBtn:hover{
    background:#b91c1c;
    transform:translateY(-1px);
}

/* =========================================
   DATATABLE CUSTOM
========================================= */
.dataTables_wrapper{
    margin-top:10px;
}

.dataTables_filter input{
    border:1px solid #d1d5db !important;
    border-radius:12px !important;
    padding:8px 14px !important;
    margin-left:10px !important;
    outline:none !important;
}

.dataTables_filter input:focus{
    border-color:#22c55e !important;
    box-shadow:0 0 0 4px rgba(34,197,94,.12) !important;
}

.dataTables_length select{
    border:1px solid #d1d5db !important;
    border-radius:10px !important;
    padding:6px 10px !important;
    outline:none !important;
}

.dataTables_paginate .paginate_button{
    border-radius:10px !important;
    margin:0 3px !important;
}

.dataTables_paginate .paginate_button.current{
    background:#16a34a !important;
    border:none !important;
    color:#fff !important;
}

/* =========================================
   EMPTY BOX
========================================= */
.empty-reports{
    padding:50px 20px;
    text-align:center;
    color:#9ca3af;
}

.empty-reports svg{
    margin-bottom:15px;
}

/* =========================================
   RESPONSIVE
========================================= */
@media(max-width:768px){

    .card{
        padding:20px;
        border-radius:20px;
    }

    .page-title{
        font-size:28px;
    }

    .card-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .card-action{
        width:100%;
    }

    .date-filter-box{
        padding:18px;
        gap:16px;
    }

    .filter-group{
        width:100%;
    }

    .filter-actions{
        width:100%;
    }

    .btn-filter{
        width:100%;
    }

    .report-table thead th,
    .report-table tbody td{
        padding:14px 12px;
        font-size:13px;
    }
}  


/* =========================================
   MODAL
========================================= */

.custom-modal{
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    overflow:auto;
    background:rgba(0,0,0,0.55);
    backdrop-filter:blur(4px);
    padding:30px;
    animation:fadeIn .25s ease;
}

.custom-modal.active{
    display:flex;
    align-items:center;
    justify-content:center;
}

.custom-modal-content{
    background:#ffffff;
    width:100%;
    max-width:700px;
    border-radius:24px;
    padding:35px;
    position:relative;
    box-shadow:0 25px 60px rgba(0,0,0,0.18);
    animation:scaleIn .25s ease;
}

.modal-title{
    font-size:28px;
    font-weight:800;
    color:#111827;
    margin-bottom:20px;
    padding-right:40px;
}

.modal-description{
    font-size:15px;
    line-height:1.8;
    color:#4b5563;
    max-height:65vh;
    overflow-y:auto;
    padding-right:10px;
}

.close-btn{
    position:absolute;
    top:18px;
    right:22px;
    font-size:34px;
    font-weight:700;
    color:#6b7280;
    cursor:pointer;
    transition:.3s ease;
}

.close-btn:hover{
    color:#dc2626;
    transform:rotate(90deg);
}

/* ANIMATION */
@keyframes fadeIn{
    from{
        opacity:0;
    }
    to{
        opacity:1;
    }
}

@keyframes scaleIn{
    from{
        transform:scale(.9);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

/* MOBILE */
@media(max-width:768px){

    .custom-modal{
        padding:15px;
    }

    .custom-modal-content{
        padding:24px;
        border-radius:18px;
    }

    .modal-title{
        font-size:22px;
    }

    .modal-description{
        font-size:14px;
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
