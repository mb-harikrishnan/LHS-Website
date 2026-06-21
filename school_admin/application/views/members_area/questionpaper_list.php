    <?php
$pageTitle = 'Reports';
$breadcrumb = 'Reports';
$activePage = 'reports';
$showGlobalSearch = false;




?>


    
    <!-- PAGE CONTENT -->
      <div class="page-header">
        <div class="page-eyebrow">
          <div class="eyebrow-pulse"></div>
          Reports
        </div>
        <h1 class="page-title">Question Papper List
          <!-- <em>Reports</em> -->
        </h1>
        <p class="page-sub"></p>
      </div>

     
      <!-- Reports Table Card -->
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Report Records
            <!-- <span class="card-badge" id="tableBadge">0 records</span> -->
          </div>
          <button class="card-action" 
              onclick="window.location.href='<?php echo base_url('add_paper'); ?>'">
          <i class="fa fa-upload"></i> Add Document
          </button>
        </div>

        <!-- Date Filter -->
      <form method="post" action="<?php echo base_url('questionpaper_list'); ?>">


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
                    <table id="incomeTable" class="display responsive nowrap creative-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>#SL</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Class</th>
                                <th>PDF</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach ($paper as $row){   ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $row->d_date; ?></td>
                                <td><?php echo $row->c_title; ?></td>
                                <td><?php echo $row->c_class; ?></td>
                                <td>

                                    <?php if (!empty($row->c_document)) { ?>

                                        <a href="http://localhost:8000/assets/documents/<?php echo $row->c_document; ?>" 
                                        target="_blank" 
                                        class="btn btn-sm btn-primary">

                                            View

                                        </a>

                                    <?php } else { ?>

                                        No Document

                                    <?php } ?>

                                    </td>
                                <td>
                                   <button class="btn btn-danger deleteBtn" 
                                            data-id="<?php echo $row->n_slno; ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>



                        <?php $count++; } ?>
                        </tbody>
                    </table>


        </div>
        
      </div>

     





   <style>

    /* =========================
       FILTER FORM DESIGN
    ========================== */

    .date-filter-box{
        width:100%;
        background:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:22px;
        margin:20px 0 30px;
        display:flex;
        align-items:end;
        gap:20px;
        flex-wrap:wrap;
        box-shadow:0 4px 18px rgba(0,0,0,0.04);
    }

    .filter-group{
        display:flex;
        flex-direction:column;
        min-width:220px;
        flex:1;
    }

    .filter-group label{
        font-size:13px;
        font-weight:600;
        color:#374151;
        margin-bottom:8px;
    }

    .filter-input{
        height:48px;
        border:1px solid #d1d5db;
        border-radius:12px;
        padding:0 15px;
        font-size:14px;
        transition:0.3s;
        background:#fff;
    }

    .filter-input:focus{
        border-color:#16a34a;
        outline:none;
        box-shadow:0 0 0 4px rgba(22,163,74,0.10);
    }

    .filter-actions{
        display:flex;
        align-items:end;
    }

    .btn-filter{
        height:48px;
        padding:0 28px;
        border:none;
        border-radius:12px;
        background:linear-gradient(135deg,#16a34a,#15803d);
        color:#fff;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
        transition:0.3s;
    }

    .btn-filter:hover{
        transform:translateY(-2px);
        box-shadow:0 8px 20px rgba(22,163,74,0.25);
    }

    /* =========================
       TABLE DESIGN
    ========================== */

    .creative-table{
        width:100% !important;
        border-collapse:separate !important;
        border-spacing:0;
        margin-top:10px;
    }

    .creative-table thead th{
        background:#14532d;
        color:#fff;
        font-size:13px;
        font-weight:600;
        padding:16px 14px !important;
        border:none !important;
        text-transform:uppercase;
        letter-spacing:0.5px;
    }

    .creative-table thead th:first-child{
        border-top-left-radius:14px;
    }

    .creative-table thead th:last-child{
        border-top-right-radius:14px;
    }

    .creative-table tbody tr{
        transition:0.3s;
    }

    .creative-table tbody tr:hover{
        background:#f0fdf4 !important;
    }

    .creative-table tbody td{
        padding:16px 14px !important;
        font-size:14px;
        color:#374151;
        vertical-align:middle;
        border-bottom:1px solid #edf2f7 !important;
        background:#fff;
    }

    .creative-table tbody tr:last-child td:first-child{
        border-bottom-left-radius:14px;
    }

    .creative-table tbody tr:last-child td:last-child{
        border-bottom-right-radius:14px;
    }

    /* =========================
       BUTTONS
    ========================== */

    .btn-view{
        background:#2563eb;
        color:#fff;
        padding:8px 16px;
        border-radius:8px;
        text-decoration:none;
        font-size:13px;
        font-weight:600;
        display:inline-flex;
        align-items:center;
        gap:6px;
        transition:0.3s;
    }

    .btn-view:hover{
        background:#1d4ed8;
        color:#fff;
        transform:translateY(-1px);
    }

    .deleteBtn{
        background:#dc2626;
        border:none;
        color:#fff;
        padding:9px 16px;
        border-radius:8px;
        font-size:13px;
        font-weight:600;
        cursor:pointer;
        transition:0.3s;
    }

    .deleteBtn:hover{
        background:#b91c1c;
        transform:translateY(-1px);
    }

    .card-action{
        background:linear-gradient(135deg,#16a34a,#15803d);
        color:#fff;
        border:none;
        border-radius:12px;
        padding:12px 18px;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:8px;
        transition:0.3s;
    }

    .card-action:hover{
        transform:translateY(-2px);
        box-shadow:0 10px 20px rgba(22,163,74,0.20);
    }

    /* =========================
       DATATABLE DESIGN
    ========================== */

    div.dataTables_wrapper div.dataTables_filter input{
        height:42px;
        border-radius:10px;
        border:1px solid #d1d5db;
        padding:0 14px;
        margin-left:10px;
    }

    div.dataTables_wrapper div.dataTables_length select{
        height:40px;
        border-radius:10px;
        border:1px solid #d1d5db;
        padding:0 10px;
    }

    .dataTables_paginate .paginate_button{
        border-radius:8px !important;
        margin:0 3px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
        background:#16a34a !important;
        border:none !important;
        color:#fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
        background:#15803d !important;
        color:#fff !important;
        border:none !important;
    }

    /* =========================
       MOBILE
    ========================== */

    @media(max-width:768px){

        .date-filter-box{
            padding:18px;
            gap:15px;
        }

        .filter-group{
            min-width:100%;
        }

        .filter-actions{
            width:100%;
        }

        .btn-filter{
            width:100%;
        }

        .card-head{
            flex-direction:column;
            align-items:flex-start;
            gap:15px;
        }

        .card-action{
            width:100%;
            justify-content:center;
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

    $('#incomeTable').DataTable({
        responsive: true,
        pageLength: 10,
        autoWidth: false,

        columnDefs: [
            { orderable: false, targets: [3,4,5] }
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

                url: "<?php echo base_url('delete_paper'); ?>",
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
